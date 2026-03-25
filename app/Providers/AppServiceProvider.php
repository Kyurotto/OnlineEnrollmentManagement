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
            // Find employee by user_id or fallback to email (for seeder compatibility)
            $employee = $user->employee ?: Employee::where('email', '=', $user->email, 'and')->first();
            if ($employee && $employee->role === 'admin') {
                return true;
            }
        });

        Gate::define('admin', function (User $user) {
            $employee = $user->employee ?: Employee::where('email', '=', $user->email, 'and')->first();
            return $employee && $employee->role === 'admin';
        });

        Gate::define('cashier', function (User $user) {
            $employee = $user->employee ?: Employee::where('email', '=', $user->email, 'and')->first();
            return $employee && $employee->role === 'cashier';
        });

        Gate::define('registrar', function (User $user) {
            $employee = $user->employee ?: Employee::where('email', '=', $user->email, 'and')->first();
            return $employee && $employee->role === 'registrar';
        });



        // Admin Navbar Data
        // Admin Layout Data
        View::composer('components.layouts.admin', function ($view) {
            $user = auth()->user();
            try {
                $unreadNotifCount = $user ? $user->unreadNotifications()->count() : 0;
                $dbNotifications = $user ? $user->unreadNotifications()->latest()->take(10)->get() : collect();
            } catch (\Exception $e) {
                $unreadNotifCount = 0;
                $dbNotifications = collect();
            }

            $view->with([
                'newEnrolleesCount' => $unreadNotifCount,
                'dbNotifications'   => $dbNotifications,
                'currentRoute'      => request()->route() ? request()->route()->getName() : null,
            ]);
        });

        // Registrar Layout Data
        View::composer('components.layouts.registrar', function ($view) {
            $user = auth()->user();
            try {
                $unreadNotifCount = $user ? $user->unreadNotifications()->count() : 0;
                $dbNotifications = $user ? $user->unreadNotifications()->latest()->take(10)->get() : collect();
            } catch (\Exception $e) {
                $unreadNotifCount = 0;
                $dbNotifications = collect();
            }

            $view->with([
                'newEnrolleesCount' => $unreadNotifCount,
                'dbNotifications'   => $dbNotifications,
                'currentRoute'      => request()->route() ? request()->route()->getName() : null,
            ]);
        });

        // Current Notification Logic (Existing)
        View::composer(['admin.*', 'registrar.*'], function ($view) {
            try {
                $pendingCount = Enrollment::where('status', '=', 'Pending', 'and')->count();
            } catch (\Exception $e) {
                $pendingCount = 0;
            }
            $view->with('pendingCount', $pendingCount);
        });

        // 4. CASHIER NOTIFICATIONS
        View::composer(['cashier.*'], function ($view) {
            try {
                $pendingPaymentsCount = Payment::where('status', '=', 'Pending')->count();
            } catch (\Exception $e) {
                $pendingPaymentsCount = 0;
            }
            $view->with('pendingPaymentsCount', $pendingPaymentsCount);
        });
    }
}
