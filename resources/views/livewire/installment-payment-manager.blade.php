<div class="installment-payment-manager p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6">Installment Payment Manager</h2>

    <!-- Configuration Section -->
    <div class="mb-8 p-4 bg-gray-50 rounded-lg">
        <h3 class="text-lg font-semibold mb-4">Configuration</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Downpayment Percentage (% of Total Assessment)
                </label>
                <input 
                    type="number" 
                    wire:change="updateDownpaymentPercentage($event.target.value)"
                    value="{{ $downpaymentPercentage }}"
                    min="5"
                    max="100"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Distribution Type
                </label>
                <select wire:change="updateInstallmentType($event.target.value)" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="equal">Equal Distribution (33.33% each)</option>
                    <option value="weighted">Weighted (30%, 30%, 40%)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Summary Section -->
    <div class="mb-8 grid grid-cols-3 gap-4">
        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
            <p class="text-sm text-gray-600">Total Assessment</p>
            <p class="text-2xl font-bold text-blue-600">₱{{ number_format($totalAssessment, 2) }}</p>
        </div>
        <div class="p-4 bg-green-50 rounded-lg border border-green-200">
            <p class="text-sm text-gray-600">Down Payment ({{ $downpaymentPercentage }}%)</p>
            <p class="text-2xl font-bold text-green-600">₱{{ number_format($downpaymentAmount, 2) }}</p>
        </div>
        <div class="p-4 bg-orange-50 rounded-lg border border-orange-200">
            <p class="text-sm text-gray-600">Remaining</p>
            <p class="text-2xl font-bold text-orange-600">₱{{ number_format($totalAssessment - $downpaymentAmount, 2) }}</p>
        </div>
    </div>

    <!-- Installment Breakdown -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold mb-4">Installment Breakdown</h3>
        <div class="grid grid-cols-1 gap-4">
            @foreach(['Prelim' => 0, 'Midterm' => 1, 'Final' => 2] as $phase => $index)
            <div class="p-4 border rounded-lg {{ in_array($phase, $paidInstallments) ? 'bg-green-50 border-green-300' : 'bg-gray-50 border-gray-300' }}">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h4 class="font-semibold text-lg">{{ $phase }} Payment</h4>
                        <p class="text-sm text-gray-600">Due: {{ $dueDates[$phase] }}</p>
                        <p class="text-2xl font-bold text-gray-800 mt-2">₱{{ number_format($installments[$phase], 2) }}</p>
                        @if(in_array($phase, $paidInstallments))
                            <span class="inline-block mt-2 px-3 py-1 bg-green-500 text-white text-sm rounded-full">✓ Paid</span>
                        @else
                            <span class="inline-block mt-2 px-3 py-1 bg-yellow-500 text-white text-sm rounded-full">Pending</span>
                        @endif
                    </div>
                    @unless(in_array($phase, $paidInstallments))
                    <button 
                        wire:click="recordInstallmentPayment('{{ $phase }}', {{ $installments[$phase] }}, 'Cash', '')"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition"
                    >
                        Record Payment
                    </button>
                    @endunless
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Payment History -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold mb-4">Payment History</h3>
        @if(count($paymentHistory) > 0)
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2 text-left">Phase</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Amount</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Payment Date</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Reference No</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paymentHistory as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2 font-semibold">{{ $payment['installment_type'] }}</td>
                            <td class="border border-gray-300 px-4 py-2">₱{{ number_format($payment['amount'], 2) }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ \Carbon\Carbon::parse($payment['payment_date'])->format('M d, Y') }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-sm">{{ $payment['transaction_id'] }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">{{ $payment['status'] }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">No payments recorded yet.</p>
        @endif
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-3 gap-4 p-4 bg-gray-100 rounded-lg">
        <div class="text-center">
            <p class="text-sm text-gray-600">Total Paid</p>
            <p class="text-xl font-bold text-green-600">₱{{ number_format($getTotalPaid(), 2) }}</p>
        </div>
        <div class="text-center">
            <p class="text-sm text-gray-600">Remaining Balance</p>
            <p class="text-xl font-bold text-orange-600">₱{{ number_format($remainingBalance, 2) }}</p>
        </div>
        <div class="text-center">
            <p class="text-sm text-gray-600">Status</p>
            <p class="text-xl font-bold {{ $isFullyPaid() ? 'text-green-600' : 'text-yellow-600' }}">
                {{ $isFullyPaid() ? 'Fully Paid ✓' : 'Partial' }}
            </p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session()->has('success'))
        <div class="mt-4 p-4 bg-green-50 border border-green-300 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="mt-4 p-4 bg-red-50 border border-red-300 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif
</div>
