<section class="space-y-6">
    <header class="mb-8">
        <h2 class="text-[10px] font-black text-rose-600 uppercase tracking-[0.25em] mb-2 flex items-center gap-2">
            <div class="w-1.5 h-4 bg-rose-600 rounded-full shadow-lg shadow-rose-600/30"></div>
            {{ __('Terminal: Delete Account') }}
        </h2>
        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-relaxed">
            {{ __('Once your account is deleted, all of its resources and data will be permanently purged. This action is non-reversible.') }}
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-rose-600 hover:bg-rose-500 text-white font-black py-4 px-10 rounded-2xl uppercase tracking-[0.2em] transition-all text-[10px] shadow-xl shadow-rose-600/20 active:scale-95"
    >{{ __('Delete this Account') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-10 bg-white rounded-[2.5rem]">
            @csrf
            @method('delete')

            <h2 class="text-xl font-black text-slate-900 tracking-tight mb-4">
                {{ __('Purge Confirmation Required') }}
            </h2>

            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest leading-relaxed mb-8">
                {{ __('Please enter your security credentials to confirm account termination. This will permanently remove all associated data from the core database.') }}
            </p>

            <div class="space-y-3">
                <label for="password" class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 sr-only">{{ __('Password') }}</label>
                <input id="password" name="password" type="password" placeholder="{{ __('Security Password') }}"
                    class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 placeholder-slate-300 font-black text-[12px] focus:border-rose-500 focus:ring-4 focus:ring-rose-500/5 outline-none transition-all">
                @if($errors->userDeletion->get('password'))
                    <p class="text-[9px] font-black text-rose-500 uppercase tracking-widest mt-2 ml-1">{{ $errors->userDeletion->get('password')[0] }}</p>
                @endif
            </div>

            <div class="mt-10 flex justify-end gap-4">
                <button type="button" x-on:click="$dispatch('close')" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-black py-4 px-8 rounded-2xl uppercase tracking-[0.2em] transition-all text-[10px] active:scale-95">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" class="bg-rose-600 hover:bg-rose-500 text-white font-black py-4 px-8 rounded-2xl uppercase tracking-[0.2em] transition-all text-[10px] shadow-xl shadow-rose-600/20 active:scale-95">
                    {{ __('Confirm Purge') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
