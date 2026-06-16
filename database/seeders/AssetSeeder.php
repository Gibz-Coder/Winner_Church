<?php

namespace Database\Seeders;

use App\Enums\AssetLogAction;
use App\Enums\AssetStatus;
use App\Models\Asset;
use App\Models\AssetLog;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AssetSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('name', 'Admin')->first() ?? User::factory()->create(['name' => 'Admin']);
        $john = User::where('name', 'John Doe')->first() ?? User::factory()->create(['name' => 'John Doe']);

        $media = Category::where('slug', 'media')->first();
        $musical = Category::where('slug', 'musical-instruments')->first();
        $electronics = Category::where('slug', 'electronics-gadgets')->first();
        $general = Category::where('slug', 'general-property')->first();

        // 1. Create the specific assets shown in the dashboard table:
        $sonyCamera = Asset::factory()->create([
            'category_id' => $media->id,
            'name' => 'Sony A7iii Camera',
            'serial_number' => '1234567890',
            'model_number' => 'ILCE-7M3',
            'brand' => 'Sony',
            'status' => AssetStatus::InUse,
            'current_location' => 'Main Sanctuary',
            'updated_at' => Carbon::parse('2025-05-18 10:00:00'),
        ]);

        $yamahaKey = Asset::factory()->create([
            'category_id' => $musical->id,
            'name' => 'Yamaha PSR-E473',
            'serial_number' => 'YMH872364',
            'model_number' => 'PSR-E473',
            'brand' => 'Yamaha',
            'status' => AssetStatus::Available,
            'current_location' => 'Music Room',
            'updated_at' => Carbon::parse('2025-05-17 14:30:00'),
        ]);

        $macbook = Asset::factory()->create([
            'category_id' => $electronics->id,
            'name' => 'MacBook Pro 14"',
            'serial_number' => 'C02FG1ABCMD6',
            'model_number' => 'A2442',
            'brand' => 'Apple',
            'status' => AssetStatus::InUse,
            'current_location' => 'Media Room',
            'updated_at' => Carbon::parse('2025-05-17 11:15:00'),
        ]);

        $shureMic = Asset::factory()->create([
            'category_id' => $musical->id,
            'name' => 'Shure SM58 Mic',
            'serial_number' => 'SH58A91234',
            'model_number' => 'SM58',
            'brand' => 'Shure',
            'status' => AssetStatus::UnderRepair,
            'current_location' => 'Audio Room',
            'updated_at' => Carbon::parse('2025-05-16 09:00:00'),
        ]);

        $chair = Asset::factory()->create([
            'category_id' => $general->id,
            'name' => 'Plastic Church Chair',
            'serial_number' => 'CHAIR-0891',
            'model_number' => 'CHAIR-0891',
            'brand' => 'Generic',
            'status' => AssetStatus::Available,
            'current_location' => 'Fellowship Hall',
            'updated_at' => Carbon::parse('2025-05-15 16:45:00'),
        ]);

        $ledLight = Asset::factory()->create([
            'category_id' => $media->id,
            'name' => 'LED Studio Light',
            'serial_number' => 'LED-5542-ABC',
            'model_number' => 'LED-5542',
            'brand' => 'Neewer',
            'status' => AssetStatus::Available,
            'current_location' => 'Media Room',
            'updated_at' => now()->subDays(2),
        ]);

        // Create standard creation logs for these assets
        foreach ([$sonyCamera, $yamahaKey, $macbook, $shureMic, $chair, $ledLight] as $asset) {
            AssetLog::factory()->for($asset)->for($admin)->create([
                'action' => AssetLogAction::Created,
                'description' => "Asset \"{$asset->name}\" was added to the inventory.",
                'created_at' => $asset->updated_at->copy()->subHours(2),
            ]);
        }

        // Seed the 4 specific recent activities:
        // 1. Checked In Shure SM58 Mic by Admin, 2h ago
        AssetLog::factory()->for($shureMic)->for($admin)->create([
            'action' => AssetLogAction::CheckedIn,
            'description' => 'Checked in Shure SM58 Mic',
            'created_at' => now()->subHours(2),
        ]);

        // 2. Checked Out Sony A7iii Camera by John Doe, 5h ago
        AssetLog::factory()->for($sonyCamera)->for($john)->create([
            'action' => AssetLogAction::CheckedOut,
            'description' => 'Checked out Sony A7iii Camera',
            'created_at' => now()->subHours(5),
        ]);

        // 3. Updated Asset Yamaha PSR-E473 by Admin, 1d ago
        AssetLog::factory()->for($yamahaKey)->for($admin)->create([
            'action' => AssetLogAction::Updated,
            'description' => 'Updated asset details',
            'created_at' => now()->subDay(),
        ]);

        // 4. New Asset Added LED Studio Light by Admin, 2d ago
        AssetLog::where('asset_id', $ledLight->id)->first()->update([
            'created_at' => now()->subDays(2),
        ]);

        // Seed remaining statuses to reach total counts
        $statusPool = [];
        for ($i = 0; $i < 69; $i++) {
            $statusPool[] = AssetStatus::Available;
        }
        for ($i = 0; $i < 32; $i++) {
            $statusPool[] = AssetStatus::InUse;
        }
        for ($i = 0; $i < 7; $i++) {
            $statusPool[] = AssetStatus::UnderRepair;
        }
        for ($i = 0; $i < 14; $i++) {
            $statusPool[] = AssetStatus::Disposed;
        }

        shuffle($statusPool);

        $categoryTargets = [
            $media->id => 26,
            $musical->id => 34,
            $electronics->id => 23,
            $general->id => 39,
        ];

        $statusIndex = 0;
        foreach ($categoryTargets as $catId => $count) {
            for ($k = 0; $k < $count; $k++) {
                $status = $statusPool[$statusIndex++];
                $asset = Asset::factory()->create([
                    'category_id' => $catId,
                    'status' => $status,
                ]);

                AssetLog::factory()->for($asset)->for($admin)->create([
                    'action' => AssetLogAction::Created,
                    'description' => "Asset \"{$asset->name}\" was added to the inventory.",
                    'created_at' => now()->subDays(rand(3, 30)),
                ]);
            }
        }
    }
}
