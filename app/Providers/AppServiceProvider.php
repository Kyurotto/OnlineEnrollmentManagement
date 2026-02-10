<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Payment;

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
        // 1. Admin Gate
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });

        // 2. Cashier Gate
        Gate::define('cashier', function (User $user) {
            return $user->role === 'cashier';
        });

        // 3. REGISTRAR GATE (New)
        Gate::define('registrar', function (User $user) {
            return $user->role === 'registrar';
        });

        // Notification Logic (Shared between Admin and Registrar)
        View::composer(['admin.*', 'registrar.*'], function ($view) {
            try {
                $pendingCount = Enrollment::where('status', 'Pending')->count();
            } catch (\Exception $e) {
                $pendingCount = 0;
            }
            $view->with('pendingCount', $pendingCount);
        });

        // 4. CASHIER NOTIFICATIONS
        View::composer(['cashier.*'], function ($view) {
            try {
                $pendingPaymentsCount = Payment::where('status', 'Pending')->count();
            } catch (\Exception $e) {
                $pendingPaymentsCount = 0;
            }
            $view->with('pendingPaymentsCount', $pendingPaymentsCount);
        });
    }
}
