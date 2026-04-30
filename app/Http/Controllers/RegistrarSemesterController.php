<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Semester;
use App\Models\AcademicYear; // Import is required!
use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class RegistrarSemesterController extends Controller
{
    public function index()
    {
        // 1. Fetch Semesters
        $semesters = Semester::orderBy('is_active', 'desc')
                             ->orderBy('id', 'desc')
                             ->paginate(10);
        
        // 2. Fetch Academic Years (THIS WAS MISSING causing the error)
        $academicYears = AcademicYear::orderBy('year_name', 'desc')->get();
                             
        return view('registrar.semesters.index', compact('semesters', 'academicYears'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_year' => 'required|string',
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Prevention of duplicates with Forbidden Error
        if (Semester::where('academic_year', $request->academic_year)
                   ->where('name', $request->name)
                   ->exists()) {
            abort(403, 'Forbidden: This Semester already exists for the selected Academic Year.');
        }

        $isActive = $request->has('is_active');

        // Logic: If setting to Active, auto-update Academic Year and perform reset
        if ($isActive) {
            $this->performSemesterReset();
            $this->activateSemesterAndYear($request->academic_year);
        }

        Semester::create([
            'academic_year' => $request->academic_year,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $isActive,
        ]);

        return redirect()->route('registrar.semesters.index')->with('success', 'Semester created successfully.');
    }

    public function update(Request $request, $id)
    {
        $semester = Semester::findOrFail($id);

        $request->validate([
            'academic_year' => 'required|string',
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $isActive = $request->has('is_active');
        $wasActive = $semester->is_active;

        // Only perform reset when switching FROM inactive TO active
        if ($isActive && !$wasActive) {
            $this->performSemesterReset();
            $this->activateSemesterAndYear($request->academic_year, $id);
        } elseif ($isActive) {
            $this->activateSemesterAndYear($request->academic_year, $id);
        }

        $semester->update([
            'academic_year' => $request->academic_year,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $isActive,
        ]);

        return redirect()->route('registrar.semesters.index')->with('success', 'Semester updated successfully.');
    }

    public function destroy($id)
    {
        Semester::findOrFail($id)->delete();
        return redirect()->route('registrar.semesters.index')->with('success', 'Semester deleted successfully.');
    }

    // --- Custom Activate Function (Button Click) ---
    public function activate($id)
    {
        $semester = Semester::findOrFail($id);
        
        // Perform the full semester reset before activating
        $this->performSemesterReset();

        // Call helper to activate this semester and its year
        $this->activateSemesterAndYear($semester->academic_year, $id);

        // Explicitly update this semester to active (redundancy check)
        $semester->update(['is_active' => true]);

        return redirect()->route('registrar.semesters.index')->with('success', "Semester activated: {$semester->academic_year} - {$semester->name}. Enrollment cycle has been reset.");
    }

    /**
     * Perform the full semester reset when a new semester is activated.
     * 
     * This method:
     * 1. Archives enrolled students from the current/previous active term
     * 2. Deletes non-enrolled application records (Pending/Approved/Rejected)
     * 3. Calculates and stores previous_balance for returning students
     */
    private function performSemesterReset()
    {
        // 1. Find ALL currently active (non-archived) enrollments
        // This ensures that even if the Year was switched first, we still catch all previous term records.
        $currentTermEnrollments = Enrollment::whereNull('archived_at')->get();
        
        // We still want to label them with the term they are being archived FROM
        // But since the year might have already switched, we should try to get the 
        // term info from the enrollments themselves if the current active term is already new.
        $currentYear = AcademicYear::where('is_active', true)->first();
        $currentSemester = Semester::where('is_active', true)->first();

        $yearName = $currentYear ? $currentYear->year_name : 'N/A';
        $semesterName = $currentSemester ? $currentSemester->name : 'N/A';

        // 2. Process enrolled students — archive them and carry forward balance
        $enrolledRecords = $currentTermEnrollments->where('status', 'Enrolled');
        
        foreach ($enrolledRecords as $enrollment) {
            // Calculate remaining balance for this student
            $previousBalance = $this->calculateRemainingBalance($enrollment);
            
            // Archive the enrollment record
            $enrollment->update([
                'archived_at' => now(),
                'semester_name' => $semesterName,
                'academic_year_name' => $yearName,
                'physical_documents_received' => 0,
                'credentials_verified' => 0,
            ]);

            // Store the previous balance on the user level for the next enrollment
            // We'll use cache to temporarily hold this until the student re-enrolls
            if ($previousBalance > 0) {
                Cache::put("student_previous_balance_{$enrollment->user_id}", $previousBalance, now()->addMonths(6));
            }
        }

        // 3. Process non-enrolled students — delete their application records
        $nonEnrolledRecords = $currentTermEnrollments->whereIn('status', ['Pending', 'Approved', 'Rejected']);
        
        foreach ($nonEnrolledRecords as $enrollment) {
            // Calculate any balance that might exist (e.g., partial payments on approved apps)
            $previousBalance = $this->calculateRemainingBalance($enrollment);
            
            if ($previousBalance > 0) {
                Cache::put("student_previous_balance_{$enrollment->user_id}", $previousBalance, now()->addMonths(6));
            }
            
            // Archive the non-enrolled application record instead of deleting it
            $enrollment->update([
                'archived_at' => now(),
                'semester_name' => $semesterName,
                'academic_year_name' => $yearName,
                'physical_documents_received' => 0,
                'credentials_verified' => 0,
            ]);
        }

        // 4. Also handle "Paid" status records — these are approved+paid but not yet fully enrolled
        $paidRecords = $currentTermEnrollments->where('status', 'Paid');
        
        foreach ($paidRecords as $enrollment) {
            $previousBalance = $this->calculateRemainingBalance($enrollment);
            
            $enrollment->update([
                'archived_at' => now(),
                'semester_name' => $semesterName,
                'academic_year_name' => $yearName,
                'physical_documents_received' => 0,
                'credentials_verified' => 0,
            ]);

            if ($previousBalance > 0) {
                Cache::put("student_previous_balance_{$enrollment->user_id}", $previousBalance, now()->addMonths(6));
            }
        }
    }

    /**
     * Calculate the remaining unpaid balance for an enrollment.
     */
    private function calculateRemainingBalance(Enrollment $enrollment): float
    {
        // Get the assessment for this enrollment's level
        $level = $enrollment->getLevel();
        $cacheKey = 'payment_assessment_' . $level;
        $assessment = Cache::get($cacheKey, [
            'tuitionFee' => 0,
            'miscellaneousFees' => 0,
        ]);

        $totalAssessment = ($assessment['tuitionFee'] ?? 0) + ($assessment['miscellaneousFees'] ?? 0);

        // Apply voucher discount
        $discount = 0;
        if ($enrollment->voucher_type === 'free_tuition') {
            $discount = $assessment['tuitionFee'] ?? 0;
        } elseif ($enrollment->voucher_type === 'discounted') {
            $discount = $totalAssessment * 0.15;
        }

        // Add any existing previous_balance from prior terms
        $priorBalance = $enrollment->previous_balance ?? 0;

        // Get total payments made
        $totalPaid = Payment::where('user_id', $enrollment->user_id)
            ->where('application_id', $enrollment->id)
            ->where('status', 'Paid')
            ->sum('amount');

        return max(0, ($totalAssessment - $discount + $priorBalance) - $totalPaid);
    }

    // Helper function to keep code clean
    private function activateSemesterAndYear($academicYearName, $excludeSemesterId = null)
    {
        // 1. Deactivate ALL Semesters
        Semester::query()->update(['is_active' => false]);

        // 2. Deactivate ALL Academic Years
        AcademicYear::query()->update(['is_active' => false]);

        // 3. Activate the specific Academic Year
        AcademicYear::where('year_name', $academicYearName)->update(['is_active' => true]);
        
        // (Note: The specific Semester gets activated in the calling function)
    }
}