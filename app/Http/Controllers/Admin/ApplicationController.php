<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Course;

class ApplicationController extends Controller
{
    public function index()
    {
        // 1. Fetch applications
        $applications = Enrollment::with(['user'])
            ->latest()
            ->paginate(10);

        // 2. Manual Eager Load for 'course' based on course_code
        // (This preserves your logic of linking via course_code string)
        $courseCodes = $applications->pluck('course_code')->unique();
        $courses = Course::whereIn('course_code', $courseCodes)->get()->keyBy('course_code');

        foreach ($applications as $application) {
            if (isset($courses[$application->course_code])) {
                $application->setRelation('course', $courses[$application->course_code]);
            }
        }

        $pendingCount = Enrollment::where('status', 'Pending')->count();

        // FIX: Pointing to the correct view path based on your structure
        return view('admin.applications.index', compact('applications', 'pendingCount'));
    }

    public function show($id)
    {
        $application = Enrollment::with(['user'])->findOrFail($id);

        // Manual Load Course
        $course = Course::where('course_code', $application->course_code)->first();
        if ($course) {
            $application->setRelation('course', $course);
        }

        // FIX: Correct view path
        return view('admin.applications.show', compact('application'));
    }

    /**
     * Approve an application.
     */
    public function approve($id)
    {
        $enrollment = Enrollment::with('user')->findOrFail($id);
        
        // *** FIX: Set status to 'Enrolled' so the Dashboard counts it ***
        $enrollment->update([
            'status' => 'Enrolled', 
            'is_processed' => true
        ]);

        // Sync User Status
        if ($enrollment->user) {
            $enrollment->user->update(['status' => 'Enrolled']);
        }

        return back()->with('success', 'Application approved! Student is now officially Enrolled.');
    }

    /**
     * Unified Update Function
     * Handles "Approve" and "Reject"
     */
    public function update(Request $request, $id)
    {
        // 1. Find Application
        $application = Enrollment::findOrFail($id);

        // 2. Validate
        $request->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);

        // 3. Determine Status to Save
        // If Admin clicks "Approve", we save "Enrolled" to DB so the dashboard counts it.
        $statusToSave = $request->status;
        if ($statusToSave === 'Approved') {
            $statusToSave = 'Enrolled';
        }

        // 4. Update Application Status
        $application->update([
            'status' => $statusToSave,
            'is_processed' => true
        ]);

        // 5. Force Update Student User Table Status
        $student = User::find($application->user_id);

        if ($student) {
            if ($statusToSave === 'Enrolled') {
                $student->status = 'Enrolled';
            } elseif ($statusToSave === 'Rejected') {
                $student->status = 'Not Enrolled';
            }
            $student->save(); 
        }

        return back()->with('success', "Application successfully marked as {$statusToSave}.");
    }

    /**
     * Reject an application.
     */
}