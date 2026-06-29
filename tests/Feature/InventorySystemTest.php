<?php

use App\Enums\AssetStatus;
use App\Models\Asset;
use App\Models\BorrowRequest;
use App\Models\Category;
use App\Models\MaintenanceLog;
use App\Models\User;

beforeEach(function () {
    // Run DatabaseSeeder to set up roles, permissions and standard assets
    $this->artisan('db:seed');
});

test('user has roles and check helper works', function () {
    $admin = User::where('email', 'admin@example.com')->first();
    $member = User::where('email', 'john@example.com')->first();

    expect($admin->hasRole('admin'))->toBeTrue();
    expect($member->hasRole('member'))->toBeTrue();
    expect($member->hasRole('admin'))->toBeFalse();
});

test('member can submit borrow request and admin can approve and return it', function () {
    $member = User::where('email', 'john@example.com')->first();
    $admin = User::where('email', 'admin@example.com')->first();

    // Create an available asset
    $category = Category::first();
    $asset = Asset::factory()->create([
        'category_id' => $category->id,
        'status' => AssetStatus::Available,
    ]);

    // 1. Member submits borrow request
    $response = $this->actingAs($member)
        ->post(route('borrow-requests.store'), [
            'asset_id' => $asset->id,
            'borrow_date' => now()->addDay()->format('Y-m-d H:i:s'),
            'expected_return_date' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'borrow_condition' => 'Perfect',
        ]);

    $response->assertRedirect(route('borrow-requests.index'));

    // Assert request was created and asset status is reserved
    $borrowRequest = BorrowRequest::where('asset_id', $asset->id)->first();
    expect($borrowRequest)->not->toBeNull();
    expect($borrowRequest->status)->toBe('pending');
    expect($asset->fresh()->status)->toBe(AssetStatus::Reserved);

    // 2. Admin approves borrow request
    $response = $this->actingAs($admin)
        ->post(route('borrow-requests.approve', $borrowRequest->id));

    $response->assertRedirect(route('borrow-requests.index'));
    expect($borrowRequest->fresh()->status)->toBe('approved');
    expect($asset->fresh()->status)->toBe(AssetStatus::Borrowed);

    // 3. Admin processes return
    $response = $this->actingAs($admin)
        ->post(route('borrow-requests.return', $borrowRequest->id), [
            'return_condition' => 'Good',
            'remarks' => 'Returned on time.',
        ]);

    $response->assertRedirect(route('borrow-requests.index'));
    expect($borrowRequest->fresh()->status)->toBe('returned');
    expect($asset->fresh()->status)->toBe(AssetStatus::Available);
});

test('admin can log and complete asset maintenance', function () {
    $admin = User::where('email', 'admin@example.com')->first();
    $category = Category::first();
    $asset = Asset::factory()->create([
        'category_id' => $category->id,
        'status' => AssetStatus::Available,
    ]);

    // 1. Create maintenance log
    $response = $this->actingAs($admin)
        ->post(route('maintenance.store'), [
            'asset_id' => $asset->id,
            'maintenance_type' => 'repair',
            'description' => 'Fixing broken stand',
            'start_date' => now()->format('Y-m-d'),
            'status' => 'in_progress',
        ]);

    $response->assertRedirect(route('maintenance.index'));

    $log = MaintenanceLog::where('asset_id', $asset->id)->first();
    expect($log)->not->toBeNull();
    expect($log->status)->toBe('in_progress');
    expect($asset->fresh()->status)->toBe(AssetStatus::UnderMaintenance);

    // 2. Complete maintenance
    $response = $this->actingAs($admin)
        ->put(route('maintenance.update', $log->id), [
            'status' => 'completed',
            'cost' => 120.50,
            'end_date' => now()->format('Y-m-d'),
            'description' => 'Replaced main frame stand.',
        ]);

    $response->assertRedirect(route('maintenance.index'));
    expect($log->fresh()->status)->toBe('completed');
    expect($asset->fresh()->status)->toBe(AssetStatus::Available);
});
