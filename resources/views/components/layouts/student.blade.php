<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Student Portal' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #060d1a 0%, #0d1f3c 50%, #1a3a6e 100%);
            background-attachment: fixed;
        }
    </style>
    @livewireStyles
</head>
<body class="text-white flex flex-col min-h-screen">
    <nav class="sticky top-0 z-20 shadow-lg border-b bg-[#060d1a]/95 backdrop-blur-md border-[#1a3a6e]/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-8">
                    <div class="flex items-center gap-3">
                        <div class="text-white font-bold p-2 rounded-lg text-sm bg-gradient-to-br from-[#0d1f3c] to-[#1a3a6e] shadow-lg shadow-[#0d1f3c]/60">
                            ST</div>
                        <div>
                            <h1 class="text-lg font-bold leading-none text-white">Student Portal</h1>
                            <span class="text-xs text-[#8ab4d8]">
                                @if(Route::is('student.dashboard')) Dashboard
                                @elseif(Route::is('student.enrollment.create')) Enrollment
                                @elseif(Route::is('student.payment')) Payments
                                @elseif(Route::is('student.profile')) Profile
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="flex space-x-6 text-sm font-medium h-16">
                        <a href="{{ route('student.dashboard') }}"
                            class="flex items-center transition h-full border-b-2 {{ Route::is('student.dashboard') ? 'text-[#a8d5f5] border-[#1a3a6e]' : 'text-[#8ab4d8] border-transparent hover:text-white' }}">Dashboard</a>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="text-right hidden sm:block">
                        <div class="text-xs text-[#8ab4d8]">Signed in as Student</div>
                        <div class="text-sm font-bold text-white capitalize">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="text-white text-sm font-semibold py-2 px-6 rounded-full transition-all shadow-lg active:scale-95 bg-rose-600/80 border border-rose-500/50 hover:bg-rose-600">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full animate-in fade-in duration-700">
        {{ $slot }}
    </main>

    <footer class="border-t py-6 mt-auto shadow-inner" style="background: rgba(6,13,26,0.6); border-color: rgba(26,58,110,0.3);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
            <div class="text-sm font-medium" style="color: #4a6fa5;">
                © 2026 Your Institution — Student Portal
            </div>
            <div class="text-xs font-bold uppercase tracking-widest opacity-40">
                Student Information System
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
