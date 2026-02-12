<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Enrollment; // 1. IMPORT ENROLLMENT MODEL

class CashierPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['user', 'application'])->latest();

        // Filter by Status
        if ($request->has('status') && $request->status != 'All statuses') {
            $query->where('status', $request->status);
        }

        // Search Logic
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  // Removed 'username' if your User table doesn't have it, relying on name/email is safer
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $payments = $query->paginate(10);

        // Calculate Pending Count for Notification Bell
        $pendingPaymentsCount = Payment::where('status', 'Pending')->count();

        return view('cashier.payments.index', compact('payments', 'pendingPaymentsCount'));
    }

    public function updateStatus(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        if ($request->has('amount')) {
            $payment->amount = $request->amount;
        }
        
        // 2. STATUS UPDATE LOGIC
        if ($request->has('status')) {
            $payment->status = $request->status;
            
            // *** FIX: AUTO-ENROLL STUDENT WHEN PAID ***
            // If the Cashier marks payment as 'Completed', set the student status to 'Enrolled'
            if ($request->status === 'Completed' && $payment->application_id) {
                $enrollment = Enrollment::find($payment->application_id);
                if ($enrollment) {
                    $enrollment->update(['status' => 'Enrolled']);
                    
                    // Also update the User table if you have a status there
                    if ($enrollment->user) {
                        $enrollment->user->update(['status' => 'Enrolled']);
                    }
                }
            }
        }

        $payment->save();
        return back()->with('success', 'Payment updated successfully.');
    }

    public function destroy($id)
    {
        Payment::findOrFail($id)->delete();
        return back()->with('success', 'Payment record deleted.');
    }
}