<div>
<div>
    <header class="bg-white border-b border-gray-200 sticky top-0 z-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Pay School Fees</h1>
                <p class="text-xs text-gray-500">Record a payment. An administrator will verify and update the status.
                </p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('student.dashboard') }}" wire:navigate
                    class="text-sm text-gray-500 hover:text-[#10B981] transition font-medium">← Back to Dashboard</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button
                        class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2 px-4 rounded shadow transition-colors">Logout</button>
                </form>
            </div>
        </div>
    </header>
    <main class="flex-grow max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Your Payment Records <span
                            class="text-gray-500 font-normal">({{ count($payments ?? []) }})</span></h2>
                </div>
                <div class="space-y-4">
                    @forelse($payments ?? [] as $payment)
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 flex justify-between items-start hover:border-gray-300 transition-colors">
                        <div>
                            <p class="font-bold text-gray-900">{{ number_format($payment['amount'], 2) }} PHP <span
                                    class="text-xs text-gray-500 font-normal ml-2">Txn: {{ $payment['txn_id'] }}</span>
                            </p>
                            <p class="text-xs text-gray-500 mt-1">{{ $payment['date'] }}</p>
                        </div>
                        <div class="text-right">
                            @if($payment['status'] === 'Completed')<span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#10B981]/10 text-[#10B981] border border-[#10B981]/20 shadow-sm">Completed</span>@else<span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200 shadow-sm">Pending</span>@endif
                            <p class="text-xs text-gray-400 mt-2">#{{ $payment['id'] }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6 text-gray-500">No payment records found.</div>
                    @endforelse
                </div>
            </div>
    </main>
</div>
