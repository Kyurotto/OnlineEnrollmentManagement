<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\Payment;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\StudentPaymentConfirmed;

#[Layout('components.layouts.cashier')]
class CashierDashboardManager extends Component
{
    public function render()
    {
        // 1. Calculate Stats
        $stats = [
            'daily_collection'   => Payment::where('status', 'Paid')->whereDate('payment_date', Carbon::today())->sum('amount'),
            'transactions_today'  => Payment::where('status', 'Paid')->whereDate('payment_date', Carbon::today())->count(),
            'pending_verifications' => Enrollment::whereIn('status', ['Pending', 'Approved'])->count('*'),
        ];

        // 2. Fetch Payments for Today (Paid today OR Pending from today)
        $paymentsToday = Payment::with('user')
            ->where(function($q) {
                $q->where('status', 'Paid')->whereDate('payment_date', Carbon::today())
                  ->orWhere('status', 'Pending')->whereDate('created_at', Carbon::today());
            })
            ->latest('updated_at')
            ->get();

        // 3. Fetch Payments for Yesterday (Paid yesterday OR Pending from yesterday)
        $paymentsYesterday = Payment::with('user')
            ->where(function($q) {
                $q->where('status', 'Paid')->whereDate('payment_date', Carbon::yesterday())
                  ->orWhere('status', 'Pending')->whereDate('created_at', Carbon::yesterday());
            })
            ->latest('updated_at')
            ->get();

        return view('dashboard', compact('stats', 'paymentsToday', 'paymentsYesterday'));
    }
}
