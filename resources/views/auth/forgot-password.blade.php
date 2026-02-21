<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Montserrat:400,800');
    </style>

    <div class="fixed inset-0 w-screen h-screen bg-[#121212] flex justify-center items-center flex-col z-[9999] font-['Montserrat',sans-serif] overflow-y-auto py-10 px-4">
        
        <div class="bg-[#1C1C1E] rounded-[20px] shadow-[0_14px_28px_rgba(0,0,0,0.5),0_10px_10px_rgba(0,0,0,0.5)] border border-[#27272A] relative overflow-hidden w-full max-w-[450px] p-10 flex flex-col items-center text-center">
            
            <h1 class="font-bold text-3xl m-0 text-white mb-2">Forgot Password</h1>
            
            <p class="text-sm font-thin leading-5 tracking-wide text-[#A1A1AA] mb-6">
                No problem. Just let us know your email address and we will email you a password reset link.
            </p>

            <x-auth-session-status class="mb-4 text-[#10B981] font-bold text-sm w-full bg-[#10B981]/10 border border-[#10B981]/20 p-3 rounded-md" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="w-full">
                @csrf
                
                <input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="Email Address" 
                    class="bg-[#121212] text-white border border-[#27272A] py-3 px-4 my-2 w-full rounded-md outline-none focus:ring-2 focus:ring-[#10B981] placeholder-[#52525B]" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm text-left w-full" />

                <button type="submit" class="mt-6 w-full rounded-full border border-[#10B981] bg-[#10B981] hover:bg-[#059669] hover:border-[#059669] text-white text-xs font-bold py-3 px-11 tracking-wider uppercase transition-colors duration-[200ms] ease-in active:scale-95 focus:outline-none shadow-md shadow-[#10B981]/20">
                    Email Reset Link
                </button>
                
                <a href="{{ route('login') }}" class="block mt-6 text-[#A1A1AA] text-sm no-underline hover:text-[#10B981] transition-colors font-bold">
                    Back to Login
                </a>
            </form>

        </div>
    </div>
</x-guest-layout>