<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;

class StudentPaymentManager extends Component
{
    public function render()
    {
        $user = Auth::user();

        // 1. Fetch payments for the logged-in student only
        $paymentRecords = Payment::where('user_id', $user->id)
                            ->latest()
                            ->get();

        // 2. Transform data to match your View's variable names
        $payments = $paymentRecords->map(function ($record) {
            return [
                'id'      => $record->id,
                'amount'  => $record->amount,
                'txn_id'  => 'TXN-' . str_pad($record->id, 6, '0', STR_PAD_LEFT), 
                'date'    => $record->created_at->format('M d, Y'),
                'status'  => $record->status,
            ];
        });

        return view('livewire.student.student-payment-manager', compact('payments'))->layout('components.layouts.student');
    }
}
