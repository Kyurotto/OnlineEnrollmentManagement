<x-guest-layout>
    <style>
        body {
            background: linear-gradient(135deg, #060d1a 0%, #0d1f3c 50%, #1a3a6e 100%) !important;
            background-attachment: fixed !important;
            font-family: 'Inter', sans-serif;
        }

        @media (min-width: 768px) {
            .right-panel-active .sign-in-container {
                transform: translateX(100%);
                opacity: 0;
            }

            .right-panel-active .sign-up-container {
                transform: translateX(100%);
                opacity: 1;
                z-index: 5;
                animation: show 0.6s;
            }

            @keyframes show {
                0%, 49.99% { opacity: 0; z-index: 1; }
                50%, 100% { opacity: 1; z-index: 5; }
            }

            .right-panel-active .overlay-container {
                transform: translateX(-100%);
            }
            .right-panel-active .overlay {
                transform: translateX(50%);
            }
            .right-panel-active .overlay-left {
                transform: translateX(0);
            }
            .right-panel-active .overlay-right {
                transform: translateX(20%);
            }
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 32px 120px rgba(0, 0, 0, 0.5);
        }
    </style>

    <div class="fixed inset-0 w-screen h-screen flex items-center justify-center p-4 overflow-hidden">
        <div class="absolute top-[20%] left-[10%] w-[30%] h-[30%] bg-blue-500/10 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-[20%] right-[10%] w-[30%] h-[30%] bg-emerald-500/10 blur-[120px] rounded-full"></div>

        <div id="container" class="relative glass-card md:rounded-[40px] overflow-hidden w-full md:w-[900px] max-w-full min-h-[600px] flex flex-col md:block animate-in fade-in zoom-in duration-700">

                <div class="md:hidden flex w-full bg-white/5 border-b border-white/10 backdrop-blur-md">
                    <button id="mobileSignInBtn" class="w-1/2 py-5 font-black text-sm uppercase tracking-widest text-white transition-all bg-blue-500/20 border-b-2 border-blue-500">Log In</button>
                    <button id="mobileSignUpBtn" class="w-1/2 py-5 font-black text-sm uppercase tracking-widest text-white/30 hover:text-white transition-all">Sign Up</button>
                </div>

                <div id="mobile-sign-up" class="hidden md:block sign-up-container md:absolute top-0 h-full left-0 w-full md:w-1/2 md:opacity-0 md:z-[1] transition-all duration-[700ms] cubic-bezier(0.4, 0, 0.2, 1) py-10 md:py-0 bg-transparent">
                    <form method="POST" action="{{ route('register') }}" class="flex items-center justify-center flex-col px-8 md:px-16 h-full text-center">
                        @csrf
                        <div class="mb-10">
                            <h2 class="font-black text-3xl text-white mb-2 tracking-tighter uppercase leading-none">Register</h2>
                            <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest">Create Student Account</p>
                        </div>

                        <div class="w-full space-y-5">
                            <div class="relative group">
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="FULL NAME"
                                    class="bg-white/[0.03] text-white border border-white/10 py-4 px-6 w-full rounded-2xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-emerald-500/50 focus:bg-white/[0.05] transition-all shadow-inner" />
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-1 text-rose-500 text-xs font-black uppercase tracking-tighter" />

                            <div class="relative group">
                                <input id="email_reg" type="email" name="email" value="{{ old('email') }}" required placeholder="EMAIL"
                                    class="bg-white/[0.03] text-white border border-white/10 py-4 px-6 w-full rounded-2xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-emerald-500/50 focus:bg-white/[0.05] transition-all shadow-inner" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-rose-500 text-xs font-black uppercase tracking-tighter" />

                            <div class="relative group">
                                <input id="password_reg" type="password" name="password" required placeholder="PASSWORD"
                                    class="bg-white/[0.03] text-white border border-white/10 py-4 px-6 w-full rounded-2xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-emerald-500/50 focus:bg-white/[0.05] transition-all shadow-inner" />
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-rose-500 text-xs font-black uppercase tracking-tighter" />

                            <div class="relative group">
                                <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="CONFIRM PASSWORD"
                                    class="bg-white/[0.03] text-white border border-white/10 py-4 px-6 w-full rounded-2xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-emerald-500/50 focus:bg-white/[0.05] transition-all shadow-inner" />
                            </div>
                        </div>

                        <button type="submit" class="mt-12 w-full rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-black text-sm font-black py-5 px-12 tracking-[0.2em] uppercase transition-all shadow-[0_12px_40px_rgba(16,185,129,0.25)] active:scale-[0.98] focus:outline-none">
                            Register
                        </button>

                        <button type="button" id="signIn" class="mt-8 text-white/40 hover:text-emerald-400 font-bold text-sm uppercase tracking-widest transition-colors py-2">
                            Have Already Account
                        </button>
                    </form>
                </div>

                <div id="mobile-sign-in" class="block md:block sign-in-container md:absolute top-0 h-full left-0 w-full md:w-1/2 md:z-[2] transition-all duration-[700ms] cubic-bezier(0.4, 0, 0.2, 1) py-10 md:py-0 flex-grow bg-transparent border-r border-white/5">
                    <form method="POST" action="{{ route('login') }}" class="flex items-center justify-center flex-col px-8 md:px-16 h-full text-center">
                        @csrf
                        <div class="mb-12">
                            <h2 class="font-black text-4xl text-white mb-2 tracking-tighter uppercase leading-none italic">Log In</h2>
                            <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest">Portal Access</p>
                        </div>

                        <div class="w-full space-y-5">
                            <div class="relative group">
                                <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus placeholder="EMAIL ADDRESS"
                                    class="bg-white/[0.03] text-white border border-white/10 py-4 px-6 w-full rounded-2xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-blue-500/50 focus:bg-white/[0.05] transition-all shadow-inner" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-rose-500 text-xs font-black uppercase tracking-tighter text-left ml-2" />

                            <div class="relative group">
                                <input id="password" type="password" name="password" required placeholder="PASSWORD"
                                    class="bg-white/[0.03] text-white border border-white/10 py-4 px-6 w-full rounded-2xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-blue-500/50 focus:bg-white/[0.05] transition-all shadow-inner" />
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-rose-500 text-xs font-black uppercase tracking-tighter text-left ml-2" />
                        </div>

                        <div class="w-full flex justify-center mt-6 px-2">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-black text-white/20 hover:text-blue-400 uppercase tracking-widest transition-colors">
                                    Forgot Password?
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="mt-12 w-full rounded-2xl bg-blue-500 hover:bg-blue-400 text-white text-sm font-black py-5 px-12 tracking-[0.2em] uppercase transition-all shadow-[0_12px_40px_rgba(59,130,246,0.25)] active:scale-[0.98] focus:outline-none">
                            Sign In
                        </button>

                        <button type="button" id="signUp" class="mt-8 text-white/40 hover:text-blue-400 font-bold text-sm uppercase tracking-widest transition-colors py-2">
                            Create New Account
                        </button>
                    </form>
                </div>

                <div class="hidden md:block overlay-container absolute top-0 left-1/2 w-1/2 h-full overflow-hidden transition-all duration-[700ms] cubic-bezier(0.4, 0, 0.2, 1) z-[100] group">
                    <div class="overlay text-white relative -left-full h-full w-[200%] transition-transform duration-[700ms] cubic-bezier(0.4, 0, 0.2, 1)"
                         style="background: linear-gradient(135deg, #0d1f3c 0%, #060d1a 100%);">

                        <div class="absolute inset-0 opacity-10 pointer-events-none"
                             style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.05) 1px, transparent 0); background-size: 32px 32px;"></div>

                        <div class="overlay-left absolute flex items-center justify-center flex-col px-16 text-center top-0 h-full w-1/2 -translate-x-[20%] transition-transform duration-[700ms] cubic-bezier(0.4, 0, 0.2, 1)">
                            <div class="w-28 h-28 rounded-full bg-[#1a2333] border border-white/10 flex items-center justify-center mb-10 shadow-[0_20px_50px_rgba(0,0,0,0.5)] transform rotate-6 group-hover:rotate-0 transition-transform duration-700 overflow-hidden">
                                <img src="{{ asset('image/logo.jfif') }}" alt="NTC Logo" class="h-full w-full object-cover">
                            </div>
                            <h2 class="font-black text-4xl text-white tracking-tighter uppercase leading-none italic">Log In</h2>
                            <p class="text-xs font-black text-blue-400/50 uppercase tracking-[0.4em] mt-4 mb-12 leading-relaxed">
                                System Entry Port
                            </p>
                        </div>

                        <div class="overlay-right absolute flex items-center justify-center flex-col px-16 text-center top-0 h-full w-1/2 right-0 translate-x-0 transition-transform duration-[700ms] cubic-bezier(0.4, 0, 0.2, 1)">
                            <div class="w-28 h-28 rounded-full bg-[#1a2333] border border-white/10 flex items-center justify-center mb-10 shadow-[0_20px_50px_rgba(0,0,0,0.5)] transform -rotate-6 group-hover:rotate-0 transition-transform duration-700 overflow-hidden">
                                <img src="{{ asset('image/logo.jfif') }}" alt="NTC Logo" class="h-full w-full object-cover">
                            </div>
                            <h2 class="font-black text-4xl text-white tracking-tighter uppercase leading-none">Register</h2>
                            <p class="text-xs font-black text-emerald-400/50 uppercase tracking-[0.4em] mt-4 mb-12 leading-relaxed">
                                Personnel Enrollment
                            </p>
                        </div>
                    </div>
                </div>

        </div>
    </div>

    <script>
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');
        const overlayContainer = document.querySelector('.overlay-container');

        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
            overlayContainer.style.borderRadius = "40px 0 0 40px";
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
            overlayContainer.style.borderRadius = "0 40px 40px 0";
        });

        // Initialize State
        overlayContainer.style.borderRadius = "0 40px 40px 0";

        @if ($errors->has('name') || $errors->has('password_confirmation'))
            container.classList.add("right-panel-active");
            overlayContainer.style.borderRadius = "40px 0 0 40px";
        @endif

        const mobileSignInBtn = document.getElementById('mobileSignInBtn');
        const mobileSignUpBtn = document.getElementById('mobileSignUpBtn');
        const mobileSignInForm = document.getElementById('mobile-sign-in');
        const mobileSignUpForm = document.getElementById('mobile-sign-up');

        mobileSignUpBtn.addEventListener('click', () => {
            mobileSignInForm.classList.add('hidden');
            mobileSignInForm.classList.remove('block');
            mobileSignUpForm.classList.remove('hidden');
            mobileSignUpForm.classList.add('block');

            mobileSignUpBtn.classList.add('text-white', 'bg-emerald-500/20', 'border-b-2', 'border-emerald-500');
            mobileSignUpBtn.classList.remove('text-white/30');
            mobileSignInBtn.classList.remove('text-white', 'bg-blue-500/20', 'border-b-2', 'border-blue-500');
            mobileSignInBtn.classList.add('text-white/30');
        });

        mobileSignInBtn.addEventListener('click', () => {
            mobileSignUpForm.classList.add('hidden');
            mobileSignUpForm.classList.remove('block');
            mobileSignInForm.classList.remove('hidden');
            mobileSignInForm.classList.add('block');

            mobileSignInBtn.classList.add('text-white', 'bg-blue-500/20', 'border-b-2', 'border-blue-500');
            mobileSignInBtn.classList.remove('text-white/30');
            mobileSignUpBtn.classList.remove('text-white', 'bg-emerald-500/20', 'border-b-2', 'border-emerald-500');
            mobileSignUpBtn.classList.add('text-white/30');
        });

        @if ($errors->has('name') || $errors->has('password_confirmation'))
            if (window.innerWidth < 768) {
                mobileSignUpBtn.click();
            }
        @endif
    </script>
</x-guest-layout>
