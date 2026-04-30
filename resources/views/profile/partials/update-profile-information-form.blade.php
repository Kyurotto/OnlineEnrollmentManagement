<section>
    <header class="mb-10">
        <h3 class="text-xl font-black text-slate-900 mb-2 flex items-center gap-3">
            <div class="w-1.5 h-6 bg-emerald-500 rounded-full shadow-lg shadow-emerald-500/20"></div>
            {{ __('PERSONAL INFORMATION') }}
        </h3>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-8">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-3">
                <label for="first_name" class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ __('First Name') }}</label>
                <input id="first_name" name="first_name" type="text" value="{{ old('first_name', $user->first_name) }}" required autofocus autocomplete="given-name"
                    class="w-full px-5 py-4 bg-slate-50 border border-transparent rounded-2xl text-slate-900 placeholder-slate-200 font-black text-[11px] focus:bg-white focus:border-emerald-500/20 focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all shadow-sm">
                @if($errors->get('first_name'))
                    <p class="text-[9px] font-black text-rose-500 uppercase tracking-widest mt-2 ml-1">{{ $errors->get('first_name')[0] }}</p>
                @endif
            </div>

            <div class="space-y-3">
                <label for="middle_name" class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ __('Middle Name') }}</label>
                <input id="middle_name" name="middle_name" type="text" value="{{ old('middle_name', $user->middle_name) }}" autocomplete="additional-name"
                    class="w-full px-5 py-4 bg-slate-50 border border-transparent rounded-2xl text-slate-900 placeholder-slate-200 font-black text-[11px] focus:bg-white focus:border-emerald-500/20 focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all shadow-sm">
                @if($errors->get('middle_name'))
                    <p class="text-[9px] font-black text-rose-500 uppercase tracking-widest mt-2 ml-1">{{ $errors->get('middle_name')[0] }}</p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-3">
                <label for="last_name" class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ __('Last Name') }}</label>
                <input id="last_name" name="last_name" type="text" value="{{ old('last_name', $user->last_name) }}" required autocomplete="family-name"
                    class="w-full px-5 py-4 bg-slate-50 border border-transparent rounded-2xl text-slate-900 placeholder-slate-200 font-black text-[11px] focus:bg-white focus:border-emerald-500/20 focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all shadow-sm">
                @if($errors->get('last_name'))
                    <p class="text-[9px] font-black text-rose-500 uppercase tracking-widest mt-2 ml-1">{{ $errors->get('last_name')[0] }}</p>
                @endif
            </div>

            <div class="space-y-3">
                <label for="email" class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ __('Email Address (Read-only)') }}</label>
                <input id="email" name="email" type="email" value="{{ $user->email }}" readonly autocomplete="username"
                    class="w-full px-5 py-4 bg-slate-50/50 border border-dashed border-slate-200 rounded-2xl text-slate-400 font-black text-[11px] outline-none cursor-not-allowed transition-all select-none">
            </div>
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-black py-5 px-10 rounded-2xl uppercase tracking-[0.2em] transition-all text-[11px] shadow-xl shadow-emerald-500/20 active:scale-[0.98]">
                {{ __('Update Profile') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-center mt-4 text-[9px] font-black text-emerald-600 uppercase tracking-widest"
                >{{ __('Changes Synchronized.') }}</p>
            @endif
        </div>
    </form>
</section>
