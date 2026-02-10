<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment; // Assuming your model is Enrollment
use App\Models\User;
use App\Models\Course;

class ApplicationController extends Controller
{
    /**
     * Display the list of applications (The First HTML Page).
     */
    public function index()
    {
        // 1. Fetch applications with Student (user) and Course details
        // We use 'with' to prevent "Attempt to read property on null" errors
        $applications = Enrollment::with(['user'])
            ->latest() // Show newest first
            ->paginate(10);

        // Manual Eager Load for 'course' based on course_code
        $courseCodes = $applications->pluck('course_code')->unique();
        $courses = Course::whereIn('course_code', $courseCodes)->get()->keyBy('course_code');

        foreach ($applications as $application) {
            if (isset($courses[$application->course_code])) {
                $application->setRelation('course', $courses[$application->course_code]);
            }
        }

        // 2. Count pending applications for the header badge
        $pendingCount = Enrollment::where('status', 'Pending')->count();

        return view('registrar.students.applications.index', compact('applications', 'pendingCount'));
    }

    /**
     * Display the specific review page (The Second HTML Page).
     */
    public function show($id)
    {
        // Fetch the specific enrollment with relationships
        $application = Enrollment::with(['user'])->findOrFail($id);

        // Manual Load Course
        $course = Course::where('course_code', $application->course_code)->first();
        if ($course) {
            $application->setRelation('course', $course);
        }

        return view('registrar.students.applications.show', compact('application'));
    }

    /**
     * Handle the Approve/Reject buttons.
     */
    public function update(Request $request, $id)
    {
        $application = Enrollment::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Approved,Rejected,Pending',
        ]);

        $application->status = $request->status;
        $application->save();

        // Redirect back to the list with a success message
        return redirect()->route('registrar.applications.index')
            ->with('success', 'Application status updated to ' . $request->status . '.');
    }

    /**
     * Delete an application (if needed).
     */
    public function destroy($id)
    {
        $application = Enrollment::findOrFail($id);
        $application->delete();

        return back()->with('success', 'Application record deleted.');
    }

    /**
     * Helper to map enrollment fields to user object for view compatibility.
     */
    private function mapEnrollmentDataToUser($application)
    {
        $application->user->age = $application->age;
        $application->user->birth_date = $application->birth_date;
        $application->user->date_of_birth = $application->birth_date;
        $application->user->gender = $application->gender;
        $application->user->address = $application->address_full;
        $application->user->father_name = $application->father_name;
        $application->user->mother_maiden_name = $application->mother_maiden_name;
        $application->user->guardian_name = $application->guardian_name;
        $application->user->guardian_contact = $application->guardian_contact;
        $application->user->contact = $application->contact;
    }
}
