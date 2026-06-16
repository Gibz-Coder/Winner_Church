<?php

use App\Enums\AssetLogAction;
use App\Enums\AssetStatus;
use App\Models\Asset;
use App\Models\Category;
use App\Models\User;

test('guests cannot access the asset index', function () {
    $this->get(route('assets.index'))->assertRedirect(route('login'));
});

test('authenticated users can view the asset index', function () {
    $user = User::factory()->create();
    Asset::factory()->count(3)->create();

    $this->actingAs($user)
        ->get(route('assets.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('assets/Index')
            ->has('assets.data', 3)
        );
});

test('the index can be filtered by status', function () {
    $user = User::factory()->create();
    Asset::factory()->create(['status' => AssetStatus::Available]);
    Asset::factory()->create(['status' => AssetStatus::Disposed]);

    $this->actingAs($user)
        ->get(route('assets.index', ['status' => AssetStatus::Disposed->value]))
        ->assertInertia(fn ($page) => $page->has('assets.data', 1));
});

test('an asset can be created and logs the creation', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

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
    $category = Category::factory()->create();
    Asset::factory()->create(['serial_number' => 'DUPLICATE']);

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
    $asset = Asset::factory()->create(['status' => AssetStatus::Available]);

    $this->actingAs($user)->put(route('assets.update', $asset), [
        'category_id' => $asset->category_id,
        'name' => $asset->name,
        'status' => AssetStatus::UnderRepair->value,
    ])->assertRedirect(route('assets.show', $asset));

    expect($asset->logs()->where('action', AssetLogAction::StatusChange)->exists())->toBeTrue();
});

test('an asset can be deleted', function () {
    $user = User::factory()->create();
    $asset = Asset::factory()->create();

    $this->actingAs($user)
        ->delete(route('assets.destroy', $asset))
        ->assertRedirect(route('assets.index'));

    $this->assertDatabaseMissing('assets', ['id' => $asset->id]);
});

test('the show page defers the activity logs', function () {
    $user = User::factory()->create();
    $asset = Asset::factory()->create();

    $this->actingAs($user)
        ->get(route('assets.show', $asset))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('assets/Show')
            ->where('asset.id', $asset->id)
            ->missing('logs')
        );
});
