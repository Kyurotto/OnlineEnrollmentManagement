<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Student Portal' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; } }
        .saved-toast { animation: fadeOut 2s ease-out 1s forwards; }
    </style>
    @livewireStyles
</head>

<body class="bg-gray-50 text-gray-600 flex flex-col min-h-screen">

    {{ $slot }}

    @if(!request()->routeIs('student.enrollment.create', 'student.payment', 'student.profile'))
    <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
            © 2026 Enrollment Management System — Student Portal
        </div>
    </footer>
    @endif

    @livewireScripts
</body>
</html>
