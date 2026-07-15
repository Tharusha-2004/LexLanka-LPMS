<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        /*
        |----------------------------------------------------------------------
        | Authorization Gates — LexLanka RBAC
        |----------------------------------------------------------------------
        |
        | These gates control access to sensitive sections of the application.
        | Use them in Blade with @can('gate-name') or in controllers with
        | Gate::authorize('gate-name') / $this->authorize('gate-name').
        |
        */

        // Only Partners can view financial/billing data
        Gate::define('view-financials', function ($user): bool {
            return $user->role === 'partner';
        });

        // Only Partners can create, edit, or suspend user accounts
        Gate::define('manage-users', function ($user): bool {
            return $user->role === 'partner';
        });
    }
}
