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
        Gate::define('admin', function (User $user) {
        $employee = \DB::table('employees')->where('user_id', $user->id)->first();
        return ($user->role === 'admin') || ($employee && $employee->role === 'admin');
    });

    // 2. Cashier Gate
    Gate::define('cashier', function (User $user) {
        $employee = \DB::table('employees')->where('user_id', $user->id)->first();
        return ($user->role === 'cashier') || ($employee && $employee->role === 'cashier');
    });

    // 3. REGISTRAR GATE
    Gate::define('registrar', function (User $user) {
        // This checks if the user exists in the employees table with the 'registrar' role
        $employee = \DB::table('employees')->where('user_id', $user->id)->first();
        return ($user->role === 'registrar') || ($employee && $employee->role === 'registrar');
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
