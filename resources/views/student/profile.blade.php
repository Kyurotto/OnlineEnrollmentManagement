<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-[#121212] text-[#A1A1AA] flex flex-col min-h-screen">

    <nav class="bg-[#1C1C1E] border-b border-[#27272A]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div>
                    <h1 class="text-xl font-bold text-[#FFFFFF]">Profile</h1>
                    <p class="text-xs text-[#A1A1AA]">Manage your account and view recent activity.</p>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('student.dashboard') }}"
                        class="text-sm text-[#A1A1AA] hover:text-[#10B981] transition">← Back to Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button
                            class="bg-[#27272A] hover:bg-[#3F3F46] text-[#FFFFFF] text-sm font-semibold py-2 px-4 rounded shadow transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

            <div class="lg:col-span-2 bg-[#1C1C1E] p-8 rounded-xl shadow-md border border-[#27272A]">
                <h3 class="font-bold text-lg text-[#FFFFFF] mb-6">Account</h3>

                @if (session('status') === 'profile-updated')
                    <div
                        class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded relative mb-4 text-sm font-medium">
                        Profile updated successfully.
                    </div>
                @endif

                <div class="mb-6">
                    <label class="block text-xs font-semibold text-[#A1A1AA] mb-1">Username</label>

                    <div class="text-[#FFFFFF] font-bold text-lg">
                        {{ Auth::user()->username ?? Auth::user()->name }}
                    </div>

                    <p class="text-xs text-[#52525B] mt-1">Contact admin to change.</p>
                </div>

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-semibold text-[#A1A1AA] mb-1">Last Name</label>
                            <input type="text" name="last_name"
                                value="{{ old('last_name', Auth::user()->last_name) }}"
                                class="border border-[#3F3F46] bg-[#121212] text-[#FFFFFF] rounded-md py-2 px-3 w-full focus:outline-none focus:ring-2 focus:ring-[#10B981] text-sm placeholder-[#52525B]">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#A1A1AA] mb-1">First Name</label>
                            <input type="text" name="first_name"
                                value="{{ old('first_name', Auth::user()->first_name) }}"
                                class="border border-[#3F3F46] bg-[#121212] text-[#FFFFFF] rounded-md py-2 px-3 w-full focus:outline-none focus:ring-2 focus:ring-[#10B981] text-sm placeholder-[#52525B]">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#A1A1AA] mb-1">Middle Name</label>
                            <input type="text" name="middle_name"
                                value="{{ old('middle_name', Auth::user()->middle_name) }}"
                                class="border border-[#3F3F46] bg-[#121212] text-[#FFFFFF] rounded-md py-2 px-3 w-full focus:outline-none focus:ring-2 focus:ring-[#10B981] text-sm placeholder-[#52525B]">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-semibold text-[#A1A1AA] mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" readonly
                            class="border border-[#27272A] rounded-md py-2 px-3 w-full focus:outline-none bg-[#121212]/50 text-[#52525B] cursor-not-allowed text-sm">
                    </div>

                    <button type="submit"
                        class="bg-[#10B981] hover:bg-[#059669] text-[#FFFFFF] font-semibold py-2 px-6 rounded shadow-sm transition text-sm">
                        Save profile
                    </button>
                </form>
            </div>

            <div class="bg-[#1C1C1E] p-6 rounded-xl shadow-md border border-[#27272A] h-fit">
                <h3 class="font-bold text-lg text-[#FFFFFF] mb-4">Activity</h3>

                <div class="mb-6">
                    <p class="text-xs font-semibold text-[#A1A1AA] mb-2">Recent enrollments</p>
                    <p class="text-sm text-[#52525B] italic">No recent enrollments.</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-[#A1A1AA] mb-2">Recent payments</p>

                    @if (isset($payments) && count($payments) > 0)
                        @foreach ($payments as $payment)
                            <div
                                class="flex justify-between items-center mb-2 border-b border-[#27272A] pb-2 last:border-0">
                                <div>
                                    <div class="text-sm font-bold text-[#FFFFFF]">
                                        {{ number_format($payment['amount'], 2) }} PHP</div>
                                    <div class="text-xs text-[#A1A1AA]">{{ $payment['date'] }}</div>
                                </div>
                                <span
                                    class="bg-[#10B981]/10 text-[#10B981] border border-[#10B981]/20 text-xs font-bold px-2 py-1 rounded">
                                    {{ $payment['status'] }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm font-bold text-[#FFFFFF]">1,000.00 PHP</div>
                                <div class="text-xs text-[#A1A1AA]">2026-01-28 13:30:10</div>
                            </div>
                            <span
                                class="bg-[#10B981]/10 text-[#10B981] border border-[#10B981]/20 text-xs font-bold px-2 py-1 rounded">
                                Completed
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-[#1C1C1E] p-8 rounded-xl shadow-md border border-[#27272A] max-w-[66%]">
            <h3 class="font-bold text-lg text-[#FFFFFF] mb-6">Change Password</h3>

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                @method('put')

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-[#A1A1AA] mb-1">Current password</label>
                    <input type="password" name="current_password"
                        class="border border-[#3F3F46] bg-[#121212] text-[#FFFFFF] rounded-md py-2 px-3 w-full focus:outline-none focus:ring-2 focus:ring-[#10B981] text-sm">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-[#A1A1AA] mb-1">New password</label>
                    <input type="password" name="password"
                        class="border border-[#3F3F46] bg-[#121212] text-[#FFFFFF] rounded-md py-2 px-3 w-full focus:outline-none focus:ring-2 focus:ring-[#10B981] text-sm">
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-semibold text-[#A1A1AA] mb-1">Confirm new password</label>
                    <input type="password" name="password_confirmation"
                        class="border border-[#3F3F46] bg-[#121212] text-[#FFFFFF] rounded-md py-2 px-3 w-full focus:outline-none focus:ring-2 focus:ring-[#10B981] text-sm">
                </div>

                <button type="submit"
                    class="bg-[#10B981] hover:bg-[#059669] text-[#FFFFFF] font-semibold py-2 px-6 rounded shadow-sm transition text-sm">
                    Change password
                </button>
            </form>
        </div>

    </main>

</body>

</html>
