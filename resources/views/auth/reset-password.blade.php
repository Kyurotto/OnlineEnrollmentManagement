<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Montserrat:400,800');
    </style>

    <div class="fixed inset-0 w-screen h-screen bg-[#f6f5f7] flex justify-center items-center flex-col z-[9999] font-['Montserrat',sans-serif] overflow-y-auto py-10 px-4">
        
        <div class="bg-white rounded-[20px] shadow-[0_14px_28px_rgba(0,0,0,0.25),0_10px_10px_rgba(0,0,0,0.22)] relative overflow-hidden w-full max-w-[450px] p-10 flex flex-col items-center text-center">
            
            <h1 class="font-bold text-3xl m-0 text-gray-800 mb-6">Reset Password</h1>

            <form method="POST" action="{{ route('password.store') }}" class="w-full text-left">
                @csrf
                
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                
                <input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" placeholder="Email Address" 
                    class="bg-[#eee] border-none py-3 px-4 my-2 w-full rounded-md outline-none focus:ring-2 focus:ring-[#4B2B85]" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm w-full" />

                <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="New Password" 
                    class="bg-[#eee] border-none py-3 px-4 my-2 w-full rounded-md outline-none focus:ring-2 focus:ring-[#4B2B85]" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm w-full" />

                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password" 
                    class="bg-[#eee] border-none py-3 px-4 my-2 w-full rounded-md outline-none focus:ring-2 focus:ring-[#4B2B85]" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 text-sm w-full" />

                <button type="submit" class="mt-6 w-full rounded-full border border-[#4B2B85] bg-[#4B2B85] text-white text-xs font-bold py-3 px-11 tracking-wider uppercase transition-transform duration-[80ms] ease-in active:scale-95 focus:outline-none">
                    Reset Password
                </button>
            </form>
            
        </div>
    </div>
</x-guest-layout>