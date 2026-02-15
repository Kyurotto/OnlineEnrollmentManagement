<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-slate-800 flex flex-col min-h-screen">

    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div>
                    <h1 class="text-xl font-bold text-slate-900">Profile</h1>
                    <p class="text-xs text-gray-500">Manage your account and view recent activity.</p>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('student.dashboard') }}" class="text-sm text-gray-500 hover:text-slate-900 transition">← Back to Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2 px-4 rounded shadow transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

            <div class="lg:col-span-2 bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-lg text-slate-800 mb-6">Account</h3>

                @if(session('status') === 'profile-updated')
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 text-sm">
                        Profile updated successfully.
                    </div>
                @endif

                <div class="mb-6">
    <label class="block text-xs font-semibold text-gray-500 mb-1">Username</label>

    <div class="text-slate-800 font-bold text-lg">
        {{ Auth::user()->username ?? Auth::user()->name }}
    </div>

    <p class="text-xs text-gray-400 mt-1">Contact admin to change.</p>
</div>

                <form action="{{ route('profile.update') }}" method="POST">
    @csrf
    @method('PATCH')

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Last Name</label>
            <input type="text" name="last_name"
                   value="{{ old('last_name', Auth::user()->last_name) }}"
                   class="border border-gray-300 rounded-md py-2 px-3 w-full focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">First Name</label>
            <input type="text" name="first_name"
                   value="{{ old('first_name', Auth::user()->first_name) }}"
                   class="border border-gray-300 rounded-md py-2 px-3 w-full focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Middle Name</label>
            <input type="text" name="middle_name"
                   value="{{ old('middle_name', Auth::user()->middle_name) }}"
                   class="border border-gray-300 rounded-md py-2 px-3 w-full focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm">
        </div>
    </div>

    <div class="mb-6">
        <label class="block text-xs font-semibold text-gray-500 mb-1">Email</label>
        <input type="email" name="email"
            value="{{ old('email', Auth::user()->email) }}"
            readonly
            class="border border-gray-300 rounded-md py-2 px-3 w-full focus:outline-none bg-gray-100 text-gray-500 cursor-not-allowed text-sm">
    </div>

    <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white font-semibold py-2 px-6 rounded shadow-sm transition text-sm">
        Save profile
    </button>
</form>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit">
                <h3 class="font-bold text-lg text-slate-800 mb-4">Activity</h3>

                <div class="mb-6">
                    <p class="text-xs font-semibold text-gray-500 mb-2">Recent enrollments</p>
                    <p class="text-sm text-gray-400 italic">No recent enrollments.</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-2">Recent payments</p>

                    @if(isset($payments) && count($payments) > 0)
                        @foreach($payments as $payment)
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <div class="text-sm font-bold text-slate-800">{{ number_format($payment['amount'], 2) }} PHP</div>
                                <div class="text-xs text-gray-400">{{ $payment['date'] }}</div>
                            </div>
                            <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded">
                                {{ $payment['status'] }}
                            </span>
                        </div>
                        @endforeach
                    @else
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm font-bold text-slate-800">1,000.00 PHP</div>
                                <div class="text-xs text-gray-400">2026-01-28 13:30:10</div>
                            </div>
                            <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded">
                                Completed
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 max-w-[66%]">
            <h3 class="font-bold text-lg text-slate-800 mb-6">Change Password</h3>

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                @method('put')

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Current password</label>
                    <input type="password" name="current_password" class="border border-gray-300 rounded-md py-2 px-3 w-full focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 mb-1">New password</label>
                    <input type="password" name="password" class="border border-gray-300 rounded-md py-2 px-3 w-full focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm">
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Confirm new password</label>
                    <input type="password" name="password_confirmation" class="border border-gray-300 rounded-md py-2 px-3 w-full focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm">
                </div>

                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-6 rounded shadow-sm transition text-sm">
                    Change password
                </button>
            </form>
        </div>

    </main>

</body>
</html>
