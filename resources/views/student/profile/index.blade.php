<x-layouts.student title="Student Profile">
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8 animate-in fade-in duration-700">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Student Profile</h2>
                <p class="text-[10px] mt-2 font-black uppercase tracking-[0.2em] text-slate-400">Account Management &
                    Security</p>
            </div>
            <a href="{{ route('student.dashboard') }}"
                class="flex items-center gap-3 px-8 py-3.5 rounded-full bg-white border border-slate-100 text-[11px] font-black text-indigo-600 uppercase tracking-[0.2em] hover:bg-indigo-50 transition-all shadow-lg shadow-indigo-600/5 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>
        </div>

        <div class="space-y-8">

            <!-- Profile Section -->
            <div class="bg-white border border-slate-100 rounded-[2rem] p-10 shadow-2xl shadow-blue-900/5">
                <h2
                    class="text-xl font-black text-slate-900 uppercase tracking-[0.2em] mb-10 border-b border-slate-50 pb-6 flex items-center gap-4">
                    <div class="w-1.5 h-6 bg-emerald-500 rounded-full shadow-lg shadow-emerald-500/30"></div>
                    Personal Information
                </h2>

                @if (session('profile-updated'))
                    <div
                        class="mb-8 p-5 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-600 text-xs font-black uppercase tracking-widest flex items-center gap-3 animate-in slide-in-from-top-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        {{ session('profile-updated') }}
                    </div>
                @endif

                <form action="{{ route('student.profile.update') }}" method="POST"
                    class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-3">
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mb-1 ml-1">First
                            Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-slate-900 focus:border-emerald-500 focus:bg-white outline-none transition-all font-black uppercase text-xs tracking-widest shadow-sm focus:ring-4 focus:ring-emerald-500/5">
                        @error('first_name')
                            <p class="text-rose-500 text-[10px] mt-2 font-black tracking-wide">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-3">
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mb-1 ml-1">Middle
                            Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}"
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-slate-900 focus:border-emerald-500 focus:bg-white outline-none transition-all font-black uppercase text-xs tracking-widest shadow-sm focus:ring-4 focus:ring-emerald-500/5">
                    </div>

                    <div class="space-y-3">
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mb-1 ml-1">Last
                            Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-slate-900 focus:border-emerald-500 focus:bg-white outline-none transition-all font-black uppercase text-xs tracking-widest shadow-sm focus:ring-4 focus:ring-emerald-500/5">
                        @error('last_name')
                            <p class="text-rose-500 text-[10px] mt-2 font-black tracking-wide">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-3">
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mb-1 ml-1">Email
                            Address (Read-Only)</label>
                        <input type="email" value="{{ $user->email }}" disabled
                            class="w-full bg-slate-50/50 border border-slate-100 rounded-2xl px-6 py-4 text-slate-400 cursor-not-allowed font-black uppercase text-xs tracking-widest border-dashed select-none">
                    </div>

                    <div class="md:col-span-2 pt-6">
                        <button type="submit"
                            class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-black uppercase tracking-[0.25em] py-5 rounded-2xl transition-all active:scale-[0.98] shadow-xl shadow-emerald-500/20 text-xs">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- Security Section -->
            <div class="bg-white border border-slate-100 rounded-[2rem] p-10 shadow-2xl shadow-blue-900/5">
                <h2
                    class="text-xl font-black text-slate-900 uppercase tracking-[0.2em] mb-10 border-b border-slate-50 pb-6 flex items-center gap-4">
                    <div class="w-1.5 h-6 bg-rose-500 rounded-full shadow-lg shadow-rose-500/30"></div>
                    Account Security
                </h2>

                @if (session('password-updated'))
                    <div
                        class="mb-8 p-5 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-600 text-xs font-black uppercase tracking-widest flex items-center gap-3 animate-in slide-in-from-top-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        {{ session('password-updated') }}
                    </div>
                @endif

                <form action="{{ route('student.profile.password') }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-3">
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mb-1 ml-1">Current
                            Password</label>
                        <div class="relative flex items-center">
                            <input type="password" id="current_password" name="current_password" placeholder="••••••••"
                                class="w-full bg-slate-50 border border-slate-100 rounded-2xl pl-6 pr-12 py-4 text-slate-900 focus:border-rose-500 focus:bg-white outline-none transition-all font-black uppercase text-xs tracking-widest shadow-sm focus:ring-4 focus:ring-rose-500/5 placeholder-slate-300">
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
                        @error('current_password')
                            <p class="text-rose-500 text-[10px] mt-2 font-black tracking-wide">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mb-1 ml-1">New
                                Password</label>
                            <div class="relative flex items-center">
                                <input type="password" id="password" name="password" placeholder="••••••••"
                                    class="w-full bg-slate-50 border border-slate-100 rounded-2xl pl-6 pr-12 py-4 text-slate-900 focus:border-rose-500 focus:bg-white outline-none transition-all font-black uppercase text-xs tracking-widest shadow-sm focus:ring-4 focus:ring-rose-500/5 placeholder-slate-300">
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
                            @error('password')
                                <p class="text-rose-500 text-[10px] mt-2 font-black tracking-wide">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-3">
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mb-1 ml-1">Confirm
                                New Password</label>
                            <div class="relative flex items-center">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    placeholder="••••••••"
                                    class="w-full bg-slate-50 border border-slate-100 rounded-2xl pl-6 pr-12 py-4 text-slate-900 focus:border-rose-500 focus:bg-white outline-none transition-all font-black uppercase text-xs tracking-widest shadow-sm focus:ring-4 focus:ring-rose-500/5 placeholder-slate-300">
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
                    </div>

                    <div class="pt-6">
                        <button type="submit"
                            class="w-full bg-rose-500 hover:bg-rose-600 text-white font-black uppercase tracking-[0.25em] py-5 rounded-2xl transition-all active:scale-[0.98] shadow-xl shadow-rose-500/20 text-xs">
                            Change Password
                        </button>
                    </div>
                </form>
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
