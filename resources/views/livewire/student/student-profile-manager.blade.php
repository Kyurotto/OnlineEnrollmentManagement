<div>
    <div class="max-w-7xl mx-auto space-y-6 animate-in fade-in duration-700">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-bold text-white tracking-tight">My Profile</h2>
                <p class="text-xs mt-2 font-medium uppercase tracking-[0.2em]" style="color: rgba(255,255,255,0.4);">Profile & Account Settings</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 p-8 rounded-2xl border shadow-2xl shadow-black/40 h-fit"
                 style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">

                <h3 class="text-lg font-bold text-white mb-8 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-blue-400 rounded-full"></span>
                    Account Registry
                </h3>

                @if (session('profile-updated'))
                <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded-xl relative mb-8 text-xs font-bold uppercase tracking-widest animate-in slide-in-from-top-2">
                    Profile Data Synchronized Successfully.
                </div>
                @endif

                <div class="mb-10 p-6 rounded-xl bg-white/5 border border-white/10">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest mb-1 block">Account Username</label>
                    <div class="text-2xl font-black text-white tracking-widest">
                        {{ Auth::user()->username ?? Auth::user()->name }}
                    </div>
                    <p class="text-xs text-white/20 mt-2 italic">Student ID is static. Contact administration for modification.</p>
                </div>

                <form wire:submit="updateProfile" class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Last Name</label>
                            <input type="text" wire:model="last_name"
                                class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-blue-400 outline-none transition-colors">
                            @error('last_name') <span class="text-xs text-rose-400 mt-1 block font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">First Name</label>
                            <input type="text" wire:model="first_name"
                                class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-blue-400 outline-none transition-colors">
                            @error('first_name') <span class="text-xs text-rose-400 mt-1 block font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Middle Name</label>
                            <input type="text" wire:model="middle_name"
                                class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-blue-400 outline-none transition-colors">
                            @error('middle_name') <span class="text-xs text-rose-400 mt-1 block font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Email</label>
                        <input type="email" wire:model="email" readonly
                            class="w-full bg-transparent border-b border-white/5 py-2.5 text-white/30 cursor-not-allowed outline-none">
                    </div>

                    <button type="submit"
                        class="bg-blue-400 hover:bg-blue-300 text-black font-black py-4 px-10 rounded-xl shadow-xl shadow-blue-500/20 transition-all active:scale-95 uppercase tracking-widest text-xs">
                        <span wire:loading.remove wire:target="updateProfile">Save</span>
                        <span wire:loading wire:target="updateProfile">Saving...</span>
                    </button>
                </form>
            </div>

            <div class="space-y-6">
                {{-- Activity Log --}}
                <div class="p-8 rounded-2xl border shadow-2xl shadow-black/40 h-fit"
                     style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">
                    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-3">
                        <span class="w-1.5 h-6 bg-emerald-400 rounded-full"></span>
                        Activity Logs
                    </h3>

                    <div class="space-y-6">
                        <div>
                            <p class="text-xs font-bold text-white/20 uppercase tracking-widest mb-3">Recent Enrollments</p>
                            <p class="text-xs text-white/30 italic">No history found.</p>
                        </div>

                        <div class="pt-6 border-t border-white/5">
                            <p class="text-xs font-bold text-white/20 uppercase tracking-widest mb-4">Payment History</p>

                            @if (isset($payments) && count($payments) > 0)
                                @foreach ($payments as $payment)
                                    <div class="flex justify-between items-center mb-4 pb-4 border-b border-white/5 last:border-0 last:pb-0 hover:bg-white/5 transition-colors p-2 -mx-2 rounded-xl">
                                        <div>
                                            <div class="text-sm font-black text-white tracking-tight">₱{{ number_format($payment['amount'], 2) }}</div>
                                            <div class="text-xs text-white/40 mt-1 uppercase tracking-tighter">{{ $payment['date'] }}</div>
                                        </div>
                                        @php
                                            $statusClasses = match($payment['status']) {
                                                'Paid', 'Completed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                                'Pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                                'Rejected' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                                default => 'bg-white/10 text-white/40 border-white/20'
                                            };
                                        @endphp
                                        <span class="{{ $statusClasses }} text-[8px] font-black px-2 py-0.5 rounded-full uppercase tracking-widest shadow-sm">
                                            {{ $payment['status'] }}
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-xs text-white/30 italic">No historical payments detected.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Password Security --}}
                <div class="p-8 rounded-2xl border shadow-2xl shadow-black/40"
                     style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">
                    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-3">
                        <span class="w-1.5 h-6 bg-rose-400 rounded-full"></span>
                        Change Password
                    </h3>

                    @if (session('password-updated'))
                    <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded-xl relative mb-6 text-xs font-bold uppercase tracking-widest">
                        Password Updated Successfully.
                    </div>
                    @endif

                    <form wire:submit="updatePassword" class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Current Password</label>
                            <input type="password" wire:model="current_password"
                                class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-rose-400 outline-none transition-colors placeholder-white/5" placeholder="••••••••">
                            @error('current_password') <span class="text-xs text-rose-400 mt-1 block font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">New Password</label>
                            <input type="password" wire:model="password"
                                class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-rose-400 outline-none transition-colors placeholder-white/5" placeholder="••••••••">
                            @error('password') <span class="text-xs text-rose-400 mt-1 block font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Confirm New Password</label>
                            <input type="password" wire:model="password_confirmation"
                                class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-rose-400 outline-none transition-colors placeholder-white/5" placeholder="••••••••">
                        </div>

                        <button type="submit"
                            class="w-full bg-rose-500/20 hover:bg-rose-500/30 text-rose-400 font-black py-4 px-6 rounded-xl border border-rose-500/30 shadow-xl shadow-rose-900/10 transition-all active:scale-95 uppercase tracking-widest text-xs">
                            <span wire:loading.remove wire:target="updatePassword">Update Password</span>
                            <span wire:loading wire:target="updatePassword">Processing...</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
