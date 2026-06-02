<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Services\DroppedStudentReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function printStudentRegistry(Request $request)
    {
        $search = $request->query('search');
        $filter = $request->query('filter', 'all');
        $sortField = $request->query('sortField', 'users.id');
        $sortDirection = $request->query('sortDirection', 'desc');

        // Whitelist allowed sort fields to prevent SQL injection
        $allowedSortFields = [
            'users.id', 'users.last_name', 'users.first_name', 'users.email',
            'latest_enrollments.course_code', 'latest_enrollments.year_level'
        ];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'users.id';
        }

        // Whitelist allowed sort directions
        $sortDirection = in_array(strtolower($sortDirection), ['asc', 'desc']) ? $sortDirection : 'desc';

        $courseFilter = $request->query('course_filter', 'All Programs');
        $levelFilter = $request->query('level', 'All Levels');
        $yearFilter = $request->query('year_level', 'All Years');
        $sectionFilter = $request->query('section_filter', 'All Sections');

        $optionalEnrollmentColumns = ['level', 'promissory_reason', 'is_regular', 'classification_reason', 'student_type'];
        $availableColumns = collect($optionalEnrollmentColumns)
            ->mapWithKeys(fn($column) => [$column => Schema::hasColumn('enrollments', $column)])
            ->all();

        $enrollmentSelect = ['user_id', 'course_code', 'year_level', 'status', 'id'];
        foreach ($optionalEnrollmentColumns as $column) {
            $enrollmentSelect[] = $availableColumns[$column]
                ? $column
                : DB::raw("NULL as {$column}");
        }

        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $activeYearName = $activeYear ? $activeYear->year_name : null;

        $query = User::query()
            ->select('users.*', 'latest_enrollments.course_code', 'latest_enrollments.year_level',
                     'latest_enrollments.id as enrollment_id', 'latest_enrollments.level',
                     'latest_enrollments.is_regular', 'latest_enrollments.classification_reason',
                     'latest_enrollments.student_type', 'courses.course_name')
            ->joinSub(
                Enrollment::select($enrollmentSelect)->whereIn('id', function ($q) {
                    $q->selectRaw('MAX(id)')->from('enrollments')->groupBy('user_id');
                }),
                'latest_enrollments',
                'users.id', '=', 'latest_enrollments.user_id'
            )
            ->leftJoin('courses', 'latest_enrollments.course_code', '=', 'courses.course_code')
            ->where('users.role', 'student')
            ->where('latest_enrollments.status', 'Enrolled');

        if ($activeYearName) {
            $query->where('latest_enrollments.year_level', 'like', '%' . $activeYearName . '%');
        }

        // Applying filters
        if ($courseFilter && $courseFilter !== 'All Programs') {
            $query->where('latest_enrollments.course_code', $courseFilter);
        }
        if ($levelFilter && $levelFilter !== 'All Levels') {
            $query->where('latest_enrollments.level', strtolower($levelFilter));
        }
        if ($yearFilter && $yearFilter !== 'All Years') {
            $query->where('latest_enrollments.year_level', 'like', "{$yearFilter}%");
        }
        if ($sectionFilter && $sectionFilter !== 'All Sections') {
            preg_match('/\d+/', $sectionFilter, $matches);
            if (!empty($matches)) {
                $yearNum = $matches[0];
                $query->where('latest_enrollments.year_level', 'like', "{$yearNum}%");
            }
        }
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('users.first_name', 'like', "%{$search}%")
                  ->orWhere('users.last_name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('latest_enrollments.course_code', 'like', "%{$search}%")
                  ->orWhere('latest_enrollments.promissory_reason', 'like', "%{$search}%");
            });
        }
        if ($filter === 'regular') {
            $query->whereRaw('latest_enrollments.is_regular = 1');
        } elseif ($filter === 'irregular') {
            $query->whereRaw('latest_enrollments.is_regular = 0');
        }

        $students = $query->orderBy($sortField, $sortDirection)->get();

        return view('reports.student-registry', compact('students', 'filter', 'courseFilter', 'levelFilter', 'yearFilter'));
    }

    public function printPayments(Request $request)
    {
        $query = Payment::select('payments.*')
            ->leftJoin('enrollments', 'payments.application_id', '=', 'enrollments.id')
            ->leftJoin('users', 'payments.user_id', '=', 'users.id')
            ->with(['user', 'application']);

        if ($request->has('status') && $request->status != 'All statuses') {
            $query->where('payments.status', $request->status);
        }

        if ($request->has('filter_course') && $request->filter_course != 'ALL') {
            $query->where('enrollments.course_code', $request->filter_course);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payments.id', 'like', "%{$search}%")
                ->orWhere('payments.transaction_id', 'like', "%{$search}%")
                ->orWhereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        $payments = $query->orderBy('payments.id', 'desc')->get();
        $totalAmount = $payments->where('status', 'Paid')->sum('amount');

        return view('reports.payments', compact('payments', 'totalAmount'));
    }

    public function printDroppedStudents(Request $request)
    {
        $service = new DroppedStudentReportService();
        $officiallyDropped = $service->getOfficiallyDropped();
        $reasonSummary = $service->getDropReasonSummary();
        $totalPenalties = $officiallyDropped->sum('drop_charge');

        return view('reports.dropped', compact('officiallyDropped', 'reasonSummary', 'totalPenalties'));
    }
}
