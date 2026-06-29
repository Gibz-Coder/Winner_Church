<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\BorrowRequest;
use App\Models\Category;
use App\Models\MaintenanceLog;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Display report dashboard.
     */
    public function index(): Response
    {
        Gate::authorize('view_reports');

        // 1. Category Summary
        $categorySummary = Category::query()
            ->withCount('assets')
            ->get()
            ->map(fn (Category $c): array => [
                'name' => $c->name,
                'count' => $c->assets_count,
                'total_cost' => Asset::where('category_id', $c->id)->sum('cost'),
            ])
            ->all();

        // 2. Asset Utilization (borrow count per asset)
        $utilization = Asset::query()
            ->withCount('borrowRequests')
            ->orderByDesc('borrow_requests_count')
            ->take(10)
            ->get()
            ->map(fn (Asset $a): array => [
                'name' => $a->name,
                'serial_number' => $a->serial_number,
                'borrow_count' => $a->borrow_requests_count,
            ])
            ->all();

        // 3. Maintenance Cost Summary
        $maintenanceSummary = [
            'total_cost' => MaintenanceLog::where('status', 'completed')->sum('cost'),
            'count' => MaintenanceLog::count(),
            'pending_count' => MaintenanceLog::whereIn('status', ['scheduled', 'in_progress'])->count(),
        ];

        return Inertia::render('reports/Index', [
            'categorySummary' => $categorySummary,
            'utilization' => $utilization,
            'maintenanceSummary' => $maintenanceSummary,
        ]);
    }

    /**
     * Export reports to CSV.
     */
    public function export(string $type): StreamedResponse
    {
        Gate::authorize('view_reports');

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=winner_church_report_{$type}_".date('Ymd').'.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($type) {
            $file = fopen('php://output', 'w');

            if ($type === 'inventory') {
                fputcsv($file, ['ID', 'Asset Name', 'Serial Number', 'Category', 'Status', 'Current Location', 'Assigned Ministry', 'Cost', 'Purchase Date']);
                $assets = Asset::with('category')->get();
                foreach ($assets as $asset) {
                    fputcsv($file, [
                        $asset->id,
                        $asset->name,
                        $asset->serial_number,
                        $asset->category?->name,
                        $asset->status->label(),
                        $asset->current_location,
                        $asset->assigned_ministry,
                        $asset->cost,
                        $asset->purchase_date?->toDateString(),
                    ]);
                }
            } elseif ($type === 'borrowing') {
                fputcsv($file, ['Request ID', 'Asset Name', 'Member Name', 'Status', 'Borrow Date', 'Expected Return', 'Return Date', 'Borrow Condition', 'Return Condition', 'Authorized By']);
                $requests = BorrowRequest::with(['asset', 'user', 'authorizedBy'])->get();
                foreach ($requests as $r) {
                    fputcsv($file, [
                        $r->id,
                        $r->asset?->name,
                        $r->user?->name,
                        $r->status,
                        $r->borrow_date?->toDateTimeString(),
                        $r->expected_return_date?->toDateTimeString(),
                        $r->return_date?->toDateTimeString(),
                        $r->borrow_condition,
                        $r->return_condition,
                        $r->authorizedBy?->name,
                    ]);
                }
            } elseif ($type === 'maintenance') {
                fputcsv($file, ['Log ID', 'Asset Name', 'Type', 'Description', 'Cost', 'Start Date', 'End Date', 'Status', 'Performed By']);
                $logs = MaintenanceLog::with('asset')->get();
                foreach ($logs as $log) {
                    fputcsv($file, [
                        $log->id,
                        $log->asset?->name,
                        ucfirst($log->maintenance_type),
                        $log->description,
                        $log->cost,
                        $log->start_date->toDateString(),
                        $log->end_date?->toDateString(),
                        ucfirst($log->status),
                        $log->performed_by,
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
