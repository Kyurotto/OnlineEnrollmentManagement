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

// Registrar Controllers
use App\Http\Controllers\RegistrarDashboardController;
use App\Http\Controllers\RegistrarStudentController;
use App\Http\Controllers\RegistrarApplicationController;
use App\Http\Controllers\RegistrarAcademicYearController;
use App\Http\Controllers\RegistrarSemesterController;
use App\Http\Controllers\RegistrarProgramController;
use App\Http\Controllers\RegistrarSectionController;

// Cashier Controllers
use App\Http\Controllers\CashierPaymentController;
use App\Livewire\CashierDashboardManager;
use App\Livewire\PaymentManager as CashierPaymentManager;
use App\Livewire\PaymentAssessmentManager;

// Student Controllers
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StudentEnrollmentController;
use App\Http\Controllers\StudentPaymentController;
use App\Http\Controllers\StudentPaymentRedirectController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\DocumentController;

// Student Livewire Components
use App\Livewire\StudentPaymentManager;
use App\Livewire\StudentProfileManager;

// Registrar Livewire Components
use App\Livewire\RegistrarApplicationManager;
use App\Livewire\RegistrarStudentManager;

// Admin Livewire Components
use App\Livewire\Admin\AdminStudentManager;
use App\Livewire\Admin\AdminApplicationManager;




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
    Route::get('/students', RegistrarStudentManager::class)->name('students.index');
    Route::get('/students/{id}/edit', [RegistrarStudentController::class, 'edit'])->name('students.edit');
    Route::patch('/students/{id}', [RegistrarStudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{id}', [RegistrarStudentController::class, 'destroy'])->name('students.destroy');

    Route::get('/profile-bank', [RegistrarStudentController::class, 'profileBank'])->name('profile_bank.index');

    Route::get('/applications', RegistrarApplicationManager::class)->name('applications.index');
    Route::get('/applications/college', RegistrarApplicationManager::class)->name('applications.college');
    Route::get('/applications/shs', RegistrarApplicationManager::class)->name('applications.shs');
    Route::get('/archives', \App\Livewire\RegistrarArchiveManager::class)->name('archives.index');
    Route::patch('/applications/{id}/toggle-physical', [RegistrarApplicationController::class, 'togglePhysicalDocuments'])->name('applications.toggle-physical');
    Route::post('/applications/{id}/apply-voucher', [RegistrarApplicationController::class, 'applyVoucher'])->name('applications.apply-voucher');
    Route::post('/applications/{id}/remove-voucher', [RegistrarApplicationController::class, 'removeVoucher'])->name('applications.remove-voucher');

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
    Route::get('/payments/college', PaymentManager::class)->name('payments.college');
    Route::get('/payments/shs', PaymentManager::class)->name('payments.shs');
    Route::get('/students', AdminStudentManager::class)->name('students.index');
    Route::get('/students/{id}/edit', [AdminStudentController::class, 'edit'])->name('students.edit');
    Route::patch('/students/{id}', [AdminStudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{id}', [AdminStudentController::class, 'destroy'])->name('students.destroy');
    Route::get('/applications', AdminApplicationManager::class)->name('applications.index');
    Route::get('/archives', \App\Livewire\Admin\AdminArchiveManager::class)->name('archives.index');


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
    Route::get('/payments', CashierPaymentManager::class)->name('payments.index');
    Route::get('/payments/college', CashierPaymentManager::class)->name('payments.college');
    Route::get('/payments/shs', CashierPaymentManager::class)->name('payments.shs');
    Route::get('/assessment/shs', PaymentAssessmentManager::class)->name('assessment.shs');
    Route::get('/assessment/college', PaymentAssessmentManager::class)->name('assessment.college');
});

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'can:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/enrollment/create', [StudentEnrollmentController::class, 'create'])->name('enrollment.create');
    Route::get('/enrollment/review', [StudentEnrollmentController::class, 'review'])->name('enrollment.review');
    Route::post('/enrollment', [StudentEnrollmentController::class, 'store'])->name('enrollment.store');
    Route::get('/enrollment/upload', [StudentEnrollmentController::class, 'upload'])->name('enrollment.upload');
    Route::post('/enrollment/upload', [StudentEnrollmentController::class, 'storeUpload'])->name('enrollment.upload.store');

    // Edit workflows
    Route::post('/enrollment/edit-request', [StudentEnrollmentController::class, 'requestEdit'])->name('enrollment.request_edit');
    Route::get('/enrollment/edit', [StudentEnrollmentController::class, 'edit'])->name('enrollment.edit');
    Route::put('/enrollment', [StudentEnrollmentController::class, 'update'])->name('enrollment.update');

    Route::get('/payments', [StudentPaymentRedirectController::class, 'redirect'])->name('payment');
    Route::get('/payments/shs', StudentPaymentManager::class)->name('payment.shs');
    Route::get('/payments/college', StudentPaymentManager::class)->name('payment.college');
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

    // Secure Document Route
    Route::get('/documents/{path}', [DocumentController::class, 'show'])->where('path', '.*')->name('document.show');
});

// Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

require __DIR__.'/auth.php';
