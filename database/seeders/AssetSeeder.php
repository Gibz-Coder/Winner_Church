<?php

namespace Database\Seeders;

use App\Enums\AssetLogAction;
use App\Enums\AssetStatus;
use App\Models\Asset;
use App\Models\AssetLog;
use App\Models\AuditLog;
use App\Models\BorrowRequest;
use App\Models\Category;
use App\Models\MaintenanceLog;
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
        $admin = User::where('name', 'Admin')->first() ?? User::factory()->create(['name' => 'Admin']);
        $john = User::where('name', 'John Doe')->first() ?? User::factory()->create(['name' => 'John Doe']);

        $vehicles = Category::where('slug', 'vehicles')->first();
        $mediaMusic = Category::where('slug', 'media-musical-instruments')->first();
        $officeFurniture = Category::where('slug', 'office-furniture')->first();

        // Let's seed specific items matching user list
        // Vehicles
        $van = Asset::factory()->create([
            'category_id' => $vehicles->id,
            'name' => 'Church Van',
            'serial_number' => 'VAN-2025-01',
            'model_number' => 'HiAce Super Grandia',
            'brand' => 'Toyota',
            'status' => AssetStatus::Available,
            'current_location' => 'Church Parking A',
            'assigned_ministry' => 'Transportation',
            'description' => '15-seater church passenger van for outreach and transit.',
            'qr_code' => 'http://localhost:8000/assets/1',
        ]);

        $car = Asset::factory()->create([
            'category_id' => $vehicles->id,
            'name' => "Pastor's Car",
            'serial_number' => 'CAR-2024-02',
            'model_number' => 'Camry Hybrid',
            'brand' => 'Toyota',
            'status' => AssetStatus::Available,
            'current_location' => 'Pastorate Garage',
            'assigned_ministry' => 'Administration',
            'description' => 'Official vehicle assigned for pastoral engagements.',
            'qr_code' => 'http://localhost:8000/assets/2',
        ]);

        // Media & Musical Instruments
        $drums = Asset::factory()->create([
            'category_id' => $mediaMusic->id,
            'name' => 'Yamaha Digital Drum Kit',
            'serial_number' => 'YMH-DTX452K',
            'model_number' => 'DTX452K',
            'brand' => 'Yamaha',
            'status' => AssetStatus::Available,
            'current_location' => 'Main Sanctuary Stage',
            'assigned_ministry' => 'Music & Worship',
            'description' => 'Electronic drum set with standard pads and module.',
            'qr_code' => 'http://localhost:8000/assets/3',
        ]);

        $bass = Asset::factory()->create([
            'category_id' => $mediaMusic->id,
            'name' => 'Fender Bass Guitar',
            'serial_number' => 'FND-BASS-881',
            'model_number' => 'Jazz Bass V',
            'brand' => 'Fender',
            'status' => AssetStatus::Available,
            'current_location' => 'Main Sanctuary Stage',
            'assigned_ministry' => 'Music & Worship',
            'description' => '5-string active electric bass guitar for services.',
            'qr_code' => 'http://localhost:8000/assets/4',
        ]);

        $mixer = Asset::factory()->create([
            'category_id' => $mediaMusic->id,
            'name' => 'Behringer X32',
            'serial_number' => 'BHG-X32-9021',
            'model_number' => 'X32 Digital Mixer',
            'brand' => 'Behringer',
            'status' => AssetStatus::Borrowed,
            'current_location' => 'Youth Chapel',
            'assigned_ministry' => 'Media & Sound',
            'description' => '40-input, 25-bus digital mixing console.',
            'qr_code' => 'http://localhost:8000/assets/5',
        ]);

        $mics = Asset::factory()->create([
            'category_id' => $mediaMusic->id,
            'name' => 'Wireless Microphones',
            'serial_number' => 'SHR-QLXD24',
            'model_number' => 'QLXD4 Receiver + QLXD2',
            'brand' => 'Shure',
            'status' => AssetStatus::Available,
            'current_location' => 'Audio Control Booth',
            'assigned_ministry' => 'Media & Sound',
            'description' => 'Set of 4 high-end wireless handheld microphones.',
            'qr_code' => 'http://localhost:8000/assets/6',
        ]);

        $lights = Asset::factory()->create([
            'category_id' => $mediaMusic->id,
            'name' => 'Stage Lights',
            'serial_number' => 'CHR-LED-PAR',
            'model_number' => 'Colorado 1 Solo',
            'brand' => 'Chauvet Professional',
            'status' => AssetStatus::Available,
            'current_location' => 'Sanctuary Truss',
            'assigned_ministry' => 'Media & Sound',
            'description' => 'DMX-controlled LED Par wash fixtures.',
            'qr_code' => 'http://localhost:8000/assets/7',
        ]);

        $camera = Asset::factory()->create([
            'category_id' => $mediaMusic->id,
            'name' => 'Cameras',
            'serial_number' => 'SNY-FX3-009',
            'model_number' => 'ILME-FX3 Cinema',
            'brand' => 'Sony',
            'status' => AssetStatus::Borrowed,
            'current_location' => 'Media Room',
            'assigned_ministry' => 'Media & Sound',
            'description' => 'Full-frame cinema camera used for livestreaming and video production.',
            'qr_code' => 'http://localhost:8000/assets/8',
        ]);

        $ledWall = Asset::factory()->create([
            'category_id' => $mediaMusic->id,
            'name' => 'LED Wall',
            'serial_number' => 'LED-WALL-W10',
            'model_number' => 'P2.5 LED Panel Grid',
            'brand' => 'Absen',
            'status' => AssetStatus::Available,
            'current_location' => 'Main Sanctuary Stage',
            'assigned_ministry' => 'Media & Sound',
            'description' => 'Modular LED Screen grid backdrop behind sanctuary stage.',
            'qr_code' => 'http://localhost:8000/assets/9',
        ]);

        // Office & Furniture
        $chairs = Asset::factory()->create([
            'category_id' => $officeFurniture->id,
            'name' => 'Plastic Chairs',
            'serial_number' => 'PLC-CHAIR-100',
            'model_number' => 'Heavy Duty Plastic Stackable',
            'brand' => 'Monobloc',
            'status' => AssetStatus::Available,
            'current_location' => 'Fellowship Hall',
            'assigned_ministry' => 'General Property',
            'description' => 'Pack of stackable chairs for church events.',
            'qr_code' => 'http://localhost:8000/assets/10',
        ]);

        $communion = Asset::factory()->create([
            'category_id' => $officeFurniture->id,
            'name' => 'Communion Tables',
            'serial_number' => 'TBL-COMM-01',
            'model_number' => 'Wooden Communion Table',
            'brand' => 'Custom Woodworks',
            'status' => AssetStatus::Available,
            'current_location' => 'Altar Area',
            'assigned_ministry' => 'Worship Support',
            'description' => 'Polished mahogany wood altar table inscribed with "In Remembrance of Me".',
            'qr_code' => 'http://localhost:8000/assets/11',
        ]);

        $desk = Asset::factory()->create([
            'category_id' => $officeFurniture->id,
            'name' => 'Office Tables',
            'serial_number' => 'TBL-OFFC-05',
            'model_number' => 'L-Shaped Desk',
            'brand' => 'OfficeDepot',
            'status' => AssetStatus::Available,
            'current_location' => 'Pastor\'s Office',
            'assigned_ministry' => 'Administration',
            'description' => 'Wooden executive office desk with drawers.',
            'qr_code' => 'http://localhost:8000/assets/12',
        ]);

        $projector = Asset::factory()->create([
            'category_id' => $officeFurniture->id,
            'name' => 'Projectors',
            'serial_number' => 'EPS-PRJ-01',
            'model_number' => 'EB-FH52 3LCD',
            'brand' => 'Epson',
            'status' => AssetStatus::UnderMaintenance,
            'current_location' => 'Main Office',
            'assigned_ministry' => 'Media & Sound',
            'description' => '4000 lumens wireless projector.',
            'qr_code' => 'http://localhost:8000/assets/13',
        ]);

        $laptop = Asset::factory()->create([
            'category_id' => $officeFurniture->id,
            'name' => 'Laptops',
            'serial_number' => 'APL-MBP-2025',
            'model_number' => 'MacBook Pro 16" M3',
            'brand' => 'Apple',
            'status' => AssetStatus::Available,
            'current_location' => 'Main Office',
            'assigned_ministry' => 'Media & Sound',
            'description' => 'Office workstation laptop for church administration and live controls.',
            'qr_code' => 'http://localhost:8000/assets/14',
        ]);

        $printer = Asset::factory()->create([
            'category_id' => $officeFurniture->id,
            'name' => 'Printers',
            'serial_number' => 'HP-LJT-M404',
            'model_number' => 'LaserJet Pro M404dn',
            'brand' => 'HP',
            'status' => AssetStatus::Available,
            'current_location' => 'Secretary Room',
            'assigned_ministry' => 'Administration',
            'description' => 'Mono laser office printing machine.',
            'qr_code' => 'http://localhost:8000/assets/15',
        ]);

        // Standard Asset creation logs
        $seededAssets = [$van, $car, $drums, $bass, $mixer, $mics, $lights, $camera, $ledWall, $chairs, $communion, $desk, $projector, $laptop, $printer];
        foreach ($seededAssets as $asset) {
            AssetLog::create([
                'asset_id' => $asset->id,
                'user_id' => $admin->id,
                'action' => AssetLogAction::Created,
                'description' => "Asset \"{$asset->name}\" was added to the inventory.",
                'created_at' => now()->subDays(15),
            ]);
        }

        // Add some borrow requests and logs
        $request1 = BorrowRequest::create([
            'asset_id' => $camera->id,
            'user_id' => $john->id,
            'status' => 'approved',
            'borrow_date' => now()->subDays(5),
            'expected_return_date' => now()->addDays(2),
            'borrow_condition' => 'Excellent - clean lens',
            'authorized_by' => $admin->id,
        ]);

        $request2 = BorrowRequest::create([
            'asset_id' => $mixer->id,
            'user_id' => $john->id,
            'status' => 'approved',
            'borrow_date' => now()->subDays(3),
            'expected_return_date' => now()->addDays(4),
            'borrow_condition' => 'Fully functional',
            'authorized_by' => $admin->id,
        ]);

        // Add a pending request
        $requestPending = BorrowRequest::create([
            'asset_id' => $drums->id,
            'user_id' => $john->id,
            'status' => 'pending',
            'borrow_date' => now()->addDays(1),
            'expected_return_date' => now()->addDays(5),
            'borrow_condition' => 'Excellent condition',
        ]);

        // Add maintenance logs
        MaintenanceLog::create([
            'asset_id' => $projector->id,
            'maintenance_type' => 'repair',
            'description' => 'Replacing bulb and cleaning fan filters.',
            'cost' => 150.00,
            'start_date' => now()->subDays(2),
            'status' => 'in_progress',
            'performed_by' => 'Epson Service Center',
        ]);

        // Seed some history logs
        AssetLog::create([
            'asset_id' => $camera->id,
            'user_id' => $john->id,
            'action' => AssetLogAction::CheckedOut,
            'description' => 'Checked out Sony camera for outreach livestream.',
            'created_at' => now()->subDays(5),
        ]);

        AssetLog::create([
            'asset_id' => $mixer->id,
            'user_id' => $john->id,
            'action' => AssetLogAction::CheckedOut,
            'description' => 'Checked out Behringer X32 mixer for youth conference.',
            'created_at' => now()->subDays(3),
        ]);

        // Seed audit logs
        AuditLog::create([
            'user_id' => $admin->id,
            'action' => 'Created Category',
            'auditable_type' => Category::class,
            'auditable_id' => $vehicles->id,
            'new_values' => ['name' => 'Vehicles'],
            'ip_address' => '127.0.0.1',
        ]);
    }
}
