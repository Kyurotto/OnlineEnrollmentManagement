<x-layouts.student title="My Profile">

    <div class="max-w-7xl mx-auto space-y-6 animate-in fade-in duration-700">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-bold text-white tracking-tight">My Profile</h2>
                <p class="text-xs mt-2 font-medium uppercase tracking-[0.2em]" style="color: rgba(255,255,255,0.4);">Profile & Account Settings</p>
            </div>
            <a href="{{ route('student.dashboard') }}" wire:navigate class="text-xs font-bold text-[#10B981] hover:text-[#34d399] transition-colors flex items-center gap-2 uppercase tracking-widest">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 p-8 rounded-2xl border shadow-2xl shadow-black/40 h-fit"
                 style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">
                
                <h3 class="text-lg font-bold text-white mb-8 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-blue-400 rounded-full"></span>
                    Account Registry
                </h3>

                @if (session('status') === 'profile-updated')
                <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded-xl relative mb-8 text-xs font-bold uppercase tracking-widest animate-in slide-in-from-top-2">
                    Profile Data Synchronized Successfully.
                </div>
                @endif

                <div class="mb-10 p-6 rounded-xl bg-white/5 border border-white/10">
                    <label class="text-[10px] font-bold text-white/40 uppercase tracking-widest mb-1 block">Account Username</label>
                    <div class="text-2xl font-black text-white tracking-widest">
                        {{ Auth::user()->username ?? Auth::user()->name }}
                    </div>
                    <p class="text-[10px] text-white/20 mt-2 italic">Student ID is static. Contact administration for modification.</p>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-white/40 uppercase tracking-widest ml-1">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name', Auth::user()->last_name) }}"
                                class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-blue-400 outline-none transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-white/40 uppercase tracking-widest ml-1">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name', Auth::user()->first_name) }}"
                                class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-blue-400 outline-none transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-white/40 uppercase tracking-widest ml-1">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name', Auth::user()->middle_name) }}"
                                class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-blue-400 outline-none transition-colors">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-white/40 uppercase tracking-widest ml-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" readonly
                            class="w-full bg-transparent border-b border-white/5 py-2.5 text-white/30 cursor-not-allowed outline-none">
                    </div>

                    <button type="submit"
                        class="bg-blue-400 hover:bg-blue-300 text-black font-black py-4 px-10 rounded-xl shadow-xl shadow-blue-500/20 transition-all active:scale-95 uppercase tracking-widest text-xs">
                        Save
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
                            <p class="text-[10px] font-bold text-white/20 uppercase tracking-widest mb-3">Recent Enrollments</p>
                            <p class="text-xs text-white/30 italic">No history found.</p>
                        </div>

                        <div class="pt-6 border-t border-white/5">
                            <p class="text-[10px] font-bold text-white/20 uppercase tracking-widest mb-4">Payment History</p>

                            @if (isset($payments) && count($payments) > 0)
                                @foreach ($payments as $payment)
                                    <div class="flex justify-between items-center mb-4 pb-4 border-b border-white/5 last:border-0 last:pb-0">
                                        <div>
                                            <div class="text-sm font-black text-white tracking-tight">₱{{ number_format($payment['amount'], 2) }}</div>
                                            <div class="text-[10px] text-white/40 mt-1 uppercase tracking-tighter">{{ $payment['date'] }}</div>
                                        </div>
                                        <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[8px] font-black px-2 py-0.5 rounded-full uppercase tracking-widest">
                                            {{ $payment['status'] }}
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex justify-between items-center bg-white/5 p-4 rounded-xl border border-white/10">
                                    <div>
                                        <div class="text-sm font-black text-white">₱1,000.00</div>
                                        <div class="text-[10px] text-white/40 mt-1 uppercase tracking-tighter">2026-01-28 13:30</div>
                                    </div>
                                    <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[8px] font-black px-2 py-0.5 rounded-full uppercase tracking-widest">COMPLETED</span>
                                </div>
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

                    <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('put')

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-white/40 uppercase tracking-widest ml-1">Current Password</label>
                            <input type="password" name="current_password"
                                class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-rose-400 outline-none transition-colors placeholder-white/5" placeholder="••••••••">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-white/40 uppercase tracking-widest ml-1">New Password</label>
                            <input type="password" name="password"
                                class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-rose-400 outline-none transition-colors placeholder-white/5" placeholder="••••••••">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-white/40 uppercase tracking-widest ml-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation"
                                class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-rose-400 outline-none transition-colors placeholder-white/5" placeholder="••••••••">
                        </div>

                        <button type="submit"
                            class="w-full bg-rose-500/20 hover:bg-rose-500/30 text-rose-400 font-black py-4 px-6 rounded-xl border border-rose-500/30 shadow-xl shadow-rose-900/10 transition-all active:scale-95 uppercase tracking-widest text-[10px]">
                            Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layouts.student>
