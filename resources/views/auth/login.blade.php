<x-guest-layout>
    <style>
        @media (min-width: 768px) {
            .right-panel-active .sign-in-container {
                transform: translateX(100%);
            }

            .right-panel-active .sign-up-container {
                transform: translateX(100%);
                opacity: 1;
                z-index: 5;
                animation: show 0.6s;
            }

            @keyframes show {
                0%, 49.99% {
                    opacity: 0;
                    z-index: 1;
                }
                50%, 100% {
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
    </style>

    <div class="fixed inset-0 w-screen h-screen bg-gray-50 flex justify-center items-center flex-col z-[9999] font-sans overflow-y-auto py-10">

        <div id="container" class="bg-white md:rounded-[20px] shadow-2xl relative overflow-hidden w-full md:w-[768px] max-w-full min-h-screen md:min-h-[480px] flex flex-col md:block border border-gray-200">

            <div class="md:hidden flex w-full bg-white text-gray-900 border-b border-gray-200">
                <button id="mobileSignInBtn" class="w-1/2 py-4 font-bold bg-[#10B981] text-white">Log In</button>
                <button id="mobileSignUpBtn" class="w-1/2 py-4 font-bold text-gray-600">Sign Up</button>
            </div>

            <div id="mobile-sign-up" class="hidden md:block sign-up-container md:absolute top-0 h-full left-0 w-full md:w-1/2 md:opacity-0 md:z-[1] transition-all duration-[600ms] ease-in-out py-10 md:py-0 bg-white">
                <form method="POST" action="{{ route('register') }}" class="flex items-center justify-center flex-col px-8 md:px-12 h-full text-center">
                    @csrf
                    <h1 class="font-bold text-3xl m-0 text-gray-900 mb-4">Create Account</h1>

                    <input id="name" type="text" name="name" :value="old('name')" required autofocus placeholder="Name"
                        class="bg-gray-50 text-gray-900 border border-gray-300 py-3 px-4 my-2 w-full rounded-md outline-none focus:ring-2 focus:ring-[#10B981] placeholder-gray-400 shadow-sm" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-rose-500" />

                    <input id="email_reg" type="email" name="email" :value="old('email')" required placeholder="Email"
                        class="bg-gray-50 text-gray-900 border border-gray-300 py-3 px-4 my-2 w-full rounded-md outline-none focus:ring-2 focus:ring-[#10B981] placeholder-gray-400 shadow-sm" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-500" />

                    <input id="password_reg" type="password" name="password" required placeholder="Password"
                        class="bg-gray-50 text-gray-900 border border-gray-300 py-3 px-4 my-2 w-full rounded-md outline-none focus:ring-2 focus:ring-[#10B981] placeholder-gray-400 shadow-sm" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-500" />

                    <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Confirm Password"
                        class="bg-gray-50 text-gray-900 border border-gray-300 py-3 px-4 my-2 w-full rounded-md outline-none focus:ring-2 focus:ring-[#10B981] placeholder-gray-400 shadow-sm" />

                    <button type="submit" class="mt-6 rounded-full border border-[#10B981] bg-[#10B981] hover:bg-[#059669] hover:border-[#059669] text-white text-xs font-bold py-3 px-11 tracking-wider uppercase transition-colors duration-[200ms] ease-in active:scale-95 focus:outline-none shadow-md shadow-[#10B981]/20">
                        Sign Up
                    </button>
                    <button type="button" id="signIn" class="mt-2 text-gray-500 hover:text-gray-900 font-semibold text-xs py-3 px-11 transition-colors">
                            Sign In Instead
                        </button>
                </form>
            </div>

            <div id="mobile-sign-in" class="block md:block sign-in-container md:absolute top-0 h-full left-0 w-full md:w-1/2 md:z-[2] transition-all duration-[600ms] ease-in-out py-10 md:py-0 flex-grow bg-white">
                <form method="POST" action="{{ route('login') }}" class="flex items-center justify-center flex-col px-8 md:px-12 h-full text-center">
                    @csrf
                    <h1 class="font-bold text-3xl m-0 text-gray-900 mb-4">Sign in</h1>

                    <input id="email" type="text" name="email" :value="old('email')" required autofocus placeholder="Email or Username"
                        class="bg-gray-50 text-gray-900 border border-gray-300 py-3 px-4 my-2 w-full rounded-md outline-none focus:ring-2 focus:ring-[#10B981] placeholder-gray-400 shadow-sm" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-500" />

                    <input id="password" type="password" name="password" required placeholder="Password"
                        class="bg-gray-50 text-gray-900 border border-gray-300 py-3 px-4 my-2 w-full rounded-md outline-none focus:ring-2 focus:ring-[#10B981] placeholder-gray-400 shadow-sm" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-500" />

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-gray-500 text-sm no-underline my-4 hover:text-[#10B981] transition-colors font-semibold">
                            Forgot your password?
                        </a>
                    @endif

                    <button type="submit" class="mt-2 mb-2 rounded-full border border-[#10B981] bg-[#10B981] hover:bg-[#059669] hover:border-[#059669] text-white text-xs font-bold py-3 px-11 tracking-wider uppercase transition-colors duration-[200ms] ease-in active:scale-95 focus:outline-none shadow-md shadow-[#10B981]/20">
                        Sign In
                    </button>
                    <button type="button" id="signUp" class="mt-2 text-gray-500 hover:text-gray-900 font-semibold text-xs py-3 px-11 transition-colors">
                            Create Account
                        </button>
                </form>
            </div>

            <div class="hidden md:block overlay-container absolute top-0 left-1/2 w-1/2 h-full overflow-hidden transition-transform duration-[600ms] ease-in-out z-[100]">
                <div class="overlay bg-gradient-to-r from-[#10B981] to-[#059669] text-white relative -left-full h-full w-[200%] transition-transform duration-[600ms] ease-in-out">

                    <div class="overlay-left absolute flex items-center justify-center flex-col px-10 text-center top-0 h-full w-1/2 -translate-x-[20%] transition-transform duration-[600ms] ease-in-out">
                        <h1 class="font-bold text-3xl m-0 text-white">Welcome Back!</h1>
                        <p class="text-sm font-medium leading-5 tracking-wide my-5 text-white/90">To keep connected with us please login with your personal info</p>
                    </div>

                    <div class="overlay-right absolute flex items-center justify-center flex-col px-10 text-center top-0 h-full w-1/2 right-0 translate-x-0 transition-transform duration-[600ms] ease-in-out">
                        <h1 class="font-bold text-3xl m-0 text-white">Hello, Friend!</h1>
                        <p class="text-sm font-medium leading-5 tracking-wide my-5 text-white/90">Register with your personal details to use all of site features</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
        });

        @if ($errors->has('name') || $errors->has('password_confirmation'))
            container.classList.add("right-panel-active");
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

            mobileSignUpBtn.classList.add('bg-[#10B981]', 'text-white');
            mobileSignUpBtn.classList.remove('text-gray-600');
            mobileSignInBtn.classList.remove('bg-[#10B981]', 'text-white');
            mobileSignInBtn.classList.add('text-gray-600');
        });

        mobileSignInBtn.addEventListener('click', () => {
            mobileSignUpForm.classList.add('hidden');
            mobileSignUpForm.classList.remove('block');

            mobileSignInForm.classList.remove('hidden');
            mobileSignInForm.classList.add('block');

            mobileSignInBtn.classList.add('bg-[#10B981]', 'text-white');
            mobileSignInBtn.classList.remove('text-gray-600');
            mobileSignUpBtn.classList.remove('bg-[#10B981]', 'text-white');
            mobileSignUpBtn.classList.add('text-gray-600');
        });

        @if ($errors->has('name') || $errors->has('password_confirmation'))
            if (window.innerWidth < 768) {
                mobileSignUpBtn.click();
            }
        @endif
    </script>
</x-guest-layout>
