<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Cashier Panel' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .table-container { min-height: 300px; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 4px; }
    </style>
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-600 flex flex-col min-h-screen">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-20 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <div class="bg-[#10B981] text-white font-bold p-2 rounded-lg text-sm shadow-md shadow-[#10B981]/20">CS</div>
                    <div>
                        <h1 class="text-lg font-bold leading-none text-gray-900">Cashier Panel</h1>
                        <span class="text-xs text-gray-500">Finance & Collections</span>
                    </div>
                    @if(request()->routeIs('cashier.payments.index') || request()->routeIs('cashier.payments'))
                    <div class="flex space-x-6 text-sm font-medium text-gray-600 h-16 ml-10">
                        <a href="{{ route('cashier.dashboard') }}" wire:navigate class="flex items-center hover:text-[#10B981] transition h-full">Dashboard</a>
                    </div>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    @if(request()->routeIs('cashier.dashboard'))
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2 px-4 rounded shadow transition-colors">Logout</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        {{ $slot }}
    </main>

    <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
            © 2026 Your Institution — Cashier Panel
        </div>
    </footer>

    @livewireScripts
</body>
</html>
