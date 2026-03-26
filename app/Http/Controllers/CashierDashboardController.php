<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Enrollment;
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
            
        $pendingApprovals = Enrollment::where('status', 'Pending')->count();

        $stats = [
            'daily_collection' => $dailyCollection,
            'transactions_today' => $transactionsToday,
            'pending_approvals' => $pendingApprovals,
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

        return view('dashboard', compact('stats', 'paymentsToday', 'paymentsYesterday'));
    }
}