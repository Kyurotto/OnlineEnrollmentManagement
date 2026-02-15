<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Course;

class ApplicationController extends Controller
{
    public function index()
    {
        // 1. Fetch main list for the table
        $applications = Enrollment::with(['user'])->latest()->paginate(10);

        // 2. Notification Logic (Fetch 'Pending' for badge count, 'Enrolled' for Paid alerts)
        $pendingCount = Enrollment::where('status', 'Pending')->count();
        
        $notifications = Enrollment::whereIn('status', ['Pending', 'Enrolled'])
                            ->with('user')
                            ->orderBy('updated_at', 'desc')
                            ->take(5)
                            ->get();

        return view('admin.applications.index', compact('applications', 'pendingCount', 'notifications'));
    }

    public function update(Request $request, $id)
    {
        $application = Enrollment::findOrFail($id);
        $application->status = $request->status;
        $application->save();
        return back()->with('success', 'Status updated.');
    }

    public function destroy($id)
    {
        Enrollment::findOrFail($id)->delete();
        return back()->with('success', 'Record deleted.');
    }
}