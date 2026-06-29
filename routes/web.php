<?php

use App\Enums\AssetStatus;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\BorrowRequestController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Models\Asset;
use App\Models\AssetLog;
use App\Models\BorrowRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        $total = Asset::count();
        $available = Asset::where('status', AssetStatus::Available)->count();
        $borrowed = Asset::where('status', AssetStatus::Borrowed)->count();
        $maintenance = Asset::where('status', AssetStatus::UnderMaintenance)->count();
        $disposed = Asset::where('status', AssetStatus::Disposed)->count();
        $lost = Asset::where('status', AssetStatus::Lost)->count();
        $reserved = Asset::where('status', AssetStatus::Reserved)->count();

        $pendingRequests = BorrowRequest::where('status', 'pending')->count();

        $recentActivity = AssetLog::with(['asset', 'user'])
            ->latest()
            ->take(10)
            ->get()
            ->map(fn ($log) => [
                'id' => $log->id,
                'asset_name' => $log->asset?->name,
                'user_name' => $log->user?->name ?? 'System',
                'action' => $log->action->value,
                'action_label' => $log->action->label(),
                'description' => $log->description,
                'time_ago' => $log->created_at?->diffForHumans() ?? 'Just now',
            ]);

        $categories = Category::withCount('assets')->get()->map(fn ($c) => [
            'name' => $c->name,
            'count' => $c->assets_count,
        ]);

        return Inertia::render('Dashboard', [
            'stats' => [
                'total' => $total,
                'available' => $available,
                'borrowed' => $borrowed,
                'maintenance' => $maintenance,
                'disposed' => $disposed,
                'lost' => $lost,
                'reserved' => $reserved,
                'pending_requests' => $pendingRequests,
            ],
            'recentActivity' => $recentActivity,
            'categories' => $categories,
        ]);
    })->name('dashboard');

    Route::resource('assets', AssetController::class);
    Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);

    // Borrowing Workflow
    Route::get('borrow-requests', [BorrowRequestController::class, 'index'])->name('borrow-requests.index');
    Route::post('borrow-requests', [BorrowRequestController::class, 'store'])->name('borrow-requests.store');
    Route::post('borrow-requests/{borrowRequest}/approve', [BorrowRequestController::class, 'approve'])->name('borrow-requests.approve');
    Route::post('borrow-requests/{borrowRequest}/reject', [BorrowRequestController::class, 'reject'])->name('borrow-requests.reject');
    Route::post('borrow-requests/{borrowRequest}/return', [BorrowRequestController::class, 'return'])->name('borrow-requests.return');

    // Maintenance
    Route::resource('maintenance', MaintenanceController::class)->except(['create', 'show', 'edit']);

    // Users
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');

    // Audit Logs
    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
});

require __DIR__.'/settings.php';
