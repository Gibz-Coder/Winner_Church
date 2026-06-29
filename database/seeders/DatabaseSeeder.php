<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin'], [
            'display_name' => 'Administrator',
            'description' => 'Full system access and control',
        ]);

        $memberRole = Role::firstOrCreate(['name' => 'member'], [
            'display_name' => 'Church Member',
            'description' => 'Can view and request to borrow assets',
        ]);

        // 2. Create Permissions
        $permissions = [
            'manage_users' => 'Manage system users',
            'manage_categories' => 'Manage asset categories',
            'manage_assets' => 'Manage assets and details',
            'approve_borrowing' => 'Approve/reject borrow requests',
            'process_returns' => 'Process return requests',
            'view_reports' => 'View inventory and usage reports',
            'view_audit_logs' => 'View administrative audit logs',
            'manage_maintenance' => 'Schedule and manage maintenance',
            'manage_disposed' => 'Manage disposed assets',
            'borrow_assets' => 'Request to borrow assets',
        ];

        $permissionModels = [];
        foreach ($permissions as $name => $desc) {
            $permissionModels[$name] = Permission::firstOrCreate(['name' => $name], [
                'display_name' => ucwords(str_replace('_', ' ', $name)),
                'description' => $desc,
            ]);
        }

        // 3. Link permissions to roles
        $adminRole->permissions()->sync(array_column($permissionModels, 'id'));
        $memberRole->permissions()->sync([
            $permissionModels['borrow_assets']->id,
        ]);

        // 4. Create Users and assign roles
        $adminUser = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $adminUser->roles()->sync([$adminRole->id]);

        $memberUser = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ]);
        $memberUser->roles()->sync([$memberRole->id]);

        // 5. Run other seeders
        $this->call([
            CategorySeeder::class,
            AssetSeeder::class,
        ]);
    }
}
