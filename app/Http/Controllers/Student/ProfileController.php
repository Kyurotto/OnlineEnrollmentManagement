<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;

class ProfileController extends Controller
{
    /**
     * Display the student's profile form.
     */
    public function edit(Request $request)
    {
        $user = Auth::user();

        // Fetch payments for the activity section in the profile view
        $paymentRecords = Payment::where('user_id', $user->id)
                            ->latest()
                            ->take(5)
                            ->get();

        $payments = $paymentRecords->map(function ($record) {
            return [
                'id'      => $record->id,
                'amount'  => $record->amount,
                'date'    => $record->created_at->format('Y-m-d H:i:s'),
                'status'  => $record->status,
            ];
        });

        return view('student.profile', compact('payments'));
    }
}
