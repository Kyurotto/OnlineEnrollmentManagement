<section>
    <header class="mb-10">
        <h3 class="text-xl font-black text-slate-900 mb-2 flex items-center gap-3">
            <div class="w-1.5 h-6 bg-rose-500 rounded-full shadow-lg shadow-rose-500/20"></div>
            {{ __('ACCOUNT SECURITY') }}
        </h3>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-8">
        @csrf
        @method('put')

        <div class="space-y-3">
            <label for="update_password_current_password"
                class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ __('Current Password') }}</label>
            <div class="relative flex items-center">
                <input id="update_password_current_password" name="current_password" type="password"
                    autocomplete="current-password" placeholder="••••••••"
                    class="w-full pl-5 pr-12 py-4 bg-slate-50 border border-transparent rounded-2xl text-slate-900 placeholder-slate-200 font-black text-[11px] focus:bg-white focus:border-rose-500/20 focus:ring-4 focus:ring-rose-500/5 outline-none transition-all shadow-sm">
                <button type="button" onclick="togglePasswordVisibility('update_password_current_password', this)"
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
            @if ($errors->updatePassword->get('current_password'))
                <p class="text-[9px] font-black text-rose-500 uppercase tracking-widest mt-2 ml-1">
                    {{ $errors->updatePassword->get('current_password')[0] }}</p>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-3">
                <label for="update_password_password"
                    class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ __('New Password') }}</label>
                <div class="relative flex items-center">
                    <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full pl-5 pr-12 py-4 bg-slate-50 border border-transparent rounded-2xl text-slate-900 placeholder-slate-200 font-black text-[11px] focus:bg-white focus:border-rose-500/20 focus:ring-4 focus:ring-rose-500/5 outline-none transition-all shadow-sm">
                    <button type="button" onclick="togglePasswordVisibility('update_password_password', this)"
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
                @if ($errors->updatePassword->get('password'))
                    <p class="text-[9px] font-black text-rose-500 uppercase tracking-widest mt-2 ml-1">
                        {{ $errors->updatePassword->get('password')[0] }}</p>
                @endif
            </div>

            <div class="space-y-3">
                <label for="update_password_password_confirmation"
                    class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ __('Confirm New Password') }}</label>
                <div class="relative flex items-center">
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                        autocomplete="new-password" placeholder="••••••••"
                        class="w-full pl-5 pr-12 py-4 bg-slate-50 border border-transparent rounded-2xl text-slate-900 placeholder-slate-200 font-black text-[11px] focus:bg-white focus:border-rose-500/20 focus:ring-4 focus:ring-rose-500/5 outline-none transition-all shadow-sm">
                    <button type="button"
                        onclick="togglePasswordVisibility('update_password_password_confirmation', this)"
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
                @if ($errors->updatePassword->get('password_confirmation'))
                    <p class="text-[9px] font-black text-rose-500 uppercase tracking-widest mt-2 ml-1">
                        {{ $errors->updatePassword->get('password_confirmation')[0] }}</p>
                @endif
            </div>
        </div>

        <div class="pt-6">
            <button type="submit"
                class="w-full bg-rose-500 hover:bg-rose-600 text-white font-black py-5 px-10 rounded-2xl uppercase tracking-[0.2em] transition-all text-[11px] shadow-xl shadow-rose-500/20 active:scale-[0.98]">
                {{ __('Change Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                    class="text-center mt-4 text-[9px] font-black text-emerald-600 uppercase tracking-widest">
                    {{ __('Security Sync Complete.') }}</p>
            @endif
        </div>
    </form>

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
</section>
