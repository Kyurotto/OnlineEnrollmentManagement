<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
        // 0. SUPER ADMIN BYPASS
        Gate::before(function (User $user, string $ability) {
            if ($user->role === 'admin') {
                return true;
            }
            // Check employee record as well
            $employee = $user->employee;
            if ($employee && $employee->role === 'admin') {
                return true;
            }
        });

        // 1. ADMIN GATE
        Gate::define('admin', function (User $user) {
            if ($user->role === 'admin') return true;
            $employee = $user->employee;
            return $employee && $employee->role === 'admin';
        });

        // 2. CASHIER GATE
        Gate::define('cashier', function (User $user) {
            if ($user->role === 'cashier') return true;
            $employee = $user->employee;
            return $employee && $employee->role === 'cashier';
        });

        // 3. REGISTRAR GATE
        Gate::define('registrar', function (User $user) {
            if ($user->role === 'registrar') return true;
            $employee = $user->employee;
            return $employee && $employee->role === 'registrar';
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
