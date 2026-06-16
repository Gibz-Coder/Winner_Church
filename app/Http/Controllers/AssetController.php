<?php

namespace App\Http\Controllers;

use App\Enums\AssetLogAction;
use App\Enums\AssetStatus;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Models\Asset;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AssetController extends Controller
{
    /**
     * Display a searchable, filterable listing of assets.
     */
    public function index(Request $request): Response
    {
        $filters = [
            'search' => $request->string('search')->toString(),
            'status' => $request->string('status')->toString(),
            'category' => $request->integer('category') ?: null,
        ];

        $assets = Asset::query()
            ->with('category:id,name')
            ->when($filters['search'], function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('serial_number', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%");
                });
            })
            ->when($filters['status'], fn ($query, string $status) => $query->where('status', $status))
            ->when($filters['category'], fn ($query, int $category) => $query->where('category_id', $category))
            ->latest()
            ->paginate(10)
            ->withQueryString()
            ->through(fn (Asset $asset): array => [
                'id' => $asset->id,
                'name' => $asset->name,
                'serial_number' => $asset->serial_number,
                'brand' => $asset->brand,
                'current_location' => $asset->current_location,
                'status' => $asset->status->value,
                'status_label' => $asset->status->label(),
                'category' => $asset->category->name,
            ]);

        return Inertia::render('assets/Index', [
            'assets' => $assets,
            'filters' => $filters,
            'categories' => $this->categoryOptions(),
            'statuses' => AssetStatus::options(),
        ]);
    }

    /**
     * Show the form for creating a new asset.
     */
    public function create(): Response
    {
        return Inertia::render('assets/Create', [
            'categories' => $this->categoryOptions(),
            'statuses' => AssetStatus::options(),
        ]);
    }

    /**
     * Store a newly created asset and log its creation.
     */
    public function store(StoreAssetRequest $request): RedirectResponse
    {
        $asset = Asset::query()->create($request->validated());

        $asset->logs()->create([
            'user_id' => $request->user()->id,
            'action' => AssetLogAction::Created,
            'description' => "Asset \"{$asset->name}\" was added to the inventory.",
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Asset created.')]);

        return to_route('assets.show', $asset);
    }

    /**
     * Display the specified asset with a deferred activity timeline.
     */
    public function show(Asset $asset): Response
    {
        $asset->load('category:id,name');

        return Inertia::render('assets/Show', [
            'asset' => [
                ...$asset->only([
                    'id', 'name', 'serial_number', 'model_number', 'brand',
                    'current_location', 'notes',
                ]),
                'purchase_date' => $asset->purchase_date?->toDateString(),
                'cost' => $asset->cost,
                'status' => $asset->status->value,
                'status_label' => $asset->status->label(),
                'category' => $asset->category->name,
            ],
            'logs' => Inertia::defer(fn (): array => $asset->logs()
                ->with('user:id,name')
                ->get()
                ->map(fn ($log): array => [
                    'id' => $log->id,
                    'action' => $log->action->value,
                    'action_label' => $log->action->label(),
                    'description' => $log->description,
                    'user' => $log->user?->name,
                    'created_at' => $log->created_at?->toIso8601String(),
                ])
                ->all()),
        ]);
    }

    /**
     * Show the form for editing the specified asset.
     */
    public function edit(Asset $asset): Response
    {
        return Inertia::render('assets/Edit', [
            'asset' => [
                ...$asset->only([
                    'id', 'category_id', 'name', 'serial_number', 'model_number',
                    'brand', 'current_location', 'notes',
                ]),
                'purchase_date' => $asset->purchase_date?->toDateString(),
                'cost' => $asset->cost,
                'status' => $asset->status->value,
            ],
            'categories' => $this->categoryOptions(),
            'statuses' => AssetStatus::options(),
        ]);
    }

    /**
     * Update the specified asset and log the change.
     */
    public function update(UpdateAssetRequest $request, Asset $asset): RedirectResponse
    {
        $original = $asset->status;

        $asset->update($request->validated());

        $statusChanged = $original !== $asset->status;

        $asset->logs()->create([
            'user_id' => $request->user()->id,
            'action' => $statusChanged ? AssetLogAction::StatusChange : AssetLogAction::Updated,
            'description' => $statusChanged
                ? "Status changed from \"{$original->label()}\" to \"{$asset->status->label()}\"."
                : 'Asset details were updated.',
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Asset updated.')]);

        return to_route('assets.show', $asset);
    }

    /**
     * Remove the specified asset from the inventory.
     */
    public function destroy(Asset $asset): RedirectResponse
    {
        $asset->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Asset deleted.')]);

        return to_route('assets.index');
    }

    /**
     * Get the category options used by the asset forms and filters.
     *
     * @return array<int, array{value: int, label: string}>
     */
    protected function categoryOptions(): array
    {
        return Category::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (Category $category): array => [
                'value' => $category->id,
                'label' => $category->name,
            ])
            ->all();
    }
}
