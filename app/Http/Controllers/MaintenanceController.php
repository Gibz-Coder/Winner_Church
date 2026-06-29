<?php

namespace App\Http\Controllers;

use App\Enums\AssetLogAction;
use App\Enums\AssetStatus;
use App\Models\Asset;
use App\Models\MaintenanceLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of maintenance logs.
     */
    public function index(): Response
    {
        Gate::authorize('manage_maintenance');

        $logs = MaintenanceLog::query()
            ->with('asset:id,name,serial_number')
            ->latest()
            ->paginate(15);

        $assets = Asset::query()
            ->whereIn('status', [AssetStatus::Available, AssetStatus::UnderMaintenance])
            ->orderBy('name')
            ->get(['id', 'name', 'serial_number', 'status'])
            ->map(fn (Asset $a): array => [
                'value' => $a->id,
                'label' => "{$a->name} ({$a->serial_number})",
                'status' => $a->status->value,
            ])
            ->all();

        return Inertia::render('maintenance/Index', [
            'logs' => $logs,
            'assets' => $assets,
        ]);
    }

    /**
     * Store a newly created maintenance log.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage_maintenance');

        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'maintenance_type' => ['required', 'string'],
            'description' => ['required', 'string'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['required', 'date'],
            'performed_by' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:scheduled,in_progress'],
        ]);

        $maintenance = MaintenanceLog::create($validated);

        $asset = Asset::findOrFail($validated['asset_id']);

        if ($validated['status'] === 'in_progress') {
            $asset->update(['status' => AssetStatus::UnderMaintenance]);
        }

        $asset->logs()->create([
            'user_id' => $request->user()->id,
            'action' => AssetLogAction::StatusChange,
            'description' => "Asset scheduled for maintenance. Type: {$validated['maintenance_type']}. Status: {$validated['status']}.",
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Maintenance log created.')]);

        return to_route('maintenance.index');
    }

    /**
     * Update the maintenance log status (complete/cancel).
     */
    public function update(Request $request, MaintenanceLog $maintenance): RedirectResponse
    {
        Gate::authorize('manage_maintenance');

        $validated = $request->validate([
            'status' => ['required', 'in:scheduled,in_progress,completed,cancelled'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'description' => ['required', 'string'],
            'performed_by' => ['nullable', 'string', 'max:255'],
        ]);

        $maintenance->update($validated);

        $asset = $maintenance->asset;

        if ($validated['status'] === 'completed') {
            $asset->update(['status' => AssetStatus::Available]);

            $asset->logs()->create([
                'user_id' => $request->user()->id,
                'action' => AssetLogAction::StatusChange,
                'description' => "Maintenance completed. Asset is now available. Cost: {$validated['cost']}.",
            ]);
        } elseif ($validated['status'] === 'cancelled') {
            $asset->update(['status' => AssetStatus::Available]);

            $asset->logs()->create([
                'user_id' => $request->user()->id,
                'action' => AssetLogAction::StatusChange,
                'description' => 'Maintenance cancelled. Asset is now available.',
            ]);
        } elseif ($validated['status'] === 'in_progress') {
            $asset->update(['status' => AssetStatus::UnderMaintenance]);
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Maintenance log updated.')]);

        return to_route('maintenance.index');
    }

    /**
     * Remove the specified maintenance log.
     */
    public function destroy(MaintenanceLog $maintenance): RedirectResponse
    {
        Gate::authorize('manage_maintenance');

        $maintenance->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Maintenance log deleted.')]);

        return to_route('maintenance.index');
    }
}
