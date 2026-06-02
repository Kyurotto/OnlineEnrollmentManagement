<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'amount'       => 'required|numeric|min:1',
            'payment_type' => 'required|string',
            'reference_no' => 'nullable|string|max:255',
        ]);

        // Get the student's latest valid enrollment to link the payment
        $enrollment = \App\Models\Enrollment::where('user_id', Auth::id())
            ->whereNotNull('year_level')
            ->latest()
            ->first();

        Payment::create([
            'user_id'        => Auth::id(),
            'application_id' => $enrollment ? $enrollment->id : null,
            'amount'         => $request->amount,
            'status'         => 'Pending',
            'payment_method' => $request->payment_type,
            'transaction_id' => $request->reference_no ?? ('TXN-' . strtoupper(\Illuminate\Support\Str::random(10))),
            'payment_date'   => now(),
        ]);

        return redirect()->route('student.dashboard')->with('success', 'Payment recorded successfully!');
    }
}
