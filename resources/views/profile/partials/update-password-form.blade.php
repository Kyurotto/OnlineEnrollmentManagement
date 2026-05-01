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
            <label for="update_password_current_password" class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ __('Current Password') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" placeholder="••••••••"
                class="w-full px-5 py-4 bg-slate-50 border border-transparent rounded-2xl text-slate-900 placeholder-slate-200 font-black text-[11px] focus:bg-white focus:border-rose-500/20 focus:ring-4 focus:ring-rose-500/5 outline-none transition-all shadow-sm">
            @if($errors->updatePassword->get('current_password'))
                <p class="text-[9px] font-black text-rose-500 uppercase tracking-widest mt-2 ml-1">{{ $errors->updatePassword->get('current_password')[0] }}</p>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-3">
                <label for="update_password_password" class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ __('New Password') }}</label>
                <input id="update_password_password" name="password" type="password" autocomplete="new-password" placeholder="••••••••"
                    class="w-full px-5 py-4 bg-slate-50 border border-transparent rounded-2xl text-slate-900 placeholder-slate-200 font-black text-[11px] focus:bg-white focus:border-rose-500/20 focus:ring-4 focus:ring-rose-500/5 outline-none transition-all shadow-sm">
                @if($errors->updatePassword->get('password'))
                    <p class="text-[9px] font-black text-rose-500 uppercase tracking-widest mt-2 ml-1">{{ $errors->updatePassword->get('password')[0] }}</p>
                @endif
            </div>

            <div class="space-y-3">
                <label for="update_password_password_confirmation" class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ __('Confirm New Password') }}</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="••••••••"
                    class="w-full px-5 py-4 bg-slate-50 border border-transparent rounded-2xl text-slate-900 placeholder-slate-200 font-black text-[11px] focus:bg-white focus:border-rose-500/20 focus:ring-4 focus:ring-rose-500/5 outline-none transition-all shadow-sm">
                @if($errors->updatePassword->get('password_confirmation'))
                    <p class="text-[9px] font-black text-rose-500 uppercase tracking-widest mt-2 ml-1">{{ $errors->updatePassword->get('password_confirmation')[0] }}</p>
                @endif
            </div>
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-black py-5 px-10 rounded-2xl uppercase tracking-[0.2em] transition-all text-[11px] shadow-xl shadow-rose-500/20 active:scale-[0.98]">
                {{ __('Change Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-center mt-4 text-[9px] font-black text-emerald-600 uppercase tracking-widest"
                >{{ __('Security Sync Complete.') }}</p>
            @endif
        </div>
    </form>
</section>
