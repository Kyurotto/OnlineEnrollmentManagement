<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment; // Import your Enrollment model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Course;

class ApplicationController extends Controller
{
    public function index()
    {
        // 1. Fetch applications with Student (user)
        $applications = Enrollment::with(['user'])
            ->latest()
            ->paginate(10);

        // Manual Eager Load for 'course' based on course_code
        $courseCodes = $applications->pluck('course_code')->unique();
        $courses = Course::whereIn('course_code', $courseCodes)->get()->keyBy('course_code');

        foreach ($applications as $application) {
            if (isset($courses[$application->course_code])) {
                $application->setRelation('course', $courses[$application->course_code]);
            }
        }

        $pendingCount = Enrollment::where('status', 'Pending')->count();

        return view('admin.students.applications.index', compact('applications', 'pendingCount'));
    }

    public function show($id)
    {
        $application = Enrollment::with(['user'])->findOrFail($id);

        // Manual Load Course
        $course = Course::where('course_code', $application->course_code)->first();
        if ($course) {
            $application->setRelation('course', $course);
        }

        return view('admin.students.applications.show', compact('application'));
    }

    /**
     * Approve an application.
     */
    public function approve($id)
    {
        $enrollment = Enrollment::with('user')->findOrFail($id);
        $enrollment->update([
            'status' => 'Approved',
            'is_processed' => true
        ]);

        if ($enrollment->user) {
            $enrollment->user->update(['status' => 'Enrolled']);
        }

        // In a real app, you could send an email notification here.

        return back()->with('success', 'Application approved! Student is now Enrolled.');
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

        // 3. Update Application Status
        $application->update([
            'status' => $request->status,
            'is_processed' => true
        ]);

        // 4. Force Update Student Status
        // Now that "use App\Models\User;" is at the top, this will work:
        $student = User::find($application->user_id);

        if ($student) {
            if ($request->status === 'Approved') {
                $student->status = 'Enrolled';
            } elseif ($request->status === 'Rejected') {
                $student->status = 'Not Enrolled'; // Or 'Rejected'
            }
            $student->save(); // Force save
        }

        return back()->with('success', "Application successfully marked as {$request->status}.");
    }

    /**
     * Reject an application.
     */
    public function reject($id)
    {
        $enrollment = Enrollment::with('user')->findOrFail($id);
        $enrollment->update([
            'status' => 'Rejected',
            'is_processed' => true
        ]);

        if ($enrollment->user) {
            $enrollment->user->update(['status' => 'Rejected']);
        }

        // In a real app, you could send a rejection email here.

        return back()->with('success', 'Application #' . $id . ' has been rejected.');
    }

    /**
     * Delete an application.
     */
    public function destroy($id)
    {
        $application = Enrollment::findOrFail($id);
        $application->delete();

        return back()->with('success', 'Application deleted successfully.');
    }
}
