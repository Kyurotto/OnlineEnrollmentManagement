<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Montserrat:400,800');
    </style>

    <div class="fixed inset-0 w-screen h-screen bg-[#f6f5f7] flex justify-center items-center flex-col z-[9999] font-['Montserrat',sans-serif] overflow-y-auto py-10 px-4">
        
        <div class="bg-white rounded-[20px] shadow-[0_14px_28px_rgba(0,0,0,0.25),0_10px_10px_rgba(0,0,0,0.22)] relative overflow-hidden w-full max-w-[450px] p-10 flex flex-col items-center text-center">
            
            <h1 class="font-bold text-3xl m-0 text-gray-800 mb-2">Verify Email</h1>
            
            <p class="text-sm font-thin leading-5 tracking-wide text-gray-600 mb-6">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 font-bold text-sm text-green-600 w-full bg-green-50 p-3 rounded-md border border-green-200">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full rounded-full border border-[#4B2B85] bg-[#4B2B85] text-white text-xs font-bold py-3 px-11 tracking-wider uppercase transition-transform duration-[80ms] ease-in active:scale-95 focus:outline-none">
                    {{ __('Resend Verification Email') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="w-full mt-4">
                @csrf
                <button type="submit" class="bg-transparent border-none cursor-pointer block mt-2 text-[#333] text-sm no-underline hover:text-[#4B2B85] transition-colors font-bold w-full text-center focus:outline-none">
                    {{ __('Log Out') }}
                </button>
            </form>

        </div>
    </div>
</x-guest-layout>