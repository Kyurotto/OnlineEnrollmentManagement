<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;
use App\Models\Enrollment;
use App\Notifications\StudentPaymentConfirmed; // Import the Notification
use Illuminate\Support\Facades\Notification;   // Import Notification Facade

class CashierPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::select('payments.*')
            ->leftJoin('enrollments', 'payments.application_id', '=', 'enrollments.id')
            ->leftJoin('users', 'payments.user_id', '=', 'users.id')
            ->with(['user', 'application']);

        if ($request->has('status') && $request->status != 'All statuses') {
            $query->where('payments.status', $request->status);
        }

        if ($request->has('filter_course') && $request->filter_course != 'ALL') {
            $filter = $request->filter_course;
            if (str_contains($filter, '-')) {
                $parts = explode('-', $filter);
                if(count($parts) >= 2) {
                    $courseCode = $parts[0];
                    $yearDigit = $parts[1];
                    $suffix = match($yearDigit) { '1' => 'st', '2' => 'nd', '3' => 'rd', default => 'th' };
                    $yearString = $yearDigit . $suffix . ' Year';
                    $query->where('enrollments.course_code', $courseCode)
                          ->where('enrollments.year_level', 'like', $yearString . '%');
                }
            } else {
                $query->where('enrollments.course_code', $filter);
            }
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payments.id', 'like', "%{$search}%")
                  ->orWhere('payments.transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Order by ID Descending to show newest first
        $payments = $query->orderBy('payments.id', 'desc')->paginate(10);

        $pendingPaymentsCount = Payment::where('status', 'Pending')->count();
        $students = User::where('role', 'student')->orderBy('name')->get();

        return view('cashier.payments.index', compact('payments', 'pendingPaymentsCount', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'reference_no' => 'nullable|string',
            'payment_type' => 'required|string',
        ]);

        $latestEnrollment = Enrollment::where('user_id', $request->user_id)->latest()->first();

        $payment = Payment::create([
            'user_id' => $request->user_id,
            'application_id' => $latestEnrollment ? $latestEnrollment->id : null,
            'amount' => $request->amount,
            'transaction_id' => $request->reference_no ?? 'CASH-' . time(),
            'status' => 'Paid',
            'payment_method' => $request->payment_type,
            'payment_date' => now(),
        ]);

        // Audit Log
        \App\Models\ActivityLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'Record Payment',
            'target_type' => \App\Models\Payment::class,
            'target_id' => $payment->id,
            'description' => "Recorded new payment of ₱" . number_format($request->amount, 2) . " for Student " . \App\Models\User::find($request->user_id)->name,
        ]);

        if ($payment->application_id) {
            $enrollment = Enrollment::find($payment->application_id);
            $status = $enrollment->credentials_verified ? 'Enrolled' : 'Paid';
            $enrollment->update(['status' => $status]);

            if ($status === 'Enrolled') {
                // Audit Log for Final Enrollment
                \App\Models\ActivityLog::create([
                    'user_id' => \Illuminate\Support\Facades\Auth::id(),
                    'action' => 'Enrollment Completed',
                    'target_type' => \App\Models\Enrollment::class,
                    'target_id' => $enrollment->id,
                    'description' => "Student {$enrollment->user->name} is now formally Enrolled (Payment & Documents Verified)",
                ]);
                // Notify Student
                $enrollment->user->notify(new \App\Notifications\EnrollmentCompletedNotification($enrollment->id));
            }
        }

        // --- NOTIFICATION LOGIC START ---
        $student = User::find($payment->user_id);
        if($student){
            $student->notify(new StudentPaymentConfirmed($payment));
        }
        // --- NOTIFICATION LOGIC END ---

        return back()->with('success', 'Payment of ₱' . number_format($request->amount, 2) . ' processed successfully.');
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_type' => 'required|string',
            'reference_no' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $payment->update([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_type,
            'transaction_id' => $request->reference_no,
        ]);

        // Audit Log
        \App\Models\ActivityLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'Update Payment',
            'target_type' => \App\Models\Payment::class,
            'target_id' => $payment->id,
            'description' => "Modified payment details for Student " . $payment->user->name,
        ]);

        return back()->with('success', 'Payment details updated successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $request->validate(['status' => 'required|in:Paid,Rejected']);

        $payment->update([
            'status' => $request->status,
            'payment_date' => $request->status === 'Paid' ? now() : $payment->payment_date
        ]);

        // Audit Log
        \App\Models\ActivityLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'Update Payment Status',
            'target_type' => \App\Models\Payment::class,
            'target_id' => $payment->id,
            'description' => "Changed payment status to {$request->status} for Student " . $payment->user->name,
        ]);

        // --- NOTIFICATION LOGIC START ---
        if ($request->status === 'Paid') {
            if ($payment->application_id) {
                $enrollment = Enrollment::find($payment->application_id);
                $status = $enrollment->credentials_verified ? 'Enrolled' : 'Paid';
                $enrollment->update(['status' => $status]);

                if ($status === 'Enrolled') {
                    // Audit Log for Final Enrollment
                    \App\Models\ActivityLog::create([
                        'user_id' => \Illuminate\Support\Facades\Auth::id(),
                        'action' => 'Enrollment Completed',
                        'target_type' => \App\Models\Enrollment::class,
                        'target_id' => $enrollment->id,
                        'description' => "Student {$enrollment->user->name} is now formally Enrolled (Payment & Documents Verified)",
                    ]);
                    // Notify Student
                    $enrollment->user->notify(new \App\Notifications\EnrollmentCompletedNotification($enrollment->id));
                }
            }

            $student = User::find($payment->user_id);
            if($student){
                $student->notify(new StudentPaymentConfirmed($payment));
            }
        }
        // --- NOTIFICATION LOGIC END ---

        return back()->with('success', 'Payment status updated to ' . $request->status);
    }

    public function destroy($id)
    {
        Payment::findOrFail($id)->delete();
        return back()->with('success', 'Payment record deleted.');
    }

    public function export(Request $request)
    {
        $level = $request->query('level', 'college');
        $shsStrands = ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT'];

        $query = Payment::select('payments.*')
            ->leftJoin('enrollments', 'payments.application_id', '=', 'enrollments.id')
            ->with(['user', 'application'])
            ->where('payments.status', 'Paid');

        if ($level === 'shs') {
            $query->whereIn('enrollments.course_code', $shsStrands);
        } else {
            $query->whereNotIn('enrollments.course_code', $shsStrands);
        }

        $payments = $query->orderBy('payments.payment_date', 'desc')->get();

        $csv = [];
        $csv[] = ['Date', 'Receipt #', 'Student Name', 'Program/Strand', 'Amount', 'Method'];

        foreach ($payments as $payment) {
            $studentName = $payment->user ? $payment->user->last_name . ', ' . $payment->user->first_name : 'N/A';
            $program = $payment->application ? $payment->application->course_code : 'N/A';
            $csv[] = [
                $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') : $payment->created_at->format('Y-m-d'),
                $payment->transaction_id,
                $studentName,
                $program,
                number_format($payment->amount, 2),
                $payment->payment_method
            ];
        }

        $filename = "payment_backup_{$level}_" . now()->format('Ymd_His') . ".csv";
        $handle = fopen('php://temp', 'r+');
        foreach ($csv as $row) { fputcsv($handle, $row); }
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response()->streamDownload(function () use ($content) { echo $content; }, $filename);
    }
}
