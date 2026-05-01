<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Montserrat:400,800');
    </style>

    <div class="fixed inset-0 w-screen h-screen flex justify-center items-center flex-col z-[9999] font-['Montserrat',sans-serif] overflow-y-auto py-10 px-4">

        <div class="bg-white rounded-[20px] shadow-2xl border border-gray-200 relative overflow-hidden w-full max-w-[450px] p-10 flex flex-col items-center text-center">

            <h1 class="font-bold text-3xl m-0 text-gray-900 mb-2">Forgot Password</h1>

            <p class="text-sm font-medium leading-5 tracking-wide text-gray-500 mb-6">
                No problem. Just let us know your email address and we will email you a password reset link.
            </p>

            <x-auth-session-status class="mb-4 text-[#3b82f6] font-bold text-sm w-full bg-[#3b82f6]/10 border border-[#3b82f6]/20 p-3 rounded-md" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="w-full">
                @csrf

                <input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="Email Address"
                    class="bg-gray-50 text-gray-900 border border-gray-300 py-3 px-4 my-2 w-full rounded-md outline-none focus:ring-2 focus:ring-[#3b82f6] placeholder-gray-400 shadow-sm" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-500 text-sm text-left w-full" />

                <button type="submit" class="mt-6 w-full rounded-full border border-[#3b82f6] bg-[#3b82f6] hover:bg-[#1d4ed8] hover:border-[#1d4ed8] text-white text-xs font-bold py-3 px-11 tracking-wider uppercase transition-colors duration-[200ms] ease-in active:scale-95 focus:outline-none shadow-md shadow-[#3b82f6]/20">
                    Email Reset Link
                </button>

                <a href="{{ route('login') }}" class="block mt-6 text-gray-500 text-sm no-underline hover:text-gray-900 transition-colors font-bold">
                    Back to Login
                </a>
            </form>

        </div>
    </div>
</x-guest-layout>
