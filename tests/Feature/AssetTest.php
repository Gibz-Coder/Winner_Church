<?php

use App\Enums\AssetLogAction;
use App\Enums\AssetStatus;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Role;
use App\Models\User;

beforeEach(function () {
    // Seed roles and permissions
    $this->artisan('db:seed');
});

test('guests cannot access the asset index', function () {
    $this->get(route('assets.index'))->assertRedirect(route('login'));
});

test('authenticated users can view the asset index', function () {
    $user = User::factory()->create();
    $category = Category::first();
    Asset::factory()->count(3)->create(['category_id' => $category->id]);

    $this->actingAs($user)
        ->get(route('assets.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('assets/Index')
            ->has('assets.data', min(10, Asset::count()))
        );
});

test('the index can be filtered by status', function () {
    $user = User::factory()->create();
    $category = Category::first();
    Asset::factory()->create(['status' => AssetStatus::Available, 'category_id' => $category->id]);
    Asset::factory()->create(['status' => AssetStatus::Disposed, 'category_id' => $category->id]);

    $this->actingAs($user)
        ->get(route('assets.index', ['status' => AssetStatus::Disposed->value]))
        ->assertInertia(fn ($page) => $page->has('assets.data', 1));
});

test('an asset can be created and logs the creation', function () {
    // Create an admin user to authorize asset management
    $user = User::factory()->create();
    $adminRole = Role::where('name', 'admin')->first();
    $user->roles()->attach($adminRole);

    $category = Category::first();

    $response = $this->actingAs($user)->post(route('assets.store'), [
        'category_id' => $category->id,
        'name' => 'Sony A7 III',
        'serial_number' => 'SN-12345',
        'status' => AssetStatus::Available->value,
    ]);

    $asset = Asset::firstWhere('name', 'Sony A7 III');

    $response->assertRedirect(route('assets.show', $asset));
    expect($asset->logs()->where('action', AssetLogAction::Created)->exists())->toBeTrue();
});

test('serial numbers must be unique when provided', function () {
    $user = User::factory()->create();
    $adminRole = Role::where('name', 'admin')->first();
    $user->roles()->attach($adminRole);

    $category = Category::first();
    Asset::factory()->create(['serial_number' => 'DUPLICATE', 'category_id' => $category->id]);

    $this->actingAs($user)
        ->post(route('assets.store'), [
            'category_id' => $category->id,
            'name' => 'Another Asset',
            'serial_number' => 'DUPLICATE',
            'status' => AssetStatus::Available->value,
        ])
        ->assertSessionHasErrors('serial_number');
});

test('updating the status records a status change log', function () {
    $user = User::factory()->create();
    $adminRole = Role::where('name', 'admin')->first();
    $user->roles()->attach($adminRole);

    $category = Category::first();
    $asset = Asset::factory()->create(['status' => AssetStatus::Available, 'category_id' => $category->id]);

    $this->actingAs($user)->put(route('assets.update', $asset), [
        'category_id' => $asset->category_id,
        'name' => $asset->name,
        'status' => AssetStatus::UnderMaintenance->value,
    ])->assertRedirect(route('assets.show', $asset));

    expect($asset->logs()->where('action', AssetLogAction::StatusChange)->exists())->toBeTrue();
});

test('an asset can be deleted', function () {
    $user = User::factory()->create();
    $adminRole = Role::where('name', 'admin')->first();
    $user->roles()->attach($adminRole);

    $category = Category::first();
    $asset = Asset::factory()->create(['category_id' => $category->id]);

    $this->actingAs($user)
        ->delete(route('assets.destroy', $asset))
        ->assertRedirect(route('assets.index'));

    $this->assertDatabaseMissing('assets', ['id' => $asset->id]);
});

test('the show page defers the activity logs', function () {
    $user = User::factory()->create();
    $category = Category::first();
    $asset = Asset::factory()->create(['category_id' => $category->id]);

    $this->actingAs($user)
        ->get(route('assets.show', $asset))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('assets/Show')
            ->where('asset.id', $asset->id)
            ->missing('logs')
        );
});
