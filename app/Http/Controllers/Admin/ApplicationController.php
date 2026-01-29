<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment; // Import your Enrollment model
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        // Get all applications, ordered by newest first
        $applications = Enrollment::latest()->get();

        return view('admin.applications.index', compact('applications'));
    }

    /**
     * Approve an application.
     */
    public function approve($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->update([
            'status' => 'Approved',
            'is_processed' => true
        ]);

        // In a real app, you could send an email notification here.

        return back()->with('success', 'Application approved! Student is now Enrolled.');
    }

    /**
     * Reject an application.
     */
    public function reject($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->update([
            'status' => 'Rejected',
            'is_processed' => true
        ]);

        // In a real app, you could send a rejection email here.

        return back()->with('success', 'Application #' . $id . ' has been rejected.');
    }

    /**
     * Delete an application.
     */
    public function destroy($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->delete();

        return back()->with('success', 'Application #' . $id . ' has been deleted.');
    }
}
