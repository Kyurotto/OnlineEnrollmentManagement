<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Montserrat:400,800');
    </style>

    <div class="fixed inset-0 w-screen h-screen bg-[#f6f5f7] flex justify-center items-center flex-col z-[9999] font-['Montserrat',sans-serif] overflow-y-auto py-10 px-4">
        
        <div class="bg-white rounded-[20px] shadow-[0_14px_28px_rgba(0,0,0,0.25),0_10px_10px_rgba(0,0,0,0.22)] relative overflow-hidden w-full max-w-[450px] p-10 flex flex-col items-center text-center">
            
            <h1 class="font-bold text-3xl m-0 text-gray-800 mb-2">Secure Area</h1>

            <p class="text-sm font-thin leading-5 tracking-wide text-gray-600 mb-6">
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
            </p>

            <form method="POST" action="{{ route('password.confirm') }}" class="w-full text-left">
                @csrf

                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Password" 
                    class="bg-[#eee] border-none py-3 px-4 my-2 w-full rounded-md outline-none focus:ring-2 focus:ring-[#4B2B85]" />
                
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm w-full" />

                <button type="submit" class="mt-6 w-full rounded-full border border-[#4B2B85] bg-[#4B2B85] text-white text-xs font-bold py-3 px-11 tracking-wider uppercase transition-transform duration-[80ms] ease-in active:scale-95 focus:outline-none">
                    {{ __('Confirm') }}
                </button>
            </form>
            
        </div>
    </div>
</x-guest-layout>