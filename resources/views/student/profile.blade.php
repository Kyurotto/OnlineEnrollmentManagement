<x-layouts.student title="My Profile">

    <div class="max-w-7xl mx-auto space-y-6 animate-in fade-in duration-700">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">My Profile</h2>
                <p class="text-xs mt-2 font-black uppercase tracking-[0.2em] text-slate-400">Profile & Account Settings</p>
            </div>
            <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 px-8 py-3.5 rounded-full bg-white border border-slate-100 text-[11px] font-black text-indigo-600 uppercase tracking-[0.2em] hover:bg-indigo-50 transition-all shadow-lg shadow-indigo-600/5 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 p-10 rounded-[2.5rem] border bg-white shadow-2xl shadow-blue-900/5 h-fit"
                 style="border-color: rgba(37,99,235,0.1);">
                
                <h3 class="text-lg font-black text-slate-900 mb-10 flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                    Personal Information
                </h3>

                @if (session('status') === 'profile-updated')
                <div class="bg-blue-50 border border-blue-100 text-blue-600 px-6 py-4 rounded-2xl relative mb-10 text-[10px] font-black uppercase tracking-widest animate-in slide-in-from-top-2">
                    Profile Data Synchronized Successfully.
                </div>
                @endif

                <div class="mb-10 p-8 rounded-3xl bg-slate-50 border border-slate-100">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Account Username</label>
                    <div class="text-3xl font-black text-slate-900 tracking-tight">
                        {{ Auth::user()->username ?? Auth::user()->name }}
                    </div>
                    <p class="text-[10px] text-slate-400 mt-3 font-medium">Student ID is static. Contact administration for modification.</p>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-10">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name', Auth::user()->last_name) }}"
                                class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-4 text-slate-900 font-bold focus:border-blue-600 outline-none transition-all shadow-sm focus:ring-4 focus:ring-blue-600/5">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name', Auth::user()->first_name) }}"
                                class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-4 text-slate-900 font-bold focus:border-blue-600 outline-none transition-all shadow-sm focus:ring-4 focus:ring-blue-600/5">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name', Auth::user()->middle_name) }}"
                                class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-4 text-slate-900 font-bold focus:border-blue-600 outline-none transition-all shadow-sm focus:ring-4 focus:ring-blue-600/5">
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Email Address (Read-only)</label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" readonly
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-slate-400 font-bold cursor-not-allowed outline-none select-none">
                    </div>

                    <div class="pt-6">
                        <button type="submit"
                            class="w-full sm:w-auto bg-emerald-500 hover:bg-emerald-600 text-white font-black py-5 px-14 rounded-[1.5rem] shadow-xl shadow-emerald-600/20 transition-all active:scale-95 uppercase tracking-widest text-xs">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <div class="space-y-8">
                {{-- Activity Log --}}
                <div class="p-10 rounded-[2.5rem] border bg-white shadow-2xl shadow-blue-900/5 h-fit"
                     style="border-color: rgba(37,99,235,0.1);">
                    <h3 class="text-lg font-black text-slate-900 mb-8 flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                        Account Logs
                    </h3>

                    <div class="space-y-8">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Enrollment Status</p>
                            <p class="text-xs text-slate-400 font-medium">No active session history detected.</p>
                        </div>

                        <div class="pt-8 border-t border-slate-100">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Recent Payments</p>

                            @if (isset($payments) && count($payments) > 0)
                                @foreach ($payments as $payment)
                                    <div class="flex justify-between items-center mb-5 pb-5 border-b border-slate-50 last:border-0 last:pb-0">
                                        <div>
                                            <div class="text-sm font-black text-slate-900 tracking-tight">₱{{ number_format($payment['amount'], 2) }}</div>
                                            <div class="text-[10px] font-medium text-slate-400 mt-1 uppercase tracking-tighter">{{ $payment['date'] }}</div>
                                        </div>
                                        <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 text-[8px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-sm">
                                            {{ $payment['status'] }}
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex justify-between items-center bg-slate-50 p-5 rounded-2xl border border-slate-100">
                                    <div>
                                        <div class="text-sm font-black text-slate-900">₱0.00</div>
                                        <div class="text-[10px] font-medium text-slate-400 mt-1 uppercase tracking-tighter">No transactions</div>
                                    </div>
                                    <span class="bg-slate-100 text-slate-400 border border-slate-200 text-[8px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-sm">VACANT</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Password Security --}}
                <div class="p-10 rounded-[2.5rem] border bg-white shadow-2xl shadow-blue-900/5"
                     style="border-color: rgba(37,99,235,0.1);">
                    <h3 class="text-lg font-black text-slate-900 mb-8 flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-rose-500 rounded-full"></div>
                        Account Security
                    </h3>

                    <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('put')

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Current Password</label>
                            <input type="password" name="current_password"
                                class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-4 text-slate-900 font-bold focus:border-rose-500 outline-none transition-all shadow-sm focus:ring-4 focus:ring-rose-500/5 placeholder-slate-200" placeholder="••••••••">
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">New Password</label>
                            <input type="password" name="password"
                                class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-4 text-slate-900 font-bold focus:border-rose-500 outline-none transition-all shadow-sm focus:ring-4 focus:ring-rose-500/5 placeholder-slate-200" placeholder="••••••••">
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation"
                                class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-4 text-slate-900 font-bold focus:border-rose-500 outline-none transition-all shadow-sm focus:ring-4 focus:ring-rose-500/5 placeholder-slate-200" placeholder="••••••••">
                        </div>

                        <div class="pt-4">
                            <button type="submit"
                                class="w-full bg-rose-500 hover:bg-rose-600 text-white font-black py-5 px-6 rounded-[1.5rem] shadow-xl shadow-rose-600/20 transition-all active:scale-95 uppercase tracking-widest text-xs">
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layouts.student>
