<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

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
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $payments = $query->paginate(10);

        // --- NEW: Calculate Pending Count for Notification Bell ---
        $pendingPaymentsCount = Payment::where('status', 'Pending')->count();

        // Pass 'pendingPaymentsCount' to the view
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
