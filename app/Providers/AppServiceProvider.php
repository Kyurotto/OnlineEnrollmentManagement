<?php

namespace App\Providers;

use App\Models\Employee;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\NewEnrollmentSubmitted;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
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
        // Prevent 30 seconds timeout error globally
        set_time_limit(0);
        ini_set('max_execution_time', 0);

        // 0. SUPER ADMIN BYPASS
        Gate::before(function (User $user, string $ability) {
            // FIX 3: Prevent Super Admin from bypassing strictly role-scoped capabilities
            // This prevents crashes where a route explicitly requires a cashier/registrar context.
            if (in_array($ability, ['cashier', 'registrar', 'student', 'teacher'])) {
                return null;
            }

            // FIX 1: Secure Email Fallback (Prevents Privilege Escalation)
            $employee = $user->employee;
            if (! $employee) {
                $employee = Employee::where('email', $user->email)->first();
                // Ensure this employee record isn't already claimed by another user ID
                if ($employee && $employee->user_id !== null && $employee->user_id !== $user->id) {
                    $employee = null;
                }
            }

            if ($employee && $employee->role === 'admin') {
                return true;
            }
        });

        Gate::define('admin', function (User $user) {
            $employee = $user->employee;
            if (! $employee) {
                $employee = Employee::where('email', $user->email)->first();
                if ($employee && $employee->user_id !== null && $employee->user_id !== $user->id) {
                    $employee = null;
                }
            }

            return $employee && $employee->role === 'admin';
        });

        Gate::define('cashier', function (User $user) {
            $employee = $user->employee;
            if (! $employee) {
                $employee = Employee::where('email', $user->email)->first();
                if ($employee && $employee->user_id !== null && $employee->user_id !== $user->id) {
                    $employee = null;
                }
            }

            return $employee && $employee->role === 'cashier';
        });

        Gate::define('registrar', function (User $user) {
            $employee = $user->employee;
            if (! $employee) {
                $employee = Employee::where('email', $user->email)->first();
                if ($employee && $employee->user_id !== null && $employee->user_id !== $user->id) {
                    $employee = null;
                }
            }

            return $employee && $employee->role === 'registrar';
        });

        Gate::define('teacher', function (User $user) {
            $employee = $user->employee;
            if (! $employee) {
                $employee = Employee::where('email', $user->email)->first();
                if ($employee && $employee->user_id !== null && $employee->user_id !== $user->id) {
                    $employee = null;
                }
            }

            return $employee && $employee->role === 'teacher';
        });

        Gate::define('student', function (User $user) {
            return $user->role === 'student';
        });

        // Admin Navbar Data
        // Admin Layout Data
        View::composer('components.layouts.admin', function ($view) {
            $user = auth()->user();
            try {
                // FIX 2: Correctly isolate "New Enrollee" notifications from all other notifications
                $unreadNotifCount = $user ? $user->unreadNotifications()->where('type', NewEnrollmentSubmitted::class)->count() : 0;
                $dbNotifications = $user ? $user->unreadNotifications()->latest()->take(10)->get() : collect();
            } catch (\Exception $e) {
                $unreadNotifCount = 0;
                $dbNotifications = collect();
            }

            $view->with([
                'newEnrolleesCount' => $unreadNotifCount,
                'dbNotifications' => $dbNotifications,
                'currentRoute' => request()->route() ? request()->route()->getName() : null,
            ]);
        });

        // Registrar Layout Data
        View::composer('components.layouts.registrar', function ($view) {
            $user = auth()->user();
            try {
                // FIX 2: Correctly isolate "New Enrollee" notifications from all other notifications
                $unreadNotifCount = $user ? $user->unreadNotifications()->where('type', NewEnrollmentSubmitted::class)->count() : 0;
                $dbNotifications = $user ? $user->unreadNotifications()->latest()->take(10)->get() : collect();
            } catch (\Exception $e) {
                $unreadNotifCount = 0;
                $dbNotifications = collect();
            }

            $view->with([
                'newEnrolleesCount' => $unreadNotifCount,
                'dbNotifications' => $dbNotifications,
                'currentRoute' => request()->route() ? request()->route()->getName() : null,
            ]);
        });

        // Current Notification Logic (Existing)
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

        // 5. PASSWORD COMPLEXITY DEFAULTS
        Password::defaults(function () {
            return Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols();
        });
    }
}
