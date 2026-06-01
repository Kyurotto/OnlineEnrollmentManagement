<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Carbon\Carbon;

class CashierDashboardController extends Controller
{
    public function index()
    {
        // 1. Calculate Stats
        // Only count payments that are explicitly 'Paid' (Approved)
        $dailyCollection = Payment::where('status', 'Paid')
            ->whereDate('updated_at', Carbon::today())
            ->sum('amount');

        $transactionsToday = Payment::where('status', 'Paid')
            ->whereDate('updated_at', Carbon::today())
            ->count();

        $stats = [
            'daily_collection' => $dailyCollection,
            'transactions_today' => $transactionsToday,
        ];

        // 2. Fetch Payments for Today
        $paymentsToday = Payment::with('user')
            ->whereDate('updated_at', Carbon::today())
            ->latest()
            ->get();

        // 3. Fetch Payments for Yesterday
        $paymentsYesterday = Payment::with('user')
            ->whereDate('updated_at', Carbon::yesterday())
            ->latest()
            ->get();

        return view('cashier.dashboard', compact('stats', 'paymentsToday', 'paymentsYesterday'));
    }
}