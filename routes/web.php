<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
// Admin Controllers
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Livewire\Admin\DashboardManager;
use App\Livewire\Admin\PaymentManager;
use App\Livewire\Admin\ApplicationManager;
use App\Livewire\Admin\AdminNavbar;

// Registrar Controllers
use App\Http\Controllers\Registrar\DashboardController as RegistrarDashboardController;
use App\Http\Controllers\Registrar\StudentController as RegistrarStudentController;
use App\Http\Controllers\Registrar\ApplicationController as RegistrarApplicationController;
use App\Http\Controllers\Registrar\AcademicYearController as RegistrarAcademicYearController;
use App\Http\Controllers\Registrar\SemesterController as RegistrarSemesterController;
use App\Http\Controllers\Registrar\ProgramController as RegistrarProgramController;
use App\Http\Controllers\Registrar\SectionController as RegistrarSectionController;

// Cashier Controllers
use App\Http\Controllers\Cashier\CashierPaymentController;
use App\Livewire\Cashier\CashierDashboardManager;

// Student Controllers
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\StudentEnrollmentController;

// Student Livewire Components
use App\Livewire\Student\StudentDashboardManager;
use App\Livewire\Student\StudentEnrollmentManager;
use App\Livewire\Student\StudentPaymentManager; 
use App\Livewire\Student\StudentProfileManager;




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

    // Look for the user in the employees table strictly by user_id OR email
    $employee = DB::table('employees')->where('user_id', $user->id)
                  ->orWhere('email', $user->email)
                  ->first();

    // STAFF roles MUST be verified against the employees table
    if ($employee) {
        $role = $employee->role;
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'registrar') {
            return redirect()->route('registrar.dashboard');
        } elseif ($role === 'cashier') {
            return redirect()->route('cashier.dashboard');
        }
    }

    // Default to student if applicable, or fallback to login
    if ($user->role === 'student') {
        return redirect()->route('student.dashboard');
    }

    return redirect()->route('login')->with('error', 'Unauthorized access. No employee record found.');
})->name('dashboard');
});


/*
|--------------------------------------------------------------------------
| REGISTRAR ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'can:registrar'])->prefix('registrar')->name('registrar.')->group(function () {
    Route::get('/dashboard', [RegistrarDashboardController::class, 'index'])->name('dashboard');
    Route::patch('/dashboard/applications/{id}/approve', [RegistrarDashboardController::class, 'approve'])->name('dashboard.approve');
    Route::patch('/dashboard/applications/{id}/reject', [RegistrarDashboardController::class, 'reject'])->name('dashboard.reject');
    Route::get('/students', [RegistrarStudentController::class, 'index'])->name('students.index');
    Route::get('/students/{id}/edit', [RegistrarStudentController::class, 'edit'])->name('students.edit');
    Route::patch('/students/{id}', [RegistrarStudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{id}', [RegistrarStudentController::class, 'destroy'])->name('students.destroy');
    
    Route::get('/applications', [RegistrarApplicationController::class, 'index'])->name('applications.index');
    Route::patch('/applications/{id}', [RegistrarApplicationController::class, 'update'])->name('applications.update');
    Route::delete('/applications/{id}', [RegistrarApplicationController::class, 'destroy'])->name('applications.destroy');
    
    Route::get('/academic-years', [RegistrarAcademicYearController::class, 'index'])->name('academic_years.index');
    Route::post('/academic-years', [RegistrarAcademicYearController::class, 'store'])->name('academic_years.store');
    Route::patch('/academic-years/{id}', [RegistrarAcademicYearController::class, 'update'])->name('academic_years.update');
    Route::delete('/academic-years/{id}', [RegistrarAcademicYearController::class, 'destroy'])->name('academic_years.destroy');
    
    Route::get('/semesters', [RegistrarSemesterController::class, 'index'])->name('semesters.index');
    Route::post('/semesters', [RegistrarSemesterController::class, 'store'])->name('semesters.store');
    Route::patch('/semesters/{id}', [RegistrarSemesterController::class, 'update'])->name('semesters.update');
    Route::delete('/semesters/{id}', [RegistrarSemesterController::class, 'destroy'])->name('semesters.destroy');
    Route::patch('/semesters/{id}/activate', [RegistrarSemesterController::class, 'activate'])->name('semesters.activate');
    
    Route::get('/programs', [RegistrarProgramController::class, 'index'])->name('programs.index');
    Route::post('/programs', [RegistrarProgramController::class, 'store'])->name('programs.store');
    Route::patch('/programs/{id}', [RegistrarProgramController::class, 'update'])->name('programs.update');
    Route::delete('/programs/{id}', [RegistrarProgramController::class, 'destroy'])->name('programs.destroy');
    
    Route::get('/sections', [RegistrarSectionController::class, 'index'])->name('sections.index');
    Route::post('/sections', [RegistrarSectionController::class, 'store'])->name('sections.store');
    Route::patch('/sections/{id}', [RegistrarSectionController::class, 'update'])->name('sections.update');
    Route::delete('/sections/{id}', [RegistrarSectionController::class, 'destroy'])->name('sections.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', DashboardManager::class)->name('dashboard');
    Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses.index');
    Route::post('/courses', [AdminCourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{id}/edit', [AdminCourseController::class, 'edit'])->name('courses.edit');
    Route::patch('/courses/{id}', [AdminCourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{id}', [AdminCourseController::class, 'destroy'])->name('courses.destroy');
    Route::get('/payments', PaymentManager::class)->name('payments.index');
    Route::get('/students', [AdminStudentController::class, 'index'])->name('students.index');
    Route::get('/applications', [App\Http\Controllers\Admin\ApplicationController::class, 'index'])->name('applications.index');
    Route::patch('/applications/{id}', [App\Http\Controllers\Admin\ApplicationController::class, 'update'])->name('applications.update');
    Route::delete('/applications/{id}', [App\Http\Controllers\Admin\ApplicationController::class, 'destroy'])->name('applications.destroy');


    // API/Export routes
    Route::get('/payments/export', [\App\Http\Controllers\Admin\PaymentController::class, 'export'])->name('payments.export');
    Route::get('/api/pending-counts', [\App\Http\Controllers\Admin\DashboardController::class, 'getPendingCounts'])->name('api.pending-counts');
    Route::post('/api/notifications/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('api.notifications.mark-read');
});

/*
|--------------------------------------------------------------------------
| CASHIER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'can:cashier'])->prefix('cashier')->name('cashier.')->group(function () {
    Route::get('/dashboard', CashierDashboardManager::class)->name('dashboard');
    Route::get('/payments', [CashierPaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments', [CashierPaymentController::class, 'store'])->name('payments.store');
    Route::patch('/payments/{id}', [CashierPaymentController::class, 'update'])->name('payments.update');
    Route::patch('/payments/{id}/status', [CashierPaymentController::class, 'updateStatus'])->name('payments.update_status');
});

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/enrollment/create', [StudentEnrollmentController::class, 'create'])->name('enrollment.create');
    Route::post('/enrollment', [StudentEnrollmentController::class, 'store'])->name('enrollment.store');
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
    
    Route::post('/notifications/{id}/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});

// Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

require __DIR__.'/auth.php';
