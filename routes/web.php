<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// --- CONTROLLER IMPORTS ---

// General
use App\Http\Controllers\ProfileController;

// Admin Full-Page Livewire Components
use App\Livewire\DashboardManager;
use App\Livewire\CourseManager;
use App\Livewire\PaymentManager;
use App\Livewire\StudentManager;
use App\Livewire\ApplicationManager;

// Registrar Full-Page Livewire Components
use App\Livewire\RegistrarDashboardManager;
use App\Livewire\RegistrarStudentManager;
use App\Livewire\RegistrarApplicationManager;
use App\Livewire\RegistrarProgramManager;
use App\Livewire\RegistrarSemesterManager;
use App\Livewire\RegistrarAcademicYearManager;
use App\Livewire\RegistrarSectionManager;

// Cashier Livewire Components
use App\Livewire\CashierDashboardManager;
use App\Livewire\CashierPaymentManager;

// Old Cashier MVC Controllers
use App\Http\Controllers\Cashier\DashboardController as CashierDashboard;
use App\Http\Controllers\Cashier\CashierPaymentController;

// Student Full-Page Livewire Components
use App\Livewire\StudentDashboardManager;
use App\Livewire\StudentEnrollmentManager;
use App\Livewire\StudentPaymentManager;
use App\Livewire\StudentProfileManager;

// Old Student MVC Controllers
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\PaymentController as StudentPayment;
use App\Http\Controllers\Student\ProfileController as StudentProfile;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| TRAFFIC COP (Main Redirect)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    elseif ($user->role === 'cashier') {
        return redirect()->route('cashier.dashboard');
    }
    elseif ($user->role === 'registrar') {
        return redirect()->route('registrar.dashboard');
    }

    return redirect()->route('student.dashboard');

})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| REGISTRAR ROUTES
|--------------------------------------------------------------------------
*/
// Old MVC Routes
Route::middleware(['auth', 'can:registrar'])->prefix('registrar')->name('registrar.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Registrar\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('students', \App\Http\Controllers\Registrar\StudentController::class)->except(['create', 'store', 'destroy']);
    Route::get('/applications', [\App\Http\Controllers\Registrar\ApplicationController::class, 'index'])->name('applications.index');
    Route::get('students/{student}/applications', [\App\Http\Controllers\Registrar\ApplicationController::class, 'index'])->name('students.applications.index');
    Route::get('applications/{application}', [\App\Http\Controllers\Registrar\ApplicationController::class, 'show'])->name('applications.show');
    Route::put('applications/{application}', [\App\Http\Controllers\Registrar\ApplicationController::class, 'update'])->name('applications.update');
    Route::delete('applications/{application}', [\App\Http\Controllers\Registrar\ApplicationController::class, 'destroy'])->name('applications.destroy');

    Route::resource('programs', \App\Http\Controllers\Registrar\ProgramController::class)->except(['show']);
    Route::resource('academic_years', \App\Http\Controllers\Registrar\AcademicYearController::class)->except(['show']);
    Route::put('academic_years/{academic_year}/set-active', [\App\Http\Controllers\Registrar\AcademicYearController::class, 'setActive'])->name('academic_years.set_active');

    Route::resource('semesters', \App\Http\Controllers\Registrar\SemesterController::class)->except(['show']);
    Route::put('semesters/{semester}/set-active', [\App\Http\Controllers\Registrar\SemesterController::class, 'setActive'])->name('semesters.set_active');

    Route::resource('sections', \App\Http\Controllers\Registrar\SectionController::class)->except(['show']);
});

// Livewire Registrar Routes
Route::middleware(['auth', 'can:registrar'])->prefix('livewire-registrar')->name('livewire.registrar.')->group(function () {
    Route::get('/dashboard', RegistrarDashboardManager::class)->name('dashboard');
    Route::get('/students', RegistrarStudentManager::class)->name('students.index');
    Route::get('/applications', RegistrarApplicationManager::class)->name('applications.index');
    Route::get('/programs', RegistrarProgramManager::class)->name('programs.index');
    Route::get('/academic-years', RegistrarAcademicYearManager::class)->name('academic-years.index');
    Route::get('/semesters', RegistrarSemesterManager::class)->name('semesters.index');
    Route::get('/sections', RegistrarSectionManager::class)->name('sections.index');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/pending-counts', [\App\Http\Controllers\Admin\DashboardController::class, 'getPendingCounts'])->name('api.pending-counts');
    Route::post('/api/notifications/mark-read', [\App\Http\Controllers\Admin\DashboardController::class, 'markNotificationRead'])->name('api.notifications.mark-read');

    // Applications Management
    Route::get('/applications', [\App\Http\Controllers\Admin\ApplicationController::class, 'index'])->name('applications.index');
    Route::put('/applications/{id}/approve', [\App\Http\Controllers\Admin\ApplicationController::class, 'approve'])->name('applications.approve');
    Route::put('/applications/{id}/reject', [\App\Http\Controllers\Admin\ApplicationController::class, 'reject'])->name('applications.reject');

    // Payments Management
    Route::get('/payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/export', [\App\Http\Controllers\Admin\PaymentController::class, 'export'])->name('payments.export');

    // Courses Management
    Route::resource('courses', \App\Http\Controllers\Admin\CourseController::class)->except(['show']);

    // Students Management
    Route::get('/students', [\App\Http\Controllers\Admin\StudentController::class, 'index'])->name('students.index');
});

// Livewire Admin Routes
Route::middleware(['auth', 'can:admin'])->prefix('livewire-admin')->name('livewire.admin.')->group(function () {
    Route::get('/dashboard', DashboardManager::class)->name('dashboard');
    Route::get('/courses', CourseManager::class)->name('courses.index');
    Route::get('/payments', PaymentManager::class)->name('payments.index');
    Route::get('/students', StudentManager::class)->name('students.index');
    Route::get('/applications', ApplicationManager::class)->name('applications.index');
});

/*
|--------------------------------------------------------------------------
| CASHIER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'can:cashier'])->prefix('cashier')->name('cashier.')->group(function () {
    Route::get('/dashboard', [CashierDashboard::class, 'index'])->name('dashboard');
    Route::get('/payments', [CashierPaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments', [CashierPaymentController::class, 'store'])->name('payments.store');
    Route::patch('/payments/{id}', [CashierPaymentController::class, 'update'])->name('payments.update');
    Route::patch('/payments/{id}/status', [CashierPaymentController::class, 'updateStatus'])->name('payments.updateStatus');
    Route::delete('/payments/{id}', [CashierPaymentController::class, 'destroy'])->name('payments.destroy');
});

// Livewire Cashier Routes
Route::middleware(['auth', 'can:cashier'])->prefix('livewire-cashier')->name('livewire.cashier.')->group(function () {
    Route::get('/dashboard', CashierDashboardManager::class)->name('dashboard');
    Route::get('/payments', CashierPaymentManager::class)->name('payments.index');
});

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');
    
    // Enrollment
    Route::get('/enrollment/create', [EnrollmentController::class, 'create'])->name('enrollment.create');
    Route::post('/enrollment', [EnrollmentController::class, 'store'])->name('enrollment.store');

    // Payments
    Route::get('/payments', [StudentPayment::class, 'index'])->name('payment.index');
    Route::get('/payments/{payment}/invoice', [StudentPayment::class, 'invoice'])->name('payment.invoice');

    // Profile
    Route::get('/profile', [StudentProfile::class, 'edit'])->name('profile');
});

// Livewire Student Routes
Route::middleware(['auth', 'verified'])->prefix('livewire-student')->name('livewire.student.')->group(function () {
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
