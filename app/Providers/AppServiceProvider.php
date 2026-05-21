<?php

namespace App\Providers;

use App\Notifications\Channels\WhatsAppChannel;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Reservasi;
use App\Models\User;
use App\Observers\ReservasiObserver;
use Illuminate\Support\Facades\Blade;

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
        Reservasi::observe(ReservasiObserver::class);

        Blade::anonymousComponentPath(resource_path('views/frontend/components'), 'frontend');

        // Authorization Gates for Role-based Access Control

        // General role checks
        Gate::define('manager-access', function (User $user) {
            return $user->role === 'manager';
        });

        Gate::define('admin-access', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('owner-access', function (User $user) {
            return $user->role === 'owner';
        });

        // Composite gates
        Gate::define('admin-manager-access', function (User $user) {
            return in_array($user->role, ['admin', 'manager']);
        });

        Gate::define('backend-access', function (User $user) {
            return in_array($user->role, ['admin', 'manager']);
        });

        // CRUD Permission Gates
        Gate::define('manage-menu', function (User $user) {
            return $user->role === 'manager';
        });

        Gate::define('manage-kategori', function (User $user) {
            return $user->role === 'manager';
        });

        Gate::define('manage-special-menu', function (User $user) {
            return $user->role === 'manager';
        });

        Gate::define('manage-testimoni', function (User $user) {
            return $user->role === 'manager';
        });

        Gate::define('manage-user', function (User $user) {
            return $user->role === 'manager';
        });

        Gate::define('manage-transactions', function (User $user) {
            return in_array($user->role, ['admin', 'manager']);
        });

        Gate::define('manage-reservations', function (User $user) {
            return in_array($user->role, ['admin', 'manager']);
        });

        Gate::define('view-analytics', function (User $user) {
            return $user->role === 'owner';
        });

        // Read-only analytics for owner
        Gate::define('view-owner-dashboard', function (User $user) {
            return $user->role === 'owner';
        });

        // Prevent owner from doing CRUD
        Gate::define('crud-access', function (User $user) {
            return in_array($user->role, ['admin', 'manager']);
        });
    }
}
