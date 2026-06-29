<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display a listing of users and their roles.
     */
    public function index(): Response
    {
        Gate::authorize('manage_users');

        $users = User::query()
            ->with('roles')
            ->orderBy('name')
            ->paginate(15)
            ->through(fn (User $u): array => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'roles' => $u->roles->map(fn (Role $r): array => [
                    'id' => $r->id,
                    'name' => $r->name,
                    'display_name' => $r->display_name,
                ])->all(),
            ]);

        $roles = Role::query()
            ->get(['id', 'name', 'display_name'])
            ->map(fn (Role $r): array => [
                'value' => $r->id,
                'label' => $r->display_name ?: $r->name,
            ])
            ->all();

        return Inertia::render('users/Index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    /**
     * Update user roles.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('manage_users');

        $validated = $request->validate([
            'role_ids' => ['required', 'array'],
            'role_ids.*' => ['exists:roles,id'],
        ]);

        $user->roles()->sync($validated['role_ids']);

        // Log action in audit logs
        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'Updated User Roles',
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'new_values' => ['roles' => $validated['role_ids']],
            'ip_address' => $request->ip(),
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('User roles updated.')]);

        return to_route('users.index');
    }
}
