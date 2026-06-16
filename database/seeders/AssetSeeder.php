<?php

namespace Database\Seeders;

use App\Enums\AssetLogAction;
use App\Models\Asset;
use App\Models\AssetLog;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->first() ?? User::factory()->create();

        Category::query()->each(function (Category $category) use ($user): void {
            Asset::factory()
                ->count(5)
                ->for($category)
                ->create()
                ->each(function (Asset $asset) use ($user): void {
                    AssetLog::factory()->for($asset)->for($user)->create([
                        'action' => AssetLogAction::Created,
                        'description' => "Asset \"{$asset->name}\" was added to the inventory.",
                    ]);
                });
        });
    }
}
