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

        if ($payment->application_id) {
            Enrollment::where('id', $payment->application_id)->update(['status' => 'Paid']);
        }

        // --- NOTIFICATION LOGIC START ---
        // Since store() creates it as 'Paid', we notify immediately
        $staff = User::whereIn('role', ['registrar', 'admin'])->get();
        $student = User::find($payment->user_id);

        $recipients = $staff->push($student)->filter(); // Combine staff and student, remove nulls

        if($recipients->count() > 0){
            Notification::send($recipients, new StudentPaymentConfirmed($payment));
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

        // --- NOTIFICATION LOGIC START ---
        // Only notify if the status was changed to 'Paid'
        if ($request->status === 'Paid') {
            if ($payment->application_id) {
                Enrollment::where('id', $payment->application_id)->update(['status' => 'Paid']);
            }

            $staff = User::whereIn('role', ['registrar', 'admin'])->get();
            $student = User::find($payment->user_id);

            $recipients = $staff->push($student)->filter(); // Combine staff and student

            if($recipients->count() > 0){
                Notification::send($recipients, new StudentPaymentConfirmed($payment));
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
}
