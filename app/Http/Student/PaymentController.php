<?php

namespace App\Http\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Fetch payments for the logged-in student only
        $paymentRecords = Payment::where('user_id', $user->id)
                            ->latest()
                            ->get();

        // 2. Transform data to match your View's variable names
        // Your view expects: ['amount', 'txn_id', 'date', 'status', 'id']
        $payments = $paymentRecords->map(function ($record) {
            return [
                'id'      => $record->id,
                'amount'  => $record->amount,
                // Generate a "Transaction ID" using the database ID (e.g., TXN-0001)
                'txn_id'  => 'TXN-' . str_pad($record->id, 6, '0', STR_PAD_LEFT), 
                'date'    => $record->created_at->format('M d, Y'), // Format date nicely
                'status'  => $record->status,
            ];
        });

        return view('student.payment', compact('payments'));
    }
}