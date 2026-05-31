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
        // 1. Calculate Stats (Filtered by Active/Non-Archived Term)
        $stats = [
            'daily_collection'    => Payment::where('status', 'Paid')
                ->whereDate('payment_date', Carbon::today())
                ->whereHas('application', function($query) {
                    $query->whereNull('archived_at');
                })
                ->sum('amount'),

            'transactions_today'  => Payment::where('status', 'Paid')
                ->whereDate('payment_date', Carbon::today())
                ->whereHas('application', function($query) {
                    $query->whereNull('archived_at');
                })
                ->count(),

            'students_paid_today' => Payment::where('status', 'Paid')
                ->whereDate('payment_date', Carbon::today())
                ->whereHas('application', function($query) {
                    $query->whereNull('archived_at');
                })
                ->distinct('user_id')
                ->count('user_id'),
        ];

        // 2. Fetch Payments for Today (Paid only) - Filtered by Active Term
        $paymentsToday = Payment::with('user')
            ->where('status', 'Paid')
            ->whereDate('payment_date', Carbon::today())
            ->whereHas('application', function($query) {
                $query->whereNull('archived_at');
            })
            ->latest('updated_at')
            ->take(10)
            ->get();

        // 3. Fetch Payments for Yesterday (Paid only) - Filtered by Active Term
        $paymentsYesterday = Payment::with('user')
            ->where('status', 'Paid')
            ->whereDate('payment_date', Carbon::yesterday())
            ->whereHas('application', function($query) {
                $query->whereNull('archived_at');
            })
            ->latest('updated_at')
            ->take(10)
            ->get();

        return view('cashier.dashboard', compact('stats', 'paymentsToday', 'paymentsYesterday'));
    }
}
