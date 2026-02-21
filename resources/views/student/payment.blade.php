<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay School Fees</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Inter', sans-serif;
    }
    </style>
</head>

<body class="bg-[#121212] text-[#A1A1AA] flex flex-col min-h-screen">
    <header class="bg-[#1C1C1E] border-b border-[#27272A] sticky top-0 z-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-white">Pay School Fees</h1>
                <p class="text-xs text-[#A1A1AA]">Record a payment. An administrator will verify and update the status.
                </p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('student.dashboard') }}"
                    class="text-sm text-[#A1A1AA] hover:text-[#10B981] transition">← Back to Dashboard</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button
                        class="bg-[#27272A] hover:bg-[#3F3F46] text-white text-sm font-semibold py-2 px-4 rounded shadow transition-colors">Logout</button>
                </form>
            </div>
        </div>
    </header>
    <main class="flex-grow max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        
            <div class="bg-[#1C1C1E] p-8 rounded-xl shadow-md border border-[#27272A]">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold text-white">Your Payment Records <span
                            class="text-[#52525B] font-normal">({{ count($payments ?? []) }})</span></h2>
                </div>
                <div class="space-y-4">
                    @foreach($payments ?? [] as $payment)
                    <div class="border border-[#27272A] rounded-lg p-4 bg-[#121212] flex justify-between items-start hover:border-[#3F3F46] transition-colors">
                        <div>
                            <p class="font-bold text-white">{{ number_format($payment['amount'], 2) }} PHP <span
                                    class="text-xs text-[#52525B] font-normal ml-2">Txn: {{ $payment['txn_id'] }}</span>
                            </p>
                            <p class="text-xs text-[#A1A1AA] mt-1">{{ $payment['date'] }}</p>
                        </div>
                        <div class="text-right">
                            @if($payment['status'] === 'Completed')<span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#10B981]/10 text-[#10B981] border border-[#10B981]/20">Completed</span>@else<span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>@endif
                            <p class="text-xs text-[#3F3F46] mt-2">#{{ $payment['id'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
    </main>
</body>

</html>