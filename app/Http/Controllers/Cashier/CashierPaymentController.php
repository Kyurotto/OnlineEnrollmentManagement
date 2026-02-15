<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Enrollment; 

class CashierPaymentController extends Controller
{
    public function index(Request $request)
    {
        // 1. DATA QUERY
        $query = Payment::select('payments.*')
            ->leftJoin('enrollments', 'payments.application_id', '=', 'enrollments.id')
            ->leftJoin('users', 'payments.user_id', '=', 'users.id') 
            ->with(['user', 'application']);

        // 2. FILTER BY STATUS
        if ($request->has('status') && $request->status != 'All statuses') {
            $query->where('payments.status', $request->status);
        }

        // 3. FILTER BY PROGRAM (UPDATED LOGIC)
        if ($request->has('filter_course') && $request->filter_course != 'ALL') {
            $filter = $request->filter_course;

            // Check if filtering by specific Year (e.g., BSIS-1)
            if (str_contains($filter, '-')) {
                [$courseCode, $yearDigit] = explode('-', $filter);
                $suffix = match($yearDigit) { '1' => 'st', '2' => 'nd', '3' => 'rd', default => 'th' };
                $yearString = $yearDigit . $suffix . ' Year'; 
                
                $query->where('enrollments.course_code', $courseCode)
                      ->where('enrollments.year_level', 'like', $yearString . '%');
            } 
            // Filtering by General Program (e.g., ACT, DIT, BTVTED)
            else {
                $query->where('enrollments.course_code', $filter);
            }
        }

        // 4. SEARCH
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payments.id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%");
                  });
            });
        }

        // 5. SORTING (Optional: Keeps list organized)
        $query->latest('payments.created_at');

        $payments = $query->paginate(10);

        // Notification Count
        $pendingPaymentsCount = Payment::where('status', 'Pending')->count();

        return view('cashier.payments.index', compact('payments', 'pendingPaymentsCount'));
    }

    public function updateStatus(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        if ($request->has('amount')) {
            $payment->amount = $request->amount;
        }
        
        if ($request->has('status')) {
            $payment->status = $request->status;
            
            // Notify Registrar: Change Enrollment to 'Enrolled'
            if ($request->status === 'Completed' && $payment->application_id) {
                $enrollment = Enrollment::find($payment->application_id);
                if ($enrollment) {
                    $enrollment->update(['status' => 'Enrolled']);
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