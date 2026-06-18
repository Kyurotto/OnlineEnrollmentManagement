<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;
use App\Notifications\DocumentsVerifiedNotification;
use App\Notifications\EnrollmentCompletedNotification;
use Illuminate\Support\Facades\Notification;

class CashierDocumentController extends Controller
{
    /**
     * Display a listing of enrollments needing document verification.
     */
    public function index(Request $request)
    {
        $query = Enrollment::with('user')
            ->hasUploadsOrVerified();

        if ($request->has('status') && $request->status !== 'All') {
            if ($request->status === 'Verified') {
                $query->where('credentials_verified', true);
            } else {
                $query->where('credentials_verified', false);
            }
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $enrollments = $query->latest()->paginate(15);

        return view('cashier.documents.index', compact('enrollments'));
    }

    /**
     * Show the documents for a specific enrollment.
     */
    public function show($id)
    {
        $enrollment = Enrollment::with('user')->findOrFail($id);
        return view('cashier.documents.show', compact('enrollment'));
    }

    /**
     * Mark an enrollment's credentials as verified.
     */
    public function verify($id)
    {
        $enrollment = Enrollment::findOrFail($id);

        $enrollment->update([
            'credentials_verified' => true,
            'physical_documents_received' => true,
        ]);

        // Audit Log
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Verify Documents',
            'target_type' => Enrollment::class,
            'target_id' => $enrollment->id,
            'description' => "Verified documents for Student {$enrollment->user->name}",
        ]);

        // Notify Student
        $enrollment->user->notify(new DocumentsVerifiedNotification($enrollment->id));

        // If the student is a Transferee, this will resolve their "Transferee Credit Gap"
        if (method_exists($enrollment, 'runStatusAudit')) {
            $enrollment->runStatusAudit();
            $enrollment->save();
        }

        // If already paid, upgrade status to Enrolled
        if ($enrollment->status === 'Paid') {
            $enrollment->update(['status' => 'Enrolled']);

            // Audit Log for Status Change
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Enrollment Completed',
                'target_type' => Enrollment::class,
                'target_id' => $enrollment->id,
                'description' => "Student {$enrollment->user->name} is now formally Enrolled (Payment & Documents Verified)",
            ]);

            // Notify Student of Completion
            $enrollment->user->notify(new EnrollmentCompletedNotification($enrollment->id));
        }

        return back()->with('success', 'Documents verified successfully for ' . $enrollment->user->name);
    }

    /**
     * Mark an enrollment's credentials as unverified (e.g. if a document was found to be fake).
     */
    public function unverify($id)
    {
        $enrollment = Enrollment::findOrFail($id);

        $enrollment->update([
            'credentials_verified' => false,
        ]);

        // Audit Log
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Unverify Documents',
            'target_type' => Enrollment::class,
            'target_id' => $enrollment->id,
            'description' => "Revoked document verification for Student {$enrollment->user->name}",
        ]);

        if (method_exists($enrollment, 'runStatusAudit')) {
            $enrollment->runStatusAudit();
            $enrollment->save();
        }

        return back()->with('success', 'Credentials marked as unverified.');
    }
}
