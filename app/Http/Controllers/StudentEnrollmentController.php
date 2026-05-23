<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semester;
use App\Models\AcademicYear;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewEnrollmentSubmitted;

class StudentEnrollmentController extends Controller
{
    public function create(Request $request)
    {
        $level = $request->query('level');

        // If no level provided, show the selection screen
        if (!$level) {
            return view('student.enrollment_choice');
        }

        // Validate level parameter
        if (!in_array($level, ['shs', 'college'])) {
            return redirect()->route('student.enrollment.create')->with('error', 'Invalid enrollment level selected.');
        }

        $user = Auth::user();

        // Fetch programs and strands from the database
        $programs = Course::where('type', 'program')->orderBy('course_code', 'asc')->get();
        $strands = Course::where('type', 'shs')->orderBy('course_code', 'asc')->get();

        // Initial data for the form
        $data = [
            'level' => $level,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'middle_name' => $user->middle_name,
            'programs' => $programs,
            'strands' => $strands,
        ];

        // Restore draft from session
        $draft = session()->get('enrollment_draft_' . Auth::id(), []);
        $data = array_merge($data, $draft);

        $activeSemester = Semester::where('is_active', true)->first();
        $activeYear = AcademicYear::where('is_active', true)->first();

        return view('student.enrollment', array_merge($data, [
            'activeSemester' => $activeSemester,
            'activeYear' => $activeYear,
        ]));
    }

    public function store(Request $request)
    {
        $level = $request->input('level');

        // Validate level parameter
        if (!in_array($level, ['shs', 'college'])) {
            return back()->with('error', 'Invalid enrollment level.')->withInput();
        }

        // Build validation rules based on enrollment level
        $validationRules = [
            'level' => 'required|in:shs,college',
            'course_code' => 'required',
            'year_level' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'birth_date' => 'required|date',
            'age' => 'required|numeric',
            'gender' => 'required',
            'contact' => 'required',
            'middle_name' => 'nullable|string|max:255',
            'extension' => 'nullable|string|max:50',
            'lrn' => 'nullable|string|max:12',
            'facebook_account' => 'nullable|string|max:255',
            'religion_church' => 'nullable|string|max:255',
            'junior_high_school' => 'nullable|string|max:255',
            'health_concerns' => 'nullable|string|max:1000',
            'prk_blk_lot_vill' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:10',
            'father_name' => 'nullable|string|max:255',
            'mother_maiden_name' => 'nullable|string|max:255',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:11',
        ];

        $request->validate($validationRules);

        $course = Course::where('course_code', $request->course_code)->first();

        if (!$course) {
            return back()->with('error', 'Selected program is invalid or not registered in the system.')->withInput();
        }

        // Auto-populate semester and academic year from active registrar settings
        $activeSemester = Semester::where('is_active', true)->first();
        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$activeSemester || !$activeYear) {
            return back()->with('error', 'No active semester or academic year set by the registrar. Please contact the registrar.')->withInput();
        }

        $semesterName = $activeSemester->name;
        $academicYearName = $activeYear->year_name;

        // Unified Year Level String: "Year | Semester | Academic Year"
        $unifiedYearLevel = "{$request->year_level} | {$semesterName} | {$academicYearName}";

        // Check for any previous balance carried over from prior terms
        $previousBalance = \Illuminate\Support\Facades\Cache::pull("student_previous_balance_" . Auth::id(), 0);

        // Check if they already have clearance from a preliminary shell record or previous term
        $latestAny = Enrollment::where('user_id', Auth::id())->latest()->first();
        $isAlreadyCleared = $latestAny && $latestAny->credentials_verified == 1;
        $isAlreadyReceived = $latestAny && $latestAny->physical_documents_received == 1;

        $enrollment = Enrollment::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'course_code' => $request->course_code,
            'level' => $level,
            'year_level' => $unifiedYearLevel,
            'semester_name' => $semesterName,
            'academic_year_name' => $academicYearName,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'extension' => $request->extension,
            'lrn' => $request->lrn,
            'birth_date' => $request->birth_date,
            'age' => $request->age,
            'gender' => $request->gender,
            'email' => $request->email,
            'contact' => $request->contact,
            'facebook_account' => $request->facebook_account,
            'religion_church' => $request->religion_church,
            'junior_high_school' => $request->junior_high_school,
            'health_concerns' => $request->health_concerns,
            'prk_blk_lot_vill' => $request->prk_blk_lot_vill,
            // Address logic (simplified for validation, or we can fetch individual components if they were in the form)
            'address_full' => implode(', ', array_filter([$request->prk_blk_lot_vill, $request->barangay, $request->city, $request->province, $request->zip])),
            'father_name' => $request->father_name,
            'mother_maiden_name' => $request->mother_maiden_name,
            'guardian_name' => $request->guardian_name,
            'guardian_contact' => $request->guardian_contact,
            'status' => 'Pending',
            'previous_balance' => $previousBalance,
            'credentials_verified' => $isAlreadyCleared ? 1 : 0,
            'physical_documents_received' => $isAlreadyReceived ? 1 : 0,
        ]);

        // ARCHIVE OLD RECORDS: Automatically archive all PREVIOUS non-archived records for this student
        // Use direct DB update for maximum reliability
        \Illuminate\Support\Facades\DB::table('enrollments')
            ->where('user_id', Auth::id())
            ->where('id', '!=', $enrollment->id)
            ->whereNull('archived_at')
            ->update([
                'archived_at' => now(),
                'status' => 'Enrolled', // Ensure they are marked as finished in archives
                'updated_at' => now()
            ]);

        // Fix missing metadata for folders in archives (for old records that lack columns)
        $archivedRecords = Enrollment::where('user_id', Auth::id())
            ->where('id', '!=', $enrollment->id)
            ->whereNotNull('archived_at')
            ->get();

        foreach ($archivedRecords as $archived) {
            if (empty($archived->semester_name) || empty($archived->academic_year_name)) {
                $parts = array_map('trim', explode('|', $archived->year_level));
                if (count($parts) >= 3) {
                    $archived->update([
                        'semester_name' => $parts[1],
                        'academic_year_name' => $parts[2]
                    ]);
                }
            }
        }

        // Clear draft on successful submission
        session()->forget('enrollment_draft_' . Auth::id());

        // 5. Notify Admins and Registrars
        $staff = User::whereIn('role', ['admin', 'registrar'])->get();
        if ($staff->count() > 0) {
            Notification::send($staff, new NewEnrollmentSubmitted($enrollment));
        }

        return redirect()->route('student.enrollment.upload')->with('success', 'Information saved. Please upload your documents to proceed.');
    }

    public function upload()
    {
        $user = Auth::user();
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where(function ($q) use ($activeYear, $activeSemester) {
                if (!$activeYear || !$activeSemester) return;
                $q->where('year_level', 'LIKE', '%' . $activeYear->year_name . '%')
                    ->where('year_level', 'LIKE', '%' . $activeSemester->name . '%');
            })
            ->latest()
            ->first();

        if (!$enrollment) {
            $latestEnrollment = Enrollment::where('user_id', $user->id)->latest()->first();
            if (!$latestEnrollment) {
                return redirect()->route('student.enrollment.create')->with('info', 'Please submit your enrollment application first.');
            }
            $enrollment = $latestEnrollment;
        }

        // Show previously uploaded documents for returning students without persisting changes
        $docFields = $enrollment->level === 'shs'
            ? ['form_137_path', 'sf10_path', 'good_moral_path', 'psa_path', 'id_picture_path']
            : ['form_137_path', 'good_moral_path', 'psa_path', 'id_picture_path'];

        foreach ($docFields as $field) {
            if (empty($enrollment->{$field})) {
                $fallback = Enrollment::where('user_id', $user->id)
                    ->where('id', '!=', $enrollment->id)
                    ->whereNotNull($field)
                    ->orderBy('id', 'desc')
                    ->value($field);

                if (!empty($fallback)) {
                    $enrollment->setAttribute($field, $fallback);
                }
            }
        }

        return view('student.enrollment_upload', compact('enrollment'));
    }

    public function storeUpload(Request $request)
    {
        $user = Auth::user();
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where(function ($q) use ($activeYear, $activeSemester) {
                if (!$activeYear || !$activeSemester) return;
                $q->where('year_level', 'LIKE', '%' . $activeYear->year_name . '%')
                    ->where('year_level', 'LIKE', '%' . $activeSemester->name . '%');
            })
            ->latest()
            ->first();

        if (!$enrollment) {
            $lastEnrollment = Enrollment::where('user_id', $user->id)->latest()->first();
            if (!$lastEnrollment) {
                return redirect()->route('student.enrollment.create')->with('error', 'No active enrollment record found.');
            }

            // Create a shell record for Clearance (Step 3) tracking
            $enrollment = Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $lastEnrollment->course_id,
                'course_code' => $lastEnrollment->course_code,
                'level' => $lastEnrollment->level,
                'first_name' => $user->first_name,
                'middle_name' => $lastEnrollment->middle_name,
                'last_name' => $user->last_name,
                'extension' => $lastEnrollment->extension,
                'lrn' => $lastEnrollment->lrn,
                'birth_date' => $lastEnrollment->birth_date,
                'age' => $lastEnrollment->age,
                'gender' => $lastEnrollment->gender,
                'email' => $user->email,
                'contact' => $lastEnrollment->contact,
                'address_full' => $lastEnrollment->address_full,
                'status' => 'Pending',
                'year_level' => "Returning Student | " . ($activeSemester->name ?? 'New Semester') . " | " . ($activeYear->year_name ?? 'New Year'),
                'semester_name' => $activeSemester->name ?? '',
                'academic_year_name' => $activeYear->year_name ?? '',
            ]);
        }

        $level = $enrollment->level;

        $validationRules = [
            'id_picture' => 'nullable|image|max:2048',
            'promissory_note' => 'nullable|array',
            'promissory_note.*' => 'nullable|file|mimes:doc,docx,pdf|max:5120',
            'promissory_reason' => 'nullable|string|max:1000',
        ];

        $requiredDocs = ['form_137', 'good_moral', 'psa', 'id_picture'];
        $fileMap = ['form_137' => 'form_137_path', 'good_moral' => 'good_moral_path', 'psa' => 'psa_path', 'id_picture' => 'id_picture_path'];

        if ($level === 'shs') {
            $requiredDocs[] = 'sf10';
            $fileMap['sf10'] = 'sf10_path';
            $validationRules = array_merge($validationRules, [
                'form_137' => 'nullable|file|max:5120',
                'sf10' => 'nullable|file|max:5120',
                'good_moral' => 'nullable|file|max:5120',
                'psa' => 'nullable|file|max:5120',
            ]);
        } else {
            $validationRules = array_merge($validationRules, [
                'form_137' => 'nullable|file|max:5120',
                'good_moral' => 'nullable|file|max:5120',
                'psa' => 'nullable|file|max:5120',
            ]);
        }

        $hasAllDocs = true;
        foreach ($requiredDocs as $doc) {
            $dbField = $fileMap[$doc];
            if (empty($enrollment->$dbField) && !$request->hasFile($doc)) {
                $hasAllDocs = false;
                break;
            }
        }

        if (!$hasAllDocs) {
            if (empty($enrollment->promissory_note_path) && !$request->hasFile('promissory_note')) {
                $validationRules['promissory_note'] = 'required|array|min:1';
                $validationRules['promissory_note.*'] = 'required|file|mimes:doc,docx,pdf|max:5120';
            }
            if (empty($enrollment->promissory_reason) && empty($request->input('promissory_reason'))) {
                $validationRules['promissory_reason'] = 'required|string|max:1000';
            }
        }

        $request->validate($validationRules, [
            'promissory_note.required' => 'A Promissory Note is required because you have incomplete documents.',
            'promissory_reason.required' => 'Please provide a reason for the missing documents.',
        ]);

        $updatedData = [];
        foreach ($fileMap as $field => $dbField) {
            if ($request->hasFile($field)) {
                $updatedData[$dbField] = $request->file($field)->store('enrollments/docs', 'local');
            }
        }

        // Handle Promissory Note (Multiple Files)
        if ($request->hasFile('promissory_note')) {
            $paths = [];
            // If they are uploading new files, we can either append or replace.
            // Let's replace the old ones with the new uploaded files to be safe, or just append. Let's merge them if they want to upgrade.
            // Actually, replacing is safer so they don't bloat the DB, or appending. Since it's a file input, uploading overwrites the selection.
            foreach ($request->file('promissory_note') as $file) {
                $paths[] = $file->store('enrollments/promissory', 'local');
            }
            $updatedData['promissory_note_path'] = json_encode($paths);
        }

        if ($request->has('promissory_reason')) {
            $updatedData['promissory_reason'] = $request->promissory_reason;
        }

        if (!empty($updatedData)) {
            $enrollment->update($updatedData);
        }

        return back()->with('success', 'Information and documents updated successfully.');
    }

    /**
     * Display the review view for an enrollment application
     */
    public function review()
    {
        $user = Auth::user();
        $activeYear = AcademicYear::where('is_active', true)->first();

        // Get the existing enrollment for the current active year
        $enrollment = null;
        if ($activeYear) {
            $enrollment = Enrollment::where('user_id', $user->id)
                ->whereIn('status', ['Pending', 'Approved', 'Enrolled', 'Rejected'])
                ->where('year_level', 'LIKE', '%' . $activeYear->year_name . '%')
                ->latest()
                ->first();
        }

        // If no enrollment found, redirect to dashboard
        if (!$enrollment) {
            return redirect()->route('student.dashboard')->with('error', 'No enrollment application found for review.');
        }

        return view('student.enrollment_review', compact('enrollment'));
    }

    public function edit()
    {
        $user = Auth::user();
        $enrollment = Enrollment::where('user_id', Auth::id())->latest()->first();

        if (!$enrollment) {
            return redirect()->route('student.dashboard')->with('error', 'No enrollment found.');
        }

        $isOldStudent = Enrollment::where('user_id', Auth::id())->count() > 1 ||
            Enrollment::where('user_id', Auth::id())->whereNotNull('archived_at')->count() > 0 ||
            stripos($enrollment->year_level, 'Returning') !== false;

        if (in_array($enrollment->status, ['Enrolled', 'Paid']) && !$isOldStudent) {
            return redirect()->route('student.enrollment.review')->with('error', 'You cannot edit an application that has already been finalized/paid.');
        }

        $programs = Course::where('type', 'program')->orderBy('course_code', 'asc')->get();
        $strands = Course::where('type', 'shs')->orderBy('course_code', 'asc')->get();

        $activeSemester = Semester::where('is_active', true)->first();
        $activeYear = AcademicYear::where('is_active', true)->first();

        $data = $enrollment->toArray();
        $data['programs'] = $programs;
        $data['strands'] = $strands;

        return view('student.enrollment_edit', array_merge($data, [
            'enrollment' => $enrollment,
            'activeSemester' => $activeSemester,
            'activeYear' => $activeYear,
        ]));
    }

    public function update(Request $request)
    {
        $enrollment = Enrollment::where('user_id', Auth::id())->latest()->first();

        if (!$enrollment) {
            return redirect()->route('student.enrollment.review')->with('error', 'Unauthorized edit attempt.');
        }

        $level = $request->input('level', $enrollment->level);

        $validationRules = [
            'level' => 'required|in:shs,college',
            'course_code' => 'required',
            'year_level' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'birth_date' => 'required|date',
            'age' => 'required|numeric',
            'gender' => 'required',
            'contact' => 'required',
            'middle_name' => 'nullable|string|max:255',
            'extension' => 'nullable|string|max:50',
            'lrn' => 'nullable|string|max:12',
            'facebook_account' => 'nullable|string|max:255',
            'religion_church' => 'nullable|string|max:255',
            'junior_high_school' => 'nullable|string|max:255',
            'health_concerns' => 'nullable|string|max:1000',
            'prk_blk_lot_vill' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:10',
            'father_name' => 'nullable|string|max:255',
            'mother_maiden_name' => 'nullable|string|max:255',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:11',
        ];

        $request->validate($validationRules);

        $course = Course::where('course_code', $request->course_code)->first();

        if (!$course) {
            return back()->with('error', 'Selected program is invalid or not registered in the system.')->withInput();
        }

        // Auto-populate semester and academic year from active registrar settings
        $activeSemester = Semester::where('is_active', true)->first();
        $activeYear = AcademicYear::where('is_active', true)->first();
        $semesterName = $activeSemester ? $activeSemester->name : '';
        $academicYearName = $activeYear ? $activeYear->year_name : '';

        $unifiedYearLevel = "{$request->year_level} | {$semesterName} | {$academicYearName}";

        $address = $request->address_full;
        if (empty($address)) {
            $address = implode(', ', array_filter([$request->prk_blk_lot_vill, $request->barangay, $request->city, $request->province, $request->zip]));
        }

        $enrollment->update([
            'course_id' => $course->id,
            'course_code' => $request->course_code,
            'level' => $level,
            'year_level' => $unifiedYearLevel,
            'semester_name' => $semesterName,
            'academic_year_name' => $academicYearName,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'extension' => $request->extension,
            'lrn' => $request->lrn,
            'birth_date' => $request->birth_date,
            'age' => $request->age,
            'gender' => $request->gender,
            'email' => $request->email,
            'contact' => $request->contact,
            'facebook_account' => $request->facebook_account,
            'religion_church' => $request->religion_church,
            'junior_high_school' => $request->junior_high_school,
            'health_concerns' => $request->health_concerns,
            'prk_blk_lot_vill' => $request->prk_blk_lot_vill,
            'address_full' => $address ?: $enrollment->address_full,
            'father_name' => $request->father_name,
            'mother_maiden_name' => $request->mother_maiden_name,
            'guardian_name' => $request->guardian_name,
            'guardian_contact' => $request->guardian_contact,
        ]);

        return redirect()->route('student.enrollment.review')->with('success', 'Your application has been successfully updated and is pending review.');
    }
}
