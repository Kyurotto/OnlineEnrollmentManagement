<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">
            {{ $level ? strtoupper($level) . ' Payment Assessment' : 'Payment Assessment' }}
        </h1>
        <p class="text-gray-300">Edit payment assessment fees and discount settings</p>
    </div>

    <!-- Success Modal -->
    @if($successMessage)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="fixed inset-0 z-50 flex items-center justify-center"
            style="background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);"
        >
            <div class="rounded-2xl p-8 text-center max-w-sm w-full mx-4 shadow-2xl" style="background: rgba(13,31,60,0.98); border: 1px solid rgba(34,197,94,0.4);">
                <!-- Animated checkmark circle -->
                <div class="flex items-center justify-center mb-5">
                    <div class="w-20 h-20 rounded-full flex items-center justify-center" style="background: rgba(34,197,94,0.15); border: 2px solid rgba(34,197,94,0.5);">
                        <svg class="w-10 h-10" style="color: #4ade80;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>

                <h3 class="text-2xl font-bold text-white mb-2">Assessment Saved!</h3>
                <p class="mb-1" style="color: #86efac; font-size: 0.95rem;">{{ $successMessage }}</p>
                <p class="text-sm mb-6" style="color: #64748b;">The updated fees will be applied to all new and existing payments.</p>

                <button
                    @click="show = false"
                    class="w-full py-3 rounded-xl font-semibold text-white transition-all duration-200"
                    style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); border: none; cursor: pointer;"
                    onmouseover="this.style.opacity='0.9'; this.style.transform='scale(1.02)';"
                    onmouseout="this.style.opacity='1'; this.style.transform='scale(1)';"
                >
                    Got it!
                </button>
            </div>
        </div>
    @endif

    <!-- Assessment Form Card -->
    <div class="rounded-xl p-6 mb-6" style="background: rgba(13,31,60,0.8); border: 1px solid rgba(99,179,237,0.2); backdrop-filter: blur(10px);">

        <!-- Card Title -->
        <div class="flex items-center gap-2 mb-6 pb-4" style="border-bottom: 1px solid rgba(99,179,237,0.2);">
            <svg class="w-5 h-5" style="color: #63b3ed;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h2 class="text-xl font-bold text-white">Assessment Configuration</h2>
        </div>

        <form wire:submit.prevent="saveAssessment" class="space-y-6">
            <!-- Tuition Fee -->
            <div>
                <label class="block text-sm font-medium mb-2" style="color: #cbd5e1;">Tuition Fee</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2" style="color: #8ab4d8;">₱</span>
                    <input
                        type="number"
                        wire:model.defer="tuitionFee"
                        step="0.01"
                        min="0"
                        placeholder="Enter tuition fee amount"
                        class="w-full pl-7 pr-4 py-3 rounded-lg font-medium focus:outline-none transition-all"
                        style="background: rgba(255,255,255,0.05); border: 1px solid rgba(99,179,237,0.2); color: #ffffff;"
                        onchange="this.style.borderColor='rgba(99,179,237,0.2)'"
                        onfocus="this.style.borderColor='rgba(99,179,237,0.4)'"
                        onblur="this.style.borderColor='rgba(99,179,237,0.2)'"
                    />
                </div>
                @error('tuitionFee') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Miscellaneous Fees -->
            <div>
                <label class="block text-sm font-medium mb-2" style="color: #cbd5e1;">Miscellaneous Fees</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2" style="color: #8ab4d8;">₱</span>
                    <input
                        type="number"
                        wire:model.defer="miscellaneousFees"
                        step="0.01"
                        min="0"
                        placeholder="Enter miscellaneous fees amount"
                        class="w-full pl-7 pr-4 py-3 rounded-lg font-medium focus:outline-none transition-all"
                        style="background: rgba(255,255,255,0.05); border: 1px solid rgba(99,179,237,0.2); color: #ffffff;"
                        onchange="this.style.borderColor='rgba(99,179,237,0.2)'"
                        onfocus="this.style.borderColor='rgba(99,179,237,0.4)'"
                        onblur="this.style.borderColor='rgba(99,179,237,0.2)'"
                    />
                </div>
                @error('miscellaneousFees') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Summary -->
            <div class="p-4 rounded-lg" style="background: rgba(99,179,237,0.08); border: 1px solid rgba(99,179,237,0.2);">
                <p class="text-sm font-medium mb-3" style="color: #cbd5e1;">Fee Summary</p>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between" style="color: #8ab4d8;">
                        <span>Tuition Fee:</span>
                        <span class="font-semibold">₱{{ number_format($tuitionFee, 2) }}</span>
                    </div>
                    <div class="flex justify-between" style="color: #8ab4d8;">
                        <span>Miscellaneous Fees:</span>
                        <span class="font-semibold">₱{{ number_format($miscellaneousFees, 2) }}</span>
                    </div>
                    <div class="flex justify-between" style="border-top: 1px solid rgba(99,179,237,0.2); padding-top: 8px; color: #63b3ed;">
                        <span>Total:</span>
                        <span class="font-bold">₱{{ number_format($tuitionFee + $miscellaneousFees, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-4">
                <button
                    type="submit"
                    class="flex-1 py-3 rounded-lg font-semibold transition-all duration-200 flex items-center justify-center gap-2"
                    style="background: linear-gradient(135deg, #63b3ed 0%, #3b82f6 100%); color: #ffffff; border: none; cursor: pointer;"
                    onmouseover="this.style.opacity='0.9'; this.style.transform='scale(1.02)';"
                    onmouseout="this.style.opacity='1'; this.style.transform='scale(1)';"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Save Assessment
                </button>
                <a
                    href="{{ route('cashier.dashboard') }}"
                    class="flex-1 py-3 rounded-lg font-semibold transition-all duration-200 flex items-center justify-center gap-2"
                    style="background: rgba(255,255,255,0.05); color: #8ab4d8; border: 1px solid rgba(99,179,237,0.2); text-decoration: none;"
                    onmouseover="this.style.background='rgba(99,179,237,0.1)'; this.style.color='#ffffff';"
                    onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.color='#8ab4d8';"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Info Box -->
    <div class="p-4 rounded-lg" style="background: rgba(59,130,246,0.08); border: 1px solid rgba(99,179,237,0.2);">
        <p class="text-sm" style="color: #cbd5e1;">
            <span class="font-semibold">💡 Note:</span> These base fees will be applied to all new and existing payments for <span class="font-semibold">{{ $level ? strtoupper($level) : 'this level' }}</span> students. Additional discounts can be applied per payment.
        </p>
    </div>
</div>
