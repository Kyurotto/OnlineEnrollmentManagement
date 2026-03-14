<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
    <style>
        .table-container { min-height: 300px; }
        .modal-backdrop { background-color: rgba(255, 255, 255, 0.1); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #E5E7EB; border-radius: 4px; }
    </style>

    @if(session('success'))
        <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded-lg relative mb-6 flex items-center gap-2 shadow-sm font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden relative">
        <div wire:loading wire:target="prepareCreate, search, filter_course, status, resetFilters" class="absolute inset-0 bg-white/50 z-10 flex items-center justify-center backdrop-blur-sm">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#10B981]"></div>
        </div>

        <div class="px-6 py-5 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Payments History</h2>
                <p class="text-sm text-gray-500 mt-1">Manage and verify student transactions.</p>
            </div>
            <button wire:click="prepareCreate" class="bg-[#10B981] hover:bg-[#059669] text-white text-sm font-bold px-5 py-2 rounded-lg shadow-md shadow-[#10B981]/10 uppercase tracking-wide transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Process New Payment
            </button>
        </div>

        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-grow">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" class="pl-10 w-full bg-white border border-gray-300 rounded-lg p-2 text-sm text-gray-900 focus:ring-2 focus:ring-[#10B981] outline-none transition-all placeholder-gray-400 shadow-sm" placeholder="Search student name, email, or receipt #...">
                </div>
                <div class="w-full sm:w-48">
                    <select wire:model.live="filter_course" class="w-full bg-white border border-gray-300 rounded-lg p-2 text-sm text-gray-900 focus:ring-2 focus:ring-[#10B981] outline-none cursor-pointer shadow-sm">
                        <option value="ALL">All Programs</option>
                        <option value="BSIS-1">BSIS 1</option>
                        <option value="BSIS-2">BSIS 2</option>
                        <option value="BSIS-3">BSIS 3</option>
                        <option value="BSIS-4">BSIS 4</option>
                        <option value="DIT-1">DIT 1</option>
                        <option value="DIT-2">DIT 2</option>
                        <option value="DIT-3">DIT 3</option>
                        <option value="DIT-4">DIT 4</option>
                        <option value="ACT-1">ACT 1</option>
                        <option value="ACT-2">ACT 2</option>
                        <option value="ACT-3">ACT 3</option>
                        <option value="DHRT-1">DHRT 1</option>
                        <option value="DHRT-2">DHRT 2</option>
                        <option value="DHRT-3">DHRT 3</option>
                        <option value="BTVTED-1">BTVTED 1</option>
                        <option value="BTVTED-2">BTVTED 2</option>
                        <option value="BTVTED-3">BTVTED 3</option>
                        <option value="BTVTED-4">BTVTED 4</option>
                    </select>
                </div>
                <div class="w-full sm:w-40">
                    <select wire:model.live="status" class="w-full bg-white border border-gray-300 rounded-lg p-2 text-sm text-gray-900 focus:ring-2 focus:ring-[#10B981] outline-none cursor-pointer shadow-sm">
                        <option value="All statuses">All Statuses</option>
                        <option value="Paid">Paid</option>
                        <option value="Pending">Pending</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
                <button wire:click="resetFilters" class="bg-white border border-gray-300 text-gray-700 hover:text-gray-900 hover:bg-gray-50 text-sm font-medium px-4 py-2 rounded-lg transition text-center shadow-sm">Reset</button>
            </div>
        </div>

        <div class="overflow-x-auto table-container">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs text-gray-600 uppercase tracking-wider">
                        <th class="py-3 px-6 font-bold">Receipt #</th>
                        <th class="py-3 px-6 font-bold">Student Name</th>
                        <th class="py-3 px-6 font-bold">Program/Year</th>
                        <th class="py-3 px-6 font-bold">Date</th>
                        <th class="py-3 px-6 font-bold text-right">Amount</th>
                        <th class="py-3 px-6 font-bold text-center">Status</th>
                        <th class="py-3 px-6 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-200 bg-white">
                    @forelse($payments as $payment)
                    <tr wire:key="payment-{{ $payment->id }}" class="hover:bg-gray-50 transition group">
                        <td class="py-4 px-6 font-mono text-xs text-gray-500">
                            <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded border border-gray-200 shadow-sm">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-bold text-gray-900">{{ optional($payment->user)->name ?? 'Unknown' }}</div>
                            <div class="text-xs text-gray-500">{{ optional($payment->user)->email }}</div>
                        </td>
                        <td class="py-4 px-6 text-gray-600">
                            @if(optional($payment->application)->course_code)
                                <span class="font-bold text-gray-900">{{ $payment->application->course_code }}</span>
                                <span class="text-xs text-gray-500 block">{{ $payment->application->year_level }}</span>
                            @else
                                <span class="text-gray-400 italic text-xs">N/A</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-gray-600">
                            {{ $payment->created_at->format('M d, Y') }}
                            <span class="text-xs text-gray-500 block">{{ $payment->payment_method ?? 'Cash' }}</span>
                        </td>
                        <td class="py-4 px-6 font-bold text-[#10B981] text-right">₱{{ number_format($payment->amount, 2) }}</td>
                        <td class="py-4 px-6 text-center">
                            @if($payment->status === 'Paid') 
                                <span class="bg-[#10B981]/10 text-[#10B981] text-[10px] font-bold px-2.5 py-1 rounded-full border border-[#10B981]/20 uppercase tracking-wide shadow-sm">Paid</span>
                            @elseif($payment->status === 'Rejected') 
                                <span class="bg-rose-50 text-rose-600 text-[10px] font-bold px-2.5 py-1 rounded-full border border-rose-200 uppercase tracking-wide shadow-sm">Rejected</span>
                            @else 
                                <span class="bg-amber-50 text-amber-600 text-[10px] font-bold px-2.5 py-1 rounded-full border border-amber-200 uppercase tracking-wide shadow-sm">Pending</span> 
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right relative" x-data="{ open: false }">
                            <div class="relative inline-block text-left">
                                <button @click="open = !open" @click.away="open = false" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-3 py-1.5 bg-white text-xs font-bold text-gray-700 hover:bg-gray-50 focus:outline-none transition-all">
                                    Actions
                                    <svg class="-mr-1 ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                
                                <div x-show="open" x-transition style="display: none;" class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white border border-gray-200 ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="py-1">
                                        <button wire:click="editPayment({{ $payment->id }})" class="w-full text-left block px-4 py-2 text-xs text-[#10B981] font-semibold hover:bg-gray-50">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                Edit Details
                                            </span>
                                        </button>
                                        @if($payment->status !== 'Paid')
                                            <button wire:click="updateStatus({{ $payment->id }}, 'Paid')" class="w-full text-left block px-4 py-2 text-xs text-gray-700 font-semibold hover:bg-gray-50">
                                                <span class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    Mark as Paid
                                                </span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="py-12 text-center text-gray-500 italic text-sm">No payment records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-100 bg-white">
            @if(method_exists($payments, 'links'))
                {{ $payments->links() }}
            @endif
        </div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 z-50 p-4 modal-backdrop flex items-center justify-center">
            <div class="absolute inset-0" wire:click="closeModal"></div>

            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all duration-200 border border-gray-200 relative z-10">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-xl">
                    <h3 class="text-lg font-bold text-gray-900">{{ $isEditMode ? 'Edit Payment Details' : 'Process New Payment' }}</h3>
                    <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}" class="p-6 space-y-5 bg-white rounded-b-xl">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Select Student</label>
                        <select wire:model="user_id" class="w-full bg-white border border-gray-300 text-gray-900 rounded-lg p-3 text-sm focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all appearance-none cursor-pointer shadow-sm" required>
                            <option value="" disabled>Search or select a student...</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                            @endforeach
                        </select>
                        @error('user_id') <span class="text-xs text-red-500 font-bold block pt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Amount (₱)</label>
                        <input type="number" wire:model="amount" step="0.01" class="w-full bg-white border border-gray-300 text-[#10B981] rounded-lg p-3 text-sm focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none font-bold transition-all placeholder-gray-400 shadow-sm" placeholder="0.00" required>
                        @error('amount') <span class="text-xs text-red-500 font-bold block pt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Payment Method</label>
                        <select wire:model="payment_type" class="w-full bg-white border border-gray-300 text-gray-900 rounded-lg p-3 text-sm focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all shadow-sm" required>
                            <option value="Cash">Cash</option>
                            <option value="Gcash">Gcash</option>
                            <option value="PayMaya">PayMaya</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                        </select>
                        @error('payment_type') <span class="text-xs text-red-500 font-bold block pt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Reference No. (Optional)</label>
                        <input type="text" wire:model="reference_no" class="w-full bg-white border border-gray-300 text-gray-900 rounded-lg p-3 text-sm focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all placeholder-gray-400 shadow-sm" placeholder="OR Number">
                    </div>

                    <div class="pt-4 flex justify-end gap-3 border-t border-gray-100 mt-6 pt-5">
                        <button type="button" wire:click="closeModal" class="px-5 py-2.5 text-sm font-semibold text-gray-600 hover:text-gray-900 border border-transparent transition">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 text-sm bg-[#10B981] hover:bg-[#059669] text-white font-bold rounded-lg shadow-md shadow-[#10B981]/10 transition-all uppercase tracking-wide">
                            <span wire:loading.remove wire:target="{{ $isEditMode ? 'update' : 'store' }}">{{ $isEditMode ? 'Update Payment' : 'Save Payment' }}</span>
                            <span wire:loading wire:target="{{ $isEditMode ? 'update' : 'store' }}">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>