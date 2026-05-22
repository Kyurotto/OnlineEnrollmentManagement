<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RegistryExportController extends Controller
{
    public function export(Request $request)
    {
        $search = $request->query('search');
        $filter = $request->query('filter', 'all');
        $sortField = $request->query('sortField', 'users.id');
        $sortDirection = $request->query('sortDirection', 'desc');
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

        $csv = [];
        $csv[] = ['Last Name', 'First Name', 'Middle Name', 'Programs', 'Academic Track', 'Section'];

        foreach ($students as $student) {
            $program = $student->course_name ?: 'N/A';
            $track = $student->course_code ?: 'N/A';
            $section = 'N/A';
            if (!empty($student->year_level)) {
                $parts = explode('|', $student->year_level);
                $section = trim($parts[0]);
            }
            $csv[] = [
                $student->last_name,
                $student->first_name,
                $student->middle_name ?: '',
                $program,
                $track,
                $section
            ];
        }

        $filename = "student_registry_export_" . now()->format('Ymd_His') . ".csv";
        $handle = fopen('php://temp', 'r+');
        foreach ($csv as $row) { fputcsv($handle, $row); }
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response()->streamDownload(function () use ($content) { echo $content; }, $filename);
    }
}
