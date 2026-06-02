<x-layouts.student title="My Profile">

    <div class="max-w-7xl mx-auto space-y-8 animate-in fade-in duration-700">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">My Profile</h2>
                <p class="text-xs mt-2 font-black uppercase tracking-[0.2em] text-slate-400">Profile & Security
                    Management</p>
            </div>
            <a href="{{ route('student.dashboard') }}"
                class="flex items-center gap-3 px-8 py-3.5 rounded-2xl bg-white border border-blue-500/10 text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] hover:bg-blue-50 transition-all shadow-xl shadow-blue-900/5 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            <div class="lg:col-span-2 p-10 rounded-[2.5rem] border bg-white shadow-2xl shadow-blue-900/5 h-fit"
                style="border-color: rgba(37,99,235,0.1);">

                <h3
                    class="text-[10px] font-black text-blue-600 uppercase tracking-[0.25em] mb-10 flex items-center gap-2">
                    <div class="w-1.5 h-4 bg-blue-600 rounded-full shadow-lg shadow-blue-600/30"></div>
                    {{ __('Personal Information') }}
                </h3>

                @if (session('status') === 'profile-updated')
                    <div
                        class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-4 rounded-2xl relative mb-10 text-[9px] font-black uppercase tracking-widest animate-in slide-in-from-top-2">
                        ✓ Profile Synchronization Complete.
                    </div>
                @endif

                <div
                    class="mb-10 p-10 rounded-[2rem] bg-blue-50/30 border border-blue-500/10 relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 p-8 opacity-[0.03] mt-[-20px] mr-[-20px] transition-transform group-hover:scale-110 duration-700">
                        <svg class="w-32 h-32 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 block">Account
                        Username</label>
                    <div class="text-4xl font-black text-slate-900 tracking-tighter">
                        {{ Auth::user()->username ?? Auth::user()->name }}
                    </div>
                    <p class="text-[9px] text-slate-400 mt-4 font-bold uppercase tracking-widest">Static Identifier •
                        Contact Registrar for modification</p>
                </div>

                <form action="{{ route('student.profile.update') }}" method="POST" class="space-y-10">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-3">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Last
                                Name</label>
                            <input type="text" name="last_name"
                                value="{{ old('last_name', Auth::user()->last_name) }}"
                                class="w-full bg-white border border-blue-500/10 rounded-2xl px-5 py-4 text-slate-900 font-black text-[12px] focus:border-blue-600 outline-none transition-all shadow-sm focus:ring-4 focus:ring-blue-600/5">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">First
                                Name</label>
                            <input type="text" name="first_name"
                                value="{{ old('first_name', Auth::user()->first_name) }}"
                                class="w-full bg-white border border-blue-500/10 rounded-2xl px-5 py-4 text-slate-900 font-black text-[12px] focus:border-blue-600 outline-none transition-all shadow-sm focus:ring-4 focus:ring-blue-600/5">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Middle
                                Name</label>
                            <input type="text" name="middle_name"
                                value="{{ old('middle_name', Auth::user()->middle_name) }}"
                                class="w-full bg-white border border-blue-500/10 rounded-2xl px-5 py-4 text-slate-900 font-black text-[12px] focus:border-blue-600 outline-none transition-all shadow-sm focus:ring-4 focus:ring-blue-600/5">
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Email
                            Address (Secured)</label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" readonly
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-slate-400 font-black text-[12px] cursor-not-allowed outline-none select-none">
                    </div>

                    <div class="pt-6">
                        <button type="submit"
                            class="w-full sm:w-auto bg-blue-600 hover:bg-blue-500 text-white font-black py-5 px-14 rounded-2xl shadow-xl shadow-blue-600/20 transition-all active:scale-95 uppercase tracking-widest text-[10px]">
                            Update Profile Protocol
                        </button>
                    </div>
                </form>
            </div>

            <div class="space-y-10">
                {{-- Activity Log --}}
                <div class="p-10 rounded-[2.5rem] border bg-white shadow-2xl shadow-blue-900/5 h-fit"
                    style="border-color: rgba(37,99,235,0.1);">
                    <h3
                        class="text-[10px] font-black text-blue-600 uppercase tracking-[0.25em] mb-8 flex items-center gap-2">
                        <div class="w-1.5 h-4 bg-blue-600 rounded-full shadow-lg shadow-blue-600/30"></div>
                        {{ __('Account Logs') }}
                    </h3>

                    <div class="space-y-8">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-4">Registry
                                Clearance</p>
                            <div class="p-5 rounded-2xl bg-blue-50/50 border border-blue-50 flex items-center gap-4">
                                <div class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></div>
                                <p class="text-[10px] text-blue-600 font-black uppercase tracking-widest">Active Session
                                </p>
                            </div>
                        </div>

                        <div class="pt-8 border-t border-slate-100">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-6">Recent
                                Payment Activity</p>

                            @if (isset($payments) && count($payments) > 0)
                                @foreach ($payments as $payment)
                                    <div
                                        class="flex justify-between items-center mb-5 pb-5 border-b border-slate-50 last:border-0 last:pb-0">
                                        <div>
                                            <div class="text-sm font-black text-slate-900 tracking-tight">
                                                ₱{{ number_format($payment['amount'], 2) }}</div>
                                            <div
                                                class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-widest">
                                                {{ $payment['date'] }}</div>
                                        </div>
                                        <span
                                            class="bg-emerald-50 text-emerald-600 border border-emerald-100 text-[8px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest shadow-sm">
                                            {{ $payment['status'] }}
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <div
                                    class="flex justify-between items-center bg-slate-50 p-6 rounded-2xl border border-slate-100">
                                    <div>
                                        <div class="text-sm font-black text-slate-900">₱0.00</div>
                                        <div class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-widest">
                                            No activity</div>
                                    </div>
                                    <span
                                        class="bg-white text-slate-300 border border-slate-100 text-[8px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest shadow-sm">VACANT</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Password Security --}}
                <div class="p-10 rounded-[2.5rem] border bg-white shadow-2xl shadow-blue-900/5 transition-all hover:shadow-rose-900/5"
                    style="border-color: rgba(37,99,235,0.1);">
                    <h3
                        class="text-[10px] font-black text-rose-600 uppercase tracking-[0.25em] mb-8 flex items-center gap-2">
                        <div class="w-1.5 h-4 bg-rose-500 rounded-full shadow-lg shadow-rose-500/30"></div>
                        {{ __('Account Security') }}
                    </h3>

                    <form action="{{ route('student.profile.password') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-3">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Current
                                Password</label>
                            <div class="relative flex items-center">
                                <input type="password" id="current_password" name="current_password"
                                    class="w-full bg-white border border-blue-500/10 rounded-2xl pl-5 pr-12 py-4 text-slate-900 font-black text-[12px] focus:border-rose-500 outline-none transition-all shadow-sm focus:ring-4 focus:ring-rose-500/5 placeholder-slate-200"
                                    placeholder="••••••••">
                                <button type="button" onclick="togglePasswordVisibility('current_password', this)"
                                    class="absolute right-4 text-slate-400 hover:text-rose-500 focus:outline-none transition-colors flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">New
                                Password</label>
                            <div class="relative flex items-center">
                                <input type="password" id="password" name="password"
                                    class="w-full bg-white border border-blue-500/10 rounded-2xl pl-5 pr-12 py-4 text-slate-900 font-black text-[12px] focus:border-rose-500 outline-none transition-all shadow-sm focus:ring-4 focus:ring-rose-500/5 placeholder-slate-200"
                                    placeholder="••••••••">
                                <button type="button" onclick="togglePasswordVisibility('password', this)"
                                    class="absolute right-4 text-slate-400 hover:text-rose-500 focus:outline-none transition-colors flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Confirm
                                New Password</label>
                            <div class="relative flex items-center">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="w-full bg-white border border-blue-500/10 rounded-2xl pl-5 pr-12 py-4 text-slate-900 font-black text-[12px] focus:border-rose-500 outline-none transition-all shadow-sm focus:ring-4 focus:ring-rose-500/5 placeholder-slate-200"
                                    placeholder="••••••••">
                                <button type="button"
                                    onclick="togglePasswordVisibility('password_confirmation', this)"
                                    class="absolute right-4 text-slate-400 hover:text-rose-500 focus:outline-none transition-colors flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit"
                                class="w-full bg-rose-600 hover:bg-rose-500 text-white font-black py-5 px-6 rounded-2xl shadow-xl shadow-rose-600/20 transition-all active:scale-95 uppercase tracking-widest text-[10px]">
                                Update Security Protocol
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            const svg = btn.querySelector('svg');
            if (input.type === 'password') {
                input.type = 'text';
                svg.innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                input.type = 'password';
                svg.innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }
    </script>
</x-layouts.student>
