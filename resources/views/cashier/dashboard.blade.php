<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-slate-800 flex flex-col min-h-screen">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="bg-emerald-700 text-white font-bold p-2 rounded-lg text-sm">CS</div>
                    <div>
                        <h1 class="text-lg font-bold leading-none text-slate-900">Cashier Panel</h1>
                        <span class="text-xs text-gray-500">Finance & Collections</span>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="text-right hidden sm:block">
                        <div class="text-xs text-gray-400">Signed in as</div>
                        <div class="text-sm font-bold text-slate-800">Cashier</div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2 px-4 rounded shadow transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full space-y-8">
        
        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 mb-1">Overview</h2>
                <p class="text-gray-600">Here is your daily financial summary.</p>
            </div>
            <a href="{{ route('cashier.payments.index') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Process New Payment
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wide">Collected Today</h3>
                    <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-emerald-700">
                    ₱{{ number_format($stats['daily_collection'], 2) }}
                </div>
                <p class="text-xs text-gray-400 mt-2">Total confirmed payments today.</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wide">Transactions</h3>
                    <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800">
                    {{ $stats['transactions_today'] }}
                </div>
                <p class="text-xs text-gray-400 mt-2">Receipts generated today.</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wide">Pending Enrollees</h3>
                    <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-slate-800">
                    {{ $stats['pending_approvals'] }}
                </div>
                <p class="text-xs text-gray-400 mt-2">Waiting for assessment.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-emerald-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-emerald-100 flex justify-between items-center bg-emerald-50">
                <h3 class="font-bold text-emerald-900 flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    Payments Received Today
                </h3>
                <span class="text-xs font-bold text-emerald-600 bg-white px-3 py-1 rounded-full border border-emerald-200">
                    {{ \Carbon\Carbon::today()->format('F d, Y') }}
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs text-gray-400 border-b border-gray-100 bg-white">
                            <th class="px-6 py-3 font-medium uppercase">Receipt #</th>
                            <th class="px-6 py-3 font-medium uppercase">Student Name</th>
                            <th class="px-6 py-3 font-medium uppercase">Time</th>
                            <th class="px-6 py-3 font-medium uppercase text-right">Amount</th>
                            <th class="px-6 py-3 font-medium uppercase text-center">Method</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($paymentsToday as $payment)
                        <tr class="border-b border-gray-50 hover:bg-emerald-50 transition">
                            <td class="px-6 py-4 font-mono text-gray-500">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4 font-bold text-slate-700">
                                {{ optional($payment->user)->name ?? 'Unknown Student' }}
                            </td>
                            <td class="px-6 py-4 text-emerald-600 font-medium">
                                {{ $payment->created_at->format('h:i A') }}
                            </td>
                            <td class="px-6 py-4 font-bold text-emerald-700 text-right">
                                ₱{{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center text-xs text-gray-500">
                                {{ $payment->payment_method ?? 'Cash' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400">No payments recorded today yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden opacity-90">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-slate-600">Yesterday's Payments</h3>
                <span class="text-xs font-medium text-gray-400">
                    {{ \Carbon\Carbon::yesterday()->format('F d, Y') }}
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs text-gray-400 border-b border-gray-100 bg-white">
                            <th class="px-6 py-3 font-medium uppercase">Receipt #</th>
                            <th class="px-6 py-3 font-medium uppercase">Student Name</th>
                            <th class="px-6 py-3 font-medium uppercase">Time</th>
                            <th class="px-6 py-3 font-medium uppercase text-right">Amount</th>
                            <th class="px-6 py-3 font-medium uppercase text-center">Method</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($paymentsYesterday as $payment)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-mono text-gray-400">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4 font-medium text-slate-600">
                                {{ optional($payment->user)->name ?? 'Unknown Student' }}
                            </td>
                            <td class="px-6 py-4 text-gray-400">
                                {{ $payment->created_at->format('h:i A') }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-600 text-right">
                                ₱{{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center text-xs text-gray-400">
                                {{ $payment->payment_method ?? 'Cash' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-gray-400 text-xs">No payments found for yesterday.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
            © 2026 Your Institution — Cashier Panel
        </div>
    </footer>

</body>
</html>