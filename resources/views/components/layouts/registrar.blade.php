<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Registrar Panel' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    @livewireStyles
</head>
<body class="text-gray-600 flex flex-col min-h-screen" style="background: linear-gradient(135deg, #060d1a 0%, #0d1f3c 40%, #1a3a6e 100%); background-attachment: fixed; min-height: 100vh;">

    <!-- Extracted Livewire Navbar -->
    <livewire:registrar.registrar-navbar />

    <!-- Page Content -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="py-6 mt-auto border-t" style="background: rgba(6,13,26,0.85); border-color: rgba(26,58,110,0.4);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm" style="color: #4a6fa5;">
            © 2026 Your Institution — Registrar Panel
        </div>
    </footer>

    @livewireScripts
</body>
</html>
