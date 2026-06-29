<?php

namespace App\Providers;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        // Register role bypass for admin
        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }
        });

        // Define specific capability gates
        $abilities = [
            'manage_users',
            'manage_categories',
            'manage_assets',
            'approve_borrowing',
            'process_returns',
            'view_reports',
            'view_audit_logs',
            'manage_maintenance',
            'manage_disposed',
            'borrow_assets',
        ];

        foreach ($abilities as $ability) {
            Gate::define($ability, function (User $user) use ($ability): bool {
                return $user->hasPermission($ability);
            });
        }
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
