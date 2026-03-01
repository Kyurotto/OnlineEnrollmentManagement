<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Inter', sans-serif; } 
        .table-container { min-height: 300px; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #3F3F46; border-radius: 4px; }
    </style>
</head>
<body class="bg-[#121212] text-[#A1A1AA] flex flex-col min-h-screen">

    <nav class="bg-[#1C1C1E] border-b border-[#27272A] sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <div class="bg-[#10B981] text-white font-bold p-2 rounded-lg text-sm shadow-md shadow-[#10B981]/20">CS</div>
                    <div>
                        <h1 class="text-lg font-bold leading-none text-[#FFFFFF]">Cashier Panel</h1>
                        <span class="text-xs text-[#52525B]">Finance & Collections</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="bg-red-500 hover:bg-[#3F3F46] text-white text-sm font-semibold py-2 px-4 rounded shadow transition-colors">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full space-y-8">
        
        <div class="bg-[#1C1C1E] p-8 rounded-xl shadow-md border border-[#27272A] flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-[#FFFFFF] mb-1">Overview</h2>
                <p class="text-[#A1A1AA]">Here is your daily financial summary.</p>
            </div>
            <a href="{{ route('cashier.payments.index') }}" class="bg-[#10B981] hover:bg-[#059669] text-white font-bold py-3 px-6 rounded-lg shadow-md shadow-[#10B981]/10 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Process New Payment
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-[#1C1C1E] p-6 rounded-xl shadow-md border border-[#27272A]">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-[#52525B] uppercase tracking-wide">Collected Today</h3>
                    <div class="p-2 bg-[#10B981]/10 rounded-lg text-[#10B981]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-[#10B981]">
                    ₱{{ number_format($stats['daily_collection'], 2) }}
                </div>
                <p class="text-xs text-[#52525B] mt-2">Total confirmed payments today.</p>
            </div>

            <div class="bg-[#1C1C1E] p-6 rounded-xl shadow-md border border-[#27272A]">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-[#52525B] uppercase tracking-wide">Transactions</h3>
                    <div class="p-2 bg-blue-500/10 rounded-lg text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-[#FFFFFF]">
                    {{ $stats['transactions_today'] }}
                </div>
                <p class="text-xs text-[#52525B] mt-2">Receipts generated today.</p>
            </div>

            <div class="bg-[#1C1C1E] p-6 rounded-xl shadow-md border border-[#27272A]">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-[#52525B] uppercase tracking-wide">Pending Enrollees</h3>
                    <div class="p-2 bg-amber-500/10 rounded-lg text-amber-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-[#FFFFFF]">
                    {{ $stats['pending_approvals'] }}
                </div>
                <p class="text-xs text-[#52525B] mt-2">Waiting for assessment.</p>
            </div>
        </div>

        <div class="bg-[#1C1C1E] rounded-xl shadow-md border border-[#10B981]/20 overflow-hidden">
            <div class="px-6 py-4 border-b border-[#27272A] flex justify-between items-center bg-[#121212]/50">
                <h3 class="font-bold text-[#FFFFFF] flex items-center gap-2">
                    <span class="w-2 h-2 bg-[#10B981] rounded-full animate-pulse"></span>
                    Payments Received Today
                </h3>
                <span class="text-xs font-bold text-[#10B981] bg-[#10B981]/10 px-3 py-1 rounded-full border border-[#10B981]/20">
                    {{ \Carbon\Carbon::today()->format('F d, Y') }}
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs text-[#52525B] border-b border-[#27272A] bg-[#121212]">
                            <th class="px-6 py-3 font-bold uppercase tracking-wider">Receipt #</th>
                            <th class="px-6 py-3 font-bold uppercase tracking-wider">Student Name</th>
                            <th class="px-6 py-3 font-bold uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 font-bold uppercase tracking-wider text-right">Amount</th>
                            <th class="px-6 py-3 font-bold uppercase tracking-wider text-center">Method</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-[#27272A]">
                        @forelse($paymentsToday as $payment)
                        <tr class="hover:bg-[#10B981]/5 transition-colors">
                            <td class="px-6 py-4 font-mono text-[#52525B]">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4 font-bold text-[#FFFFFF]">
                                {{ optional($payment->user)->name ?? 'Unknown Student' }}
                            </td>
                            <td class="px-6 py-4 text-[#10B981] font-medium">
                                {{ $payment->created_at->format('h:i A') }}
                            </td>
                            <td class="px-6 py-4 font-bold text-[#10B981] text-right">
                                ₱{{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center text-xs text-[#A1A1AA]">
                                <span class="bg-[#121212] px-2 py-1 rounded border border-[#27272A]">
                                    {{ $payment->payment_method ?? 'Cash' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-[#52525B] italic">No payments recorded today yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-[#1C1C1E] rounded-xl shadow-md border border-[#27272A] overflow-hidden opacity-90">
            <div class="px-6 py-4 border-b border-[#27272A] flex justify-between items-center bg-[#121212]/50">
                <h3 class="font-bold text-[#A1A1AA]">Yesterday's Payments</h3>
                <span class="text-xs font-medium text-[#52525B]">
                    {{ \Carbon\Carbon::yesterday()->format('F d, Y') }}
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs text-[#52525B] border-b border-[#27272A] bg-[#121212]">
                            <th class="px-6 py-3 font-bold uppercase tracking-wider">Receipt #</th>
                            <th class="px-6 py-3 font-bold uppercase tracking-wider">Student Name</th>
                            <th class="px-6 py-3 font-bold uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 font-bold uppercase tracking-wider text-right">Amount</th>
                            <th class="px-6 py-3 font-bold uppercase tracking-wider text-center">Method</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-[#27272A]">
                        @forelse($paymentsYesterday as $payment)
                        <tr class="hover:bg-[#27272A]/50 transition-colors">
                            <td class="px-6 py-4 font-mono text-[#52525B]">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4 font-medium text-[#A1A1AA]">
                                {{ optional($payment->user)->name ?? 'Unknown Student' }}
                            </td>
                            <td class="px-6 py-4 text-[#52525B]">
                                {{ $payment->created_at->format('h:i A') }}
                            </td>
                            <td class="px-6 py-4 font-bold text-[#A1A1AA] text-right">
                                ₱{{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center text-xs text-[#52525B]">
                                {{ $payment->payment_method ?? 'Cash' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-[#52525B] text-xs">No payments found for yesterday.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <footer class="bg-[#1C1C1E] border-t border-[#27272A] py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-[#52525B]">
            © 2026 Your Institution — Cashier Panel
        </div>
    </footer>

</body>
</html>