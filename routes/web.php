<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
// Admin Livewire Components
use App\Livewire\Admin\DashboardManager;
use App\Livewire\Admin\CourseManager;
use App\Livewire\Admin\PaymentManager;
use App\Livewire\Admin\StudentManager;
use App\Livewire\Admin\ApplicationManager;
use App\Livewire\Admin\AdminNavbar;

// Registrar Livewire Components
use App\Livewire\Registrar\RegistrarDashboardManager;
use App\Livewire\Registrar\RegistrarStudentManager;
use App\Livewire\Registrar\RegistrarApplicationManager;
use App\Livewire\Registrar\RegistrarAcademicYearManager;
use App\Livewire\Registrar\RegistrarSemesterManager;
use App\Livewire\Registrar\RegistrarProgramManager;
use App\Livewire\Registrar\RegistrarSectionManager;
use App\Livewire\Registrar\RegistrarNavbar;

// Cashier Livewire Components
use App\Livewire\Cashier\CashierDashboardManager;
use App\Livewire\Cashier\CashierPaymentManager;

// Student Livewire Components
use App\Livewire\Student\StudentDashboardManager;
use App\Livewire\Student\StudentEnrollmentManager;
use App\Livewire\Student\StudentPaymentManager;
use App\Livewire\Student\StudentProfileManager;

// Admin Staff Manager
use App\Livewire\StaffManager;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware([
    'auth',
])->group(function () {
    Route::get('/dashboard', function () {
    $user = Auth::user();
    if (!$user) return redirect()->route('login');

    // Look for the user in the employees table
    $employee = \DB::table('employees')->where('user_id', $user->id)->first();

    // Determine the role from either table
    $role = $employee ? $employee->role : $user->role;



    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($role === 'registrar') {
        return redirect()->route('registrar.dashboard');
    } elseif ($role === 'cashier') {
        return redirect()->route('cashier.dashboard');
    }

    return redirect()->route('student.dashboard');
})->name('dashboard');
});


/*
|--------------------------------------------------------------------------
| REGISTRAR ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'can:registrar'])->prefix('registrar')->name('registrar.')->group(function () {
    Route::get('/dashboard', RegistrarDashboardManager::class)->name('dashboard');
    Route::get('/students', RegistrarStudentManager::class)->name('students.index');
    Route::get('/applications', RegistrarApplicationManager::class)->name('applications.index');
    Route::get('/academic-years', RegistrarAcademicYearManager::class)->name('academic_years.index');
    Route::get('/semesters', RegistrarSemesterManager::class)->name('semesters.index');
    Route::get('/programs', RegistrarProgramManager::class)->name('programs.index');
    Route::get('/sections', RegistrarSectionManager::class)->name('sections.index');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', DashboardManager::class)->name('dashboard');
    Route::get('/courses', CourseManager::class)->name('courses.index');
    Route::get('/payments', PaymentManager::class)->name('payments.index');
    Route::get('/students', StudentManager::class)->name('students.index');
    Route::get('/applications', ApplicationManager::class)->name('applications.index');


    // API/Export routes
    Route::get('/payments/export', [\App\Http\Controllers\Admin\PaymentController::class, 'export'])->name('payments.export');
    Route::get('/api/pending-counts', [\App\Http\Controllers\Admin\DashboardController::class, 'getPendingCounts'])->name('api.pending-counts');
    Route::post('/api/notifications/mark-read', [\App\Http\Controllers\Admin\DashboardController::class, 'markNotificationRead'])->name('api.notifications.mark-read');
});

/*
|--------------------------------------------------------------------------
| CASHIER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'can:cashier'])->prefix('cashier')->name('cashier.')->group(function () {
    Route::get('/dashboard', CashierDashboardManager::class)->name('dashboard');
    Route::get('/payments', CashierPaymentManager::class)->name('payments.index');
    Route::post('/payments', [\App\Http\Controllers\Cashier\CashierPaymentController::class, 'store'])->name('payments.store');
    Route::patch('/payments/{id}', [\App\Http\Controllers\Cashier\CashierPaymentController::class, 'update'])->name('payments.update');
    Route::patch('/payments/{id}/status', [\App\Http\Controllers\Cashier\CashierPaymentController::class, 'updateStatus'])->name('payments.updateStatus');
});

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', StudentDashboardManager::class)->name('dashboard');
    Route::get('/enrollment/create', StudentEnrollmentManager::class)->name('enrollment.create');
    Route::get('/payments', StudentPaymentManager::class)->name('payment');
    Route::get('/profile', StudentProfileManager::class)->name('profile');
});

/*
|--------------------------------------------------------------------------
| PROFILE & LOGOUT
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

require __DIR__.'/auth.php';
