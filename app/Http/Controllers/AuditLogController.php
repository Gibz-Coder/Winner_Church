<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class AuditLogController extends Controller
{
    /**
     * Display a listing of the audit logs.
     */
    public function index(): Response
    {
        Gate::authorize('view_audit_logs');

        $logs = AuditLog::query()
            ->with('user:id,name')
            ->latest()
            ->paginate(30);

        return Inertia::render('audit_logs/Index', [
            'logs' => $logs,
        ]);
    }
}
