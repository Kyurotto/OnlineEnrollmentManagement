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
        $applications = Enrollment::with(['user'])
            ->whereNotIn('status', ['Dropped', 'Withdrawn'])
            ->latest()
            ->paginate(10);

        // 2. Notification Logic (Count strictly 'Pending' for badge count)
        $pendingCount = Enrollment::where('status', '=', 'Pending', 'and')->count();
        
        $notifications = Enrollment::whereIn('status', ['Pending', 'Enrolled'], 'and')
                            ->with('user')
                            ->orderBy('updated_at', 'desc')
                            ->take(5)
                            ->get();

        // Attach payment info for notifications
        foreach($notifications as $notif) {
            if($notif->status === 'Enrolled') {
                $payment = \App\Models\Payment::where('application_id', $notif->id)->first();
                $notif->paid_amount = $payment ? $payment->amount : 0;
            }
        }

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