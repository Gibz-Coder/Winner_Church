<?php

namespace App\Http\Controllers;

use App\Enums\AssetLogAction;
use App\Enums\AssetStatus;
use App\Models\Asset;
use App\Models\BorrowRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class BorrowRequestController extends Controller
{
    /**
     * Display a listing of borrowing requests.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        $query = BorrowRequest::query()->with(['asset', 'user', 'authorizedBy']);

        if (! $user->hasRole('admin')) {
            $query->where('user_id', $user->id);
        }

        $requests = $query->latest()
            ->paginate(15)
            ->withQueryString();

        $assets = Asset::where('status', AssetStatus::Available)
            ->orderBy('name')
            ->get(['id', 'name', 'serial_number'])
            ->map(fn (Asset $a): array => [
                'value' => $a->id,
                'label' => "{$a->name} ({$a->serial_number})",
            ])
            ->all();

        return Inertia::render('borrow_requests/Index', [
            'requests' => $requests,
            'assets' => $assets,
        ]);
    }

    /**
     * Store a newly created borrow request.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'borrow_date' => ['required', 'date', 'after_or_equal:today'],
            'expected_return_date' => ['required', 'date', 'after:borrow_date'],
            'borrow_condition' => ['required', 'string'],
        ]);

        $asset = Asset::findOrFail($validated['asset_id']);

        if ($asset->status !== AssetStatus::Available) {
            return back()->withErrors(['asset_id' => __('This asset is currently not available.')]);
        }

        BorrowRequest::create([
            ...$validated,
            'user_id' => $request->user()->id,
            'status' => 'pending',
        ]);

        // Reserve the asset while request is pending
        $asset->update(['status' => AssetStatus::Reserved]);

        $asset->logs()->create([
            'user_id' => $request->user()->id,
            'action' => AssetLogAction::StatusChange,
            'description' => "Asset reserved. Borrow request submitted by {$request->user()->name}.",
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Borrow request submitted.')]);

        return to_route('borrow-requests.index');
    }

    /**
     * Approve a borrowing request (Admin only).
     */
    public function approve(Request $request, BorrowRequest $borrowRequest): RedirectResponse
    {
        Gate::authorize('approve_borrowing');

        $asset = $borrowRequest->asset;

        $borrowRequest->update([
            'status' => 'approved',
            'authorized_by' => $request->user()->id,
        ]);

        $asset->update(['status' => AssetStatus::Borrowed]);

        $asset->logs()->create([
            'user_id' => $request->user()->id,
            'action' => AssetLogAction::CheckedOut,
            'description' => "Borrow request approved by {$request->user()->name}. Checked out to {$borrowRequest->user->name}.",
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Borrow request approved.')]);

        return to_route('borrow-requests.index');
    }

    /**
     * Reject a borrowing request (Admin only).
     */
    public function reject(Request $request, BorrowRequest $borrowRequest): RedirectResponse
    {
        Gate::authorize('approve_borrowing');

        $asset = $borrowRequest->asset;

        $borrowRequest->update([
            'status' => 'rejected',
            'authorized_by' => $request->user()->id,
            'remarks' => $request->string('remarks')->toString() ?: 'Rejected by administrator',
        ]);

        // Revert asset status back to Available
        $asset->update(['status' => AssetStatus::Available]);

        $asset->logs()->create([
            'user_id' => $request->user()->id,
            'action' => AssetLogAction::StatusChange,
            'description' => "Borrow request rejected by {$request->user()->name}.",
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Borrow request rejected.')]);

        return to_route('borrow-requests.index');
    }

    /**
     * Process return of an asset (Admin only).
     */
    public function return(Request $request, BorrowRequest $borrowRequest): RedirectResponse
    {
        Gate::authorize('process_returns');

        $validated = $request->validate([
            'return_condition' => ['required', 'string'],
            'remarks' => ['nullable', 'string'],
        ]);

        $asset = $borrowRequest->asset;

        $borrowRequest->update([
            'status' => 'returned',
            'return_date' => now(),
            'return_condition' => $validated['return_condition'],
            'remarks' => $validated['remarks'],
            'authorized_by' => $request->user()->id,
        ]);

        $asset->update(['status' => AssetStatus::Available]);

        $asset->logs()->create([
            'user_id' => $request->user()->id,
            'action' => AssetLogAction::CheckedIn,
            'description' => "Asset returned in \"{$validated['return_condition']}\" condition. Processed by {$request->user()->name}.",
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Asset return processed successfully.')]);

        return to_route('borrow-requests.index');
    }
}
