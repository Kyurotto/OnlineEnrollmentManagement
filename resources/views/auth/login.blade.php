<x-guest-layout>
    <style>
        body {
            background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%) !important;
            background-attachment: fixed !important;
            font-family: 'Inter', sans-serif;
            color: #0f172a;
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

                0%,
                49.99% {
                    opacity: 0;
                    z-index: 1;
                }

                50%,
                100% {
                    opacity: 1;
                    z-index: 5;
                }
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
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 32px 120px rgba(0, 0, 0, 0.05);
        }
    </style>

    <div class="fixed inset-0 w-screen h-screen flex items-center justify-center p-4 overflow-hidden">
        <div class="absolute top-[20%] left-[10%] w-[30%] h-[30%] bg-blue-500/5 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-[20%] right-[10%] w-[30%] h-[30%] bg-blue-400/5 blur-[120px] rounded-full"></div>

        <div id="container"
            class="relative glass-card md:rounded-[40px] overflow-hidden w-full md:w-[900px] max-w-full min-h-[600px] flex flex-col md:block animate-in fade-in zoom-in duration-700">

            <div class="md:hidden flex w-full bg-slate-50 border-b border-slate-100 backdrop-blur-md">
                <button id="mobileSignInBtn"
                    class="w-1/2 py-5 font-black text-sm uppercase tracking-widest text-blue-600 transition-all bg-blue-50 border-b-2 border-blue-600">Log
                    In</button>
                <button id="mobileSignUpBtn"
                    class="w-1/2 py-5 font-black text-sm uppercase tracking-widest text-slate-400 hover:text-blue-600 transition-all">Sign
                    Up</button>
            </div>

            <div id="mobile-sign-up"
                class="hidden md:block sign-up-container md:absolute top-0 h-full left-0 w-full md:w-1/2 md:opacity-0 md:z-[1] transition-all duration-[700ms] cubic-bezier(0.4, 0, 0.2, 1) py-10 md:py-0 bg-transparent">
                <form method="POST" action="{{ route('register') }}"
                    class="flex items-center justify-center flex-col px-8 md:px-16 h-full text-center">
                    @csrf
                    <div class="mb-10 text-center">
                        <h2 class="font-black text-3xl text-slate-900 mb-2 tracking-tighter uppercase leading-none">
                            Register</h2>
                        <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">Create Student Account
                        </p>
                    </div>

                    <div class="w-full space-y-4">
                        <div class="relative group">
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required
                                autofocus placeholder="FULL NAME"
                                class="bg-slate-50 text-slate-900 border border-slate-200 py-4 px-6 w-full rounded-2xl outline-none placeholder-slate-300 text-sm font-bold tracking-wider focus:border-blue-500 focus:bg-white transition-all" />
                        </div>
                        <x-input-error :messages="$errors->get('name')"
                            class="mt-1 text-rose-500 text-xs font-black uppercase tracking-tighter" />

                        <div class="relative group">
                            <input id="email_reg" type="email" name="email" value="{{ old('email') }}" required
                                placeholder="EMAIL"
                                class="bg-slate-50 text-slate-900 border border-slate-200 py-4 px-6 w-full rounded-2xl outline-none placeholder-slate-300 text-sm font-bold tracking-wider focus:border-blue-500 focus:bg-white transition-all" />
                        </div>
                        <x-input-error :messages="$errors->get('email')"
                            class="mt-1 text-rose-500 text-xs font-black uppercase tracking-tighter" />

                        <div class="relative group">
                            <div class="relative flex items-center">
                                <input id="password_reg" type="password" name="password" required placeholder="PASSWORD"
                                    class="bg-slate-50 text-slate-900 border border-slate-200 py-4 pl-6 pr-12 w-full rounded-2xl outline-none placeholder-slate-300 text-sm font-bold tracking-wider focus:border-blue-500 focus:bg-white transition-all" />
                                <button type="button" onclick="togglePasswordVisibility('password_reg', this)"
                                    class="absolute right-4 text-slate-400 hover:text-blue-600 focus:outline-none transition-colors flex items-center justify-center">
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
                        <x-input-error :messages="$errors->get('password')"
                            class="mt-1 text-rose-500 text-xs font-black uppercase tracking-tighter" />

                        <div class="relative group">
                            <div class="relative flex items-center">
                                <input id="password_confirmation" type="password" name="password_confirmation" required
                                    placeholder="CONFIRM PASSWORD"
                                    class="bg-slate-50 text-slate-900 border border-slate-200 py-4 pl-6 pr-12 w-full rounded-2xl outline-none placeholder-slate-300 text-sm font-bold tracking-wider focus:border-blue-500 focus:bg-white transition-all" />
                                <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)"
                                    class="absolute right-4 text-slate-400 hover:text-blue-600 focus:outline-none transition-colors flex items-center justify-center">
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

                    <button type="submit"
                        class="mt-10 w-full rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black py-5 px-12 tracking-[0.2em] uppercase transition-all shadow-lg shadow-blue-600/20 active:scale-[0.98] focus:outline-none">
                        Register
                    </button>

                    <button type="button" id="signIn"
                        class="mt-8 text-slate-400 hover:text-blue-600 font-bold text-sm uppercase tracking-widest transition-colors py-2">
                        Have Already Account
                    </button>
                </form>
            </div>

            <div id="mobile-sign-in"
                class="block md:block sign-in-container md:absolute top-0 h-full left-0 w-full md:w-1/2 md:z-[2] transition-all duration-[700ms] cubic-bezier(0.4, 0, 0.2, 1) py-10 md:py-0 flex-grow bg-transparent md:border-r border-slate-100">
                <form method="POST" action="{{ route('login') }}"
                    class="flex items-center justify-center flex-col px-8 md:px-16 h-full text-center">
                    @csrf
                    <div class="mb-12">
                        <h2 class="font-black text-4xl text-slate-900 mb-2 tracking-tighter uppercase leading-none">Log
                            In</h2>
                        <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">Portal Access</p>
                    </div>

                    <div class="w-full space-y-4">
                        <div class="relative group text-left">
                            <label
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 mb-1 block">Email
                                Address</label>
                            <input id="email" type="text" name="email" value="{{ old('email') }}" required
                                autofocus placeholder="your@email.com"
                                class="bg-slate-50 text-slate-900 border border-slate-200 py-4 px-6 w-full rounded-2xl outline-none placeholder-slate-300 text-sm font-bold tracking-wider focus:border-blue-500 focus:bg-white transition-all" />
                            <x-input-error :messages="$errors->get('email')"
                                class="mt-1 text-rose-500 text-xs font-black uppercase tracking-tighter ml-2" />
                        </div>

                        <div class="relative group text-left">
                            <label
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 mb-1 block">Password</label>
                            <div class="relative flex items-center">
                                <input id="password" type="password" name="password" required
                                    placeholder="••••••••"
                                    class="bg-slate-50 text-slate-900 border border-slate-200 py-4 pl-6 pr-12 w-full rounded-2xl outline-none placeholder-slate-300 text-sm font-bold tracking-wider focus:border-blue-500 focus:bg-white transition-all" />
                                <button type="button" onclick="togglePasswordVisibility('password', this)"
                                    class="absolute right-4 text-slate-400 hover:text-blue-600 focus:outline-none transition-colors flex items-center justify-center">
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
                            <x-input-error :messages="$errors->get('password')"
                                class="mt-1 text-rose-500 text-xs font-black uppercase tracking-tighter ml-2" />
                        </div>
                    </div>

                    <div class="w-full flex justify-end mt-4 px-2">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-[10px] font-black text-slate-400 hover:text-blue-600 uppercase tracking-widest transition-colors">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <button type="submit"
                        class="mt-10 w-full rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-black py-5 px-12 tracking-[0.2em] uppercase transition-all shadow-lg shadow-blue-600/20 active:scale-[0.98] focus:outline-none">
                        Sign In
                    </button>

                    <button type="button" id="signUp"
                        class="mt-8 text-slate-400 hover:text-blue-600 font-bold text-sm uppercase tracking-widest transition-colors py-2">
                        Create New Account
                    </button>
                </form>
            </div>

            <div
                class="hidden md:block overlay-container absolute top-0 left-1/2 w-1/2 h-full overflow-hidden transition-all duration-[700ms] cubic-bezier(0.4, 0, 0.2, 1) z-[100] group">
                <div class="overlay text-white relative -left-full h-full w-[200%] transition-transform duration-[700ms] cubic-bezier(0.4, 0, 0.2, 1)"
                    style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);">

                    <div class="absolute inset-0 opacity-10 pointer-events-none"
                        style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.2) 1px, transparent 0); background-size: 32px 32px;">
                    </div>

                    <div
                        class="overlay-left absolute flex items-center justify-center flex-col px-16 text-center top-0 h-full w-1/2 -translate-x-[20%] transition-transform duration-[700ms] cubic-bezier(0.4, 0, 0.2, 1)">
                        <div
                            class="w-28 h-28 rounded-full bg-white/10 border border-white/20 flex items-center justify-center mb-10 shadow-2xl transform rotate-6 group-hover:rotate-0 transition-transform duration-700 overflow-hidden">
                            <img src="{{ asset('image/logo.jfif') }}" alt="NTC Logo"
                                class="h-full w-full object-cover">
                        </div>
                        <h2 class="font-black text-4xl text-white tracking-tighter uppercase leading-none">Log In</h2>
                        <p
                            class="text-xs font-black text-white/70 uppercase tracking-[0.4em] mt-4 mb-12 leading-relaxed">
                            System Entry Port
                        </p>
                    </div>

                    <div
                        class="overlay-right absolute flex items-center justify-center flex-col px-16 text-center top-0 h-full w-1/2 right-0 translate-x-0 transition-transform duration-[700ms] cubic-bezier(0.4, 0, 0.2, 1)">
                        <div
                            class="w-28 h-28 rounded-full bg-white/10 border border-white/20 flex items-center justify-center mb-10 shadow-2xl transform -rotate-6 group-hover:rotate-0 transition-transform duration-700 overflow-hidden">
                            <img src="{{ asset('image/logo.jfif') }}" alt="NTC Logo"
                                class="h-full w-full object-cover">
                        </div>
                        <h2 class="font-black text-4xl text-white tracking-tighter uppercase leading-none">Register
                        </h2>
                        <p
                            class="text-xs font-black text-white/70 uppercase tracking-[0.4em] mt-4 mb-12 leading-relaxed">
                            Personnel Enrollment
                        </p>
                    </div>
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

            mobileSignUpBtn.classList.add('text-blue-600', 'bg-blue-50', 'border-b-2', 'border-blue-600');
            mobileSignUpBtn.classList.remove('text-slate-400');
            mobileSignInBtn.classList.remove('text-blue-600', 'bg-blue-50', 'border-b-2', 'border-blue-600');
            mobileSignInBtn.classList.add('text-slate-400');
        });

        mobileSignInBtn.addEventListener('click', () => {
            mobileSignUpForm.classList.add('hidden');
            mobileSignUpForm.classList.remove('block');
            mobileSignInForm.classList.remove('hidden');
            mobileSignInForm.classList.add('block');

            mobileSignInBtn.classList.add('text-blue-600', 'bg-blue-50', 'border-b-2', 'border-blue-600');
            mobileSignInBtn.classList.remove('text-slate-400');
            mobileSignUpBtn.classList.remove('text-blue-600', 'bg-blue-50', 'border-b-2', 'border-blue-600');
            mobileSignUpBtn.classList.add('text-slate-400');
        });

        @if ($errors->has('name') || $errors->has('password_confirmation'))
            if (window.innerWidth < 768) {
                mobileSignUpBtn.click();
            }
        @endif
    </script>
</x-guest-layout>
