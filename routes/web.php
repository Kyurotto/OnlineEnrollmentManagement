<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// --- CONTROLLER IMPORTS ---

// General
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EnrollmentController;

// Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\StudentController as AdminStudent;
use App\Http\Controllers\Admin\PaymentController as AdminPayment;
use App\Http\Controllers\Admin\ApplicationController as AdminApplication;

// Registrar
use App\Http\Controllers\Registrar\DashboardController as RegistrarDashboard;
use App\Http\Controllers\Registrar\StudentController as RegistrarStudent;
use App\Http\Controllers\Registrar\ApplicationController as RegistrarApplication;
use App\Http\Controllers\Registrar\ProgramController;
use App\Http\Controllers\Registrar\SemesterController;
use App\Http\Controllers\Registrar\AcademicYearController;
use App\Http\Controllers\Registrar\SectionController;

// Cashier
use App\Http\Controllers\Cashier\DashboardController as CashierDashboard;
use App\Http\Controllers\Cashier\CashierPaymentController; // Ensure this matches your file class name


// Student
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\PaymentController as StudentPayment; 
// Note: If your student payment file is in App\Http\Student, move it to App\Http\Controllers\Student

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
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
Route::middleware(['auth', 'can:registrar'])->prefix('registrar')->name('registrar.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [RegistrarDashboard::class, 'index'])->name('dashboard');

    // Students & Applications
    Route::resource('students', RegistrarStudent::class);
    
    // Custom Application Routes
    Route::get('/applications', [RegistrarApplication::class, 'index'])->name('applications.index');
    Route::get('/applications/{id}', [RegistrarApplication::class, 'show'])->name('applications.show');
    Route::patch('/applications/{id}', [RegistrarApplication::class, 'update'])->name('applications.update');
    Route::delete('/applications/{id}', [RegistrarApplication::class, 'destroy'])->name('applications.destroy');

    // Academic Management (Programs, Semesters, etc.)
    Route::resource('programs', ProgramController::class);
    Route::resource('academic-years', AcademicYearController::class);
    Route::resource('semesters', SemesterController::class);
    Route::resource('sections', SectionController::class);

    // Custom Semester Activation
    Route::patch('/semesters/{id}/activate', [SemesterController::class, 'activate'])->name('semesters.activate');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Resources
    Route::resource('courses', CourseController::class);
    
    // Payments (Admin View)
    Route::get('/payments', [AdminPayment::class, 'index'])->name('payments.index');
    Route::post('/payments', [AdminPayment::class, 'store'])->name('payments.store');
    Route::patch('/payments/{id}', [AdminPayment::class, 'update'])->name('payments.update');
    Route::patch('/payments/{id}/status', [AdminPayment::class, 'updateStatus'])->name('payments.updateStatus');
    Route::delete('/payments/{id}', [AdminPayment::class, 'destroy'])->name('payments.destroy');

    // Students
    Route::resource('students', AdminStudent::class);

    // Applications
    Route::get('/applications', [AdminApplication::class, 'index'])->name('applications.index');
    Route::get('/applications/{id}', [AdminApplication::class, 'show'])->name('applications.show');
    Route::patch('/applications/{id}', [AdminApplication::class, 'update'])->name('applications.update');
    Route::delete('/applications/{id}', [AdminApplication::class, 'destroy'])->name('applications.destroy');
});

/*
|--------------------------------------------------------------------------
| CASHIER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'can:cashier'])->prefix('cashier')->name('cashier.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Cashier\DashboardController::class, 'index'])->name('dashboard');

    // Payments Management
    Route::get('/payments', [CashierPaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments', [CashierPaymentController::class, 'store'])->name('payments.store');
    
    // Update Details (Edit Amount/Ref)
    Route::patch('/payments/{id}', [CashierPaymentController::class, 'update'])->name('payments.update');
    
    // Update Status (Approve/Reject)
    Route::patch('/payments/{id}/status', [CashierPaymentController::class, 'updateStatus'])->name('payments.updateStatus');
    
    Route::delete('/payments/{id}', [CashierPaymentController::class, 'destroy'])->name('payments.destroy');
});

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('student')->name('student.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');
    
    // Enrollment
    Route::get('/enrollment/create', [EnrollmentController::class, 'create'])->name('enrollment.create');
    Route::post('/enrollment', [EnrollmentController::class, 'store'])->name('enrollment.store');

    // Payment History
    Route::get('/payments', [StudentPayment::class, 'index'])->name('payment.index');

    // Profile (Read Only View)
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

// Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

require __DIR__.'/auth.php';