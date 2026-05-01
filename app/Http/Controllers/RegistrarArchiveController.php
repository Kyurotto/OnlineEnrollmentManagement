<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\AcademicYear;
use App\Models\Semester;

class RegistrarArchiveController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $level = $request->get('level', '');
        $selectedCourse = $request->get('selectedCourse', '');
        $selectedFolder = $request->get('selectedFolder');

        // Get all archived folder groupings
        $folders = Enrollment::whereNotNull('archived_at')
            ->whereNotNull('semester_name')
            ->whereNotNull('academic_year_name')
            ->select('semester_name', 'academic_year_name')
            ->selectRaw('COUNT(*) as student_count')
            ->groupBy('semester_name', 'academic_year_name')
            ->orderBy('academic_year_name', 'desc')
            ->orderBy('semester_name', 'desc')
            ->get();

        // Also get legacy archives
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $legacyEnrollments = collect();
        if ($activeYear && $activeSemester) {
            $legacyEnrollments = Enrollment::whereNull('archived_at')
                ->where(function($q) use ($activeYear, $activeSemester) {
                    $q->where('enrollments.year_level', 'NOT LIKE', "%{$activeYear->year_name}%")
                      ->orWhere('enrollments.year_level', 'NOT LIKE', "%{$activeSemester->name}%");
                })
                ->get();
        }

        $legacyFolders = collect();
        foreach ($legacyEnrollments as $enrollment) {
            $parts = array_map('trim', explode('|', $enrollment->year_level));
            if (count($parts) >= 3) {
                $semesterName = $parts[1];
                $academicYearName = $parts[2];
            } else {
                $semesterName = 'Unknown Semester';
                $academicYearName = 'Unknown Year';
            }

            $key = $semesterName . '|' . $academicYearName;
            if (!$legacyFolders->has($key)) {
                $legacyFolders->put($key, (object)[
                    'semester_name' => $semesterName,
                    'academic_year_name' => $academicYearName,
                    'student_count' => 0
                ]);
            }
            $legacyFolders[$key]->student_count++;
        }

        foreach ($legacyFolders as $key => $legacyFolder) {
            $existingFolder = $folders->first(function ($folder) use ($legacyFolder) {
                return $folder->semester_name === $legacyFolder->semester_name && 
                       $folder->academic_year_name === $legacyFolder->academic_year_name;
            });

            if ($existingFolder) {
                $existingFolder->student_count += $legacyFolder->student_count;
            } else {
                $folders->push($legacyFolder);
            }
        }

        $folders = $folders->sortByDesc(function ($folder) {
            return $folder->academic_year_name . '-' . $folder->semester_name;
        })->values();

        $applications = collect();
        if ($selectedFolder) {
            $parts = explode('|', $selectedFolder);
            $semesterName = trim($parts[0] ?? '');
            $academicYearName = trim($parts[1] ?? '');

            $query = Enrollment::query()
                ->select('enrollments.*')
                ->join('users', 'enrollments.user_id', '=', 'users.id')
                ->with(['user'])
                ->where(function($q) use ($semesterName, $academicYearName) {
                    $q->where(function($sub) use ($semesterName, $academicYearName) {
                        $sub->whereNotNull('enrollments.archived_at')
                            ->where('enrollments.semester_name', $semesterName)
                            ->where('enrollments.academic_year_name', $academicYearName);
                    })
                    ->orWhere(function($sub) use ($semesterName, $academicYearName) {
                        $sub->whereNull('enrollments.archived_at')
                            ->where('enrollments.year_level', 'LIKE', "%{$semesterName}%")
                            ->where('enrollments.year_level', 'LIKE', "%{$academicYearName}%");
                    });
                });

            if ($level) {
                $query->where('enrollments.level', $level);
            }

            if ($selectedCourse) {
                $query->where('enrollments.course_code', $selectedCourse);
            }

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($sub) use ($search) {
                        $sub->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhere('enrollments.course_code', 'like', "%{$search}%")
                    ->orWhere('enrollments.year_level', 'like', "%{$search}%");
                });
            }

            $applications = $query->orderBy('enrollments.created_at', 'desc')->paginate(10);
        }

        return view('registrar.archives.index', [
            'applications' => $applications,
            'folders' => $folders,
            'courses' => Course::orderBy('course_name')->get(),
            'search' => $search,
            'level' => $level,
            'selectedCourse' => $selectedCourse,
            'selectedFolder' => $selectedFolder,
        ]);
    }

    public function togglePhysicalDocuments($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->physical_documents_received = $enrollment->physical_documents_received == 1 ? 0 : 1;
        $enrollment->save();

        return back()->with('success', 'Physical documents status updated.');
    }

    public function verifyCredentials($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->credentials_verified = 1;
        $enrollment->save();

        return back()->with('success', 'Credentials marked as verified.');
    }
}
