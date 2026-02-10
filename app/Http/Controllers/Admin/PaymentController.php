<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Enrollment;

class PaymentController extends Controller
{
    public function index()
    {
        // Fetch real payments from the database and map to the structure expected by the view
        // Ensure we return Eloquent Models (not arrays) so the view can access relationships like ->application
        $payments = Payment::with(['user', 'application'])->latest()->paginate(10);
        $pendingCount = Enrollment::where('status', 'Pending')->count();

        return view('admin.payments.index', compact('payments', 'pendingCount'));
    }

    /**
     * Void (Delete) a payment record.
     */
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return back()->with('success', 'Payment record voided successfully.');
    }

    /**
     * Update payment status.
     */
    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        if ($request->has('status')) {
            $payment->status = $request->status;
            $payment->save();
        }

        return back()->with('success', 'Payment status updated successfully.');
    }
}
