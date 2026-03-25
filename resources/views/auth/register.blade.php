<x-guest-layout>
    <style>
        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="fixed inset-0 w-screen h-screen bg-[#060d1a] flex justify-center items-center flex-col z-[9999] font-sans overflow-hidden fade-in px-4">
        <!-- Ambient Background Glows -->
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-cyan-500/10 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-600/10 blur-[120px] rounded-full"></div>

        <div class="bg-white/[0.03] backdrop-blur-3xl border border-white/10 rounded-[32px] shadow-[0_32px_120px_rgba(0,0,0,0.6)] relative overflow-hidden w-full max-w-[480px] p-10 md:p-14 flex flex-col items-center text-center">
            
            <div class="mb-10 text-center">
                <h1 class="font-black text-3xl text-white mb-2 tracking-tight uppercase">Join Us</h1>
                <p class="text-cyan-400/60 text-[10px] font-bold uppercase tracking-[0.2em]">Create your account</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="w-full space-y-5">
                @csrf

                <div class="relative group">
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="FULL NAME"
                        class="bg-white/[0.03] text-white border border-white/10 py-4 px-6 w-full rounded-2xl outline-none placeholder-white/20 text-xs font-bold tracking-wider focus:border-cyan-500/50 focus:bg-white/[0.05] transition-all shadow-inner" />
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-1 text-rose-500 text-[10px] font-black uppercase tracking-tighter text-left ml-2" />

                <div class="relative group">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="EMAIL ADDRESS"
                        class="bg-white/[0.03] text-white border border-white/10 py-4 px-6 w-full rounded-2xl outline-none placeholder-white/20 text-xs font-bold tracking-wider focus:border-cyan-500/50 focus:bg-white/[0.05] transition-all shadow-inner" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-rose-500 text-[10px] font-black uppercase tracking-tighter text-left ml-2" />

                <div class="relative group">
                    <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="PASSWORD"
                        class="bg-white/[0.03] text-white border border-white/10 py-4 px-6 w-full rounded-2xl outline-none placeholder-white/20 text-xs font-bold tracking-wider focus:border-cyan-500/50 focus:bg-white/[0.05] transition-all shadow-inner" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-rose-500 text-[10px] font-black uppercase tracking-tighter text-left ml-2" />

                <div class="relative group">
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="CONFIRM PASSWORD"
                        class="bg-white/[0.03] text-white border border-white/10 py-4 px-6 w-full rounded-2xl outline-none placeholder-white/20 text-xs font-bold tracking-wider focus:border-cyan-500/50 focus:bg-white/[0.05] transition-all shadow-inner" />
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-rose-500 text-[10px] font-black uppercase tracking-tighter text-left ml-2" />

                <button type="submit" class="mt-8 w-full rounded-2xl bg-cyan-500 hover:bg-cyan-400 text-white text-[11px] font-black py-4 px-12 tracking-[0.2em] uppercase transition-all shadow-[0_8px_30px_rgba(34,211,238,0.2)] active:scale-[0.98] focus:outline-none">
                    Establish Account
                </button>

                <div class="pt-6">
                    <a class="text-[10px] font-black text-white/40 hover:text-cyan-400 uppercase tracking-widest transition-colors py-2" href="{{ route('login') }}">
                        Already have an account? Log In
                    </a>
                </div>
            </form>

        </div>
    </div>
</x-guest-layout>
