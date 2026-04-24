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
        ];

        // 2. Fetch Payments for Today (Paid only)
        $paymentsToday = Payment::with('user')
            ->where('status', 'Paid')
            ->whereDate('payment_date', Carbon::today())
            ->latest('updated_at')
            ->get();

        // 3. Fetch Payments for Yesterday (Paid only)
        $paymentsYesterday = Payment::with('user')
            ->where('status', 'Paid')
            ->whereDate('payment_date', Carbon::yesterday())
            ->latest('updated_at')
            ->get();

        return view('dashboard', compact('stats', 'paymentsToday', 'paymentsYesterday'));
    }
}
