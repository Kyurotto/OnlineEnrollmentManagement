<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\Cashier\CashierPaymentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Registrar\ProgramController;
use App\Http\Controllers\Registrar\SemesterController;
use App\Http\Controllers\Registrar\AcademicYearController;
use App\Http\Controllers\Registrar\SectionController;
use App\Http\Student\PaymentController as StudentPaymentController;
use App\Http\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Registrar\DashboardController as RegistrarDashboard;
use App\Http\Controllers\Registrar\StudentController as RegistrarStudent;
use App\Http\Controllers\Registrar\ApplicationController as RegistrarApplicationController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
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
        return redirect()->route('cashier.payments.index');
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
Route::middleware(['auth', 'can:registrar'])->prefix('registrar')->name('registrar.')->group(function () {

    // 1. Dashboard (Uses Registrar\DashboardController)
    Route::get('/dashboard', [RegistrarDashboard::class, 'index'])->name('dashboard');

    // 2. Manage Students (Uses Registrar\StudentController)
    Route::resource('students', RegistrarStudent::class);

    // 3. Manage Applications
    Route::get('/applications', [RegistrarApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{id}', [RegistrarApplicationController::class, 'show'])->name('applications.show');
    Route::patch('/applications/{id}', [RegistrarApplicationController::class, 'update'])->name('applications.update');
    Route::delete('/applications/{id}', [RegistrarApplicationController::class, 'destroy'])->name('applications.destroy');

    Route::get('/programs', function() { return back()->with('status', 'Manage Programs feature coming soon.'); })->name('programs.index');
    Route::get('/academic-years', function() { return back()->with('status', 'Manage Academic Years feature coming soon.'); })->name('academic_years.index');
    Route::get('/semesters', function() { return back()->with('status', 'Manage Semesters feature coming soon.'); })->name('semesters.index');
    Route::get('/sections', function() { return back()->with('status', 'Manage Sections feature coming soon.'); })->name('sections.index');

    Route::resource('programs', ProgramController::class);
    Route::get('/academic-years', function() { return back()->with('status', 'Coming soon.'); })->name('academic_years.index');
    Route::resource('semesters', SemesterController::class);
    Route::resource('academic-years', AcademicYearController::class);
    Route::resource('sections', SectionController::class);
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Courses
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{id}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{id}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::delete('/payments/{id}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::patch('/payments/{id}', [PaymentController::class, 'update'])->name('payments.update');

    // Applications
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{id}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::patch('/applications/{id}', [ApplicationController::class, 'update'])->name('applications.update');
    Route::delete('/applications/{id}', [ApplicationController::class, 'destroy'])->name('applications.destroy');
    // Keeping these for backward compatibility if your views still use them:
    Route::post('/applications/{id}/approve', [ApplicationController::class, 'approve'])->name('applications.approve');
    Route::post('/applications/{id}/reject', [ApplicationController::class, 'reject'])->name('applications.reject');

    // Students
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::patch('/students/{id}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
});

/*
|--------------------------------------------------------------------------
| CASHIER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'can:cashier'])->group(function () {
    Route::get('/cashier/payments', [CashierPaymentController::class, 'index'])->name('cashier.payments.index');
    Route::patch('/cashier/payments/{id}', [CashierPaymentController::class, 'updateStatus'])->name('cashier.payments.update');
    Route::delete('/cashier/payments/{id}', [CashierPaymentController::class, 'destroy'])->name('cashier.payments.destroy');
});

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');
    
    // Enrollment Application
    Route::get('/enrollment/create', [EnrollmentController::class, 'create'])->name('enrollment.create');
    Route::post('/enrollment', [EnrollmentController::class, 'store'])->name('enrollment.store');

    // Payment History (The new controller we created)
    Route::get('/payments', [StudentPaymentController::class, 'index'])->name('payment.index');

    // Profile
    Route::get('/profile', function () {
        return view('student.profile');
    })->name('profile');
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

// Explicit Logout Route
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    // Redirect to LOGIN PAGE, not root
    return redirect()->route('login');
})->name('logout');

require __DIR__.'/auth.php';