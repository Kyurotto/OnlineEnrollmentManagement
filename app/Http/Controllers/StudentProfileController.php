<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Payment;

class StudentProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $payments = Payment::where('user_id', $user->id)
                    ->latest()
                    ->take(3)
                    ->get()
                    ->map(function ($record) {
                        return [
                            'amount' => $record->amount,
                            'date' => $record->created_at->format('M d, Y h:i A'),
                            'status' => $record->status,
                        ];
                    });

        return view('student.profile.index', compact('user', 'payments'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
        ]);

        return back()->with('profile-updated', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('password-updated', 'Password successfully updated.');
    }
}
