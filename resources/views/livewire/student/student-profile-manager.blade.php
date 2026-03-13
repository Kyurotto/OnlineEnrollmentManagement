<div>
<div>
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Profile</h1>
                    <p class="text-xs text-gray-500">Manage your account and view recent activity.</p>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('student.dashboard') }}" wire:navigate
                        class="text-sm text-gray-500 hover:text-[#10B981] transition font-medium">← Back to Dashboard</a>
                    @if(request()->routeIs('student.dashboard'))
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button
                            class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2 px-4 rounded shadow transition">Logout</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

            <div class="lg:col-span-2 bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <h3 class="font-bold text-lg text-gray-900 mb-6">Account</h3>

                @if (session('profile-updated'))
                    <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded relative mb-4 text-sm font-bold shadow-sm">
                        {{ session('profile-updated') }}
                    </div>
                @endif

                <div class="mb-6">
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Username</label>

                    <div class="text-gray-900 font-bold text-lg">
                        {{ Auth::user()->username ?? Auth::user()->name }}
                    </div>

                    <p class="text-xs text-gray-400 mt-1">Contact admin to change.</p>
                </div>

                <form wire:submit="updateProfile">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Last Name</label>
                            <input type="text" wire:model="last_name"
                                class="border border-gray-300 bg-white text-gray-900 rounded-md py-2 px-3 w-full focus:outline-none focus:ring-2 focus:ring-[#10B981] text-sm placeholder-gray-400 font-medium">
                            @error('last_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">First Name</label>
                            <input type="text" wire:model="first_name"
                                class="border border-gray-300 bg-white text-gray-900 rounded-md py-2 px-3 w-full focus:outline-none focus:ring-2 focus:ring-[#10B981] text-sm placeholder-gray-400 font-medium">
                            @error('first_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Middle Name</label>
                            <input type="text" wire:model="middle_name"
                                class="border border-gray-300 bg-white text-gray-900 rounded-md py-2 px-3 w-full focus:outline-none focus:ring-2 focus:ring-[#10B981] text-sm placeholder-gray-400 font-medium">
                            @error('middle_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Email</label>
                        <input type="email" wire:model="email" readonly
                            class="border border-gray-200 rounded-md py-2 px-3 w-full focus:outline-none bg-gray-50 text-gray-500 cursor-not-allowed text-sm">
                    </div>

                    <button type="submit"
                        class="bg-[#10B981] hover:bg-[#059669] text-white font-bold py-2 px-6 rounded-lg shadow-sm transition text-sm">
                        <span wire:loading.remove wire:target="updateProfile">Save profile</span>
                        <span wire:loading wire:target="updateProfile">Saving...</span>
                    </button>
                </form>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 h-fit">
                <h3 class="font-bold text-lg text-gray-900 mb-4">Activity</h3>

                <div class="mb-6">
                    <p class="text-xs font-semibold text-gray-500 mb-2">Recent enrollments</p>
                    <p class="text-sm text-gray-400 italic">No recent enrollments.</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-2">Recent payments</p>

                    @if (isset($payments) && count($payments) > 0)
                        @foreach ($payments as $payment)
                            <div class="flex justify-between items-center mb-2 border-b border-gray-100 pb-2 last:border-0 hover:bg-gray-50 transition-colors p-1 -mx-1 rounded">
                                <div>
                                    <div class="text-sm font-bold text-gray-900">
                                        {{ number_format($payment['amount'], 2) }} PHP</div>
                                    <div class="text-xs text-gray-500">{{ $payment['date'] }}</div>
                                </div>
                                <span class="{{ $payment['status'] === 'Completed' ? 'bg-[#10B981]/10 text-[#10B981] border border-[#10B981]/20' : 'bg-amber-50 text-amber-600 border border-amber-200' }} text-xs font-bold px-2 py-1 rounded shadow-sm">
                                    {{ $payment['status'] }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-sm text-gray-400 italic">No recent payments.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200 max-w-[66%]">
            <h3 class="font-bold text-lg text-gray-900 mb-6">Change Password</h3>

            @if (session('password-updated'))
                <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded relative mb-4 text-sm font-bold shadow-sm">
                    {{ session('password-updated') }}
                </div>
            @endif

            <form wire:submit="updatePassword">
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Current password</label>
                    <input type="password" wire:model="current_password"
                        class="border border-gray-300 bg-white text-gray-900 rounded-md py-2 px-3 w-full focus:outline-none focus:ring-2 focus:ring-[#10B981] text-sm">
                    @error('current_password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 mb-1">New password</label>
                    <input type="password" wire:model="password"
                        class="border border-gray-300 bg-white text-gray-900 rounded-md py-2 px-3 w-full focus:outline-none focus:ring-2 focus:ring-[#10B981] text-sm">
                    @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Confirm new password</label>
                    <input type="password" wire:model="password_confirmation"
                        class="border border-gray-300 bg-white text-gray-900 rounded-md py-2 px-3 w-full focus:outline-none focus:ring-2 focus:ring-[#10B981] text-sm">
                </div>

                <button type="submit"
                    class="bg-[#10B981] hover:bg-[#059669] text-white font-bold py-2 px-6 rounded-lg shadow-sm transition text-sm">
                    <span wire:loading.remove wire:target="updatePassword">Change password</span>
                    <span wire:loading wire:target="updatePassword">Changing...</span>
                </button>
            </form>
        </div>
    </main>
</div>
