<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment; // Make sure you have this Model
use App\Models\User;

class CashierPaymentController extends Controller
{
    public function index(Request $request)
    {
        // 1. Start the query
        $query = Payment::with('user')->latest();

        // 2. Search Functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                  ->orWhere('amount', 'like', "%$search%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                  });
            });
        }

        // 3. Filter by Status
        if ($request->has('status') && $request->status != 'All statuses') {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate(10);

        return view('cashier.payments.index', compact('payments'));
    }

    // Function to update status (Completed/Pending)
    public function updateStatus(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->status = $request->status;
        $payment->save();

        return back()->with('success', 'Payment status updated!');
    }

    // Function to delete payment
    public function destroy($id)
    {
        Payment::findOrFail($id)->delete();
        return back()->with('success', 'Payment record deleted.');
    }
}
