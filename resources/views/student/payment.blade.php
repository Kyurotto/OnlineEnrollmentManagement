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

<body class="bg-gray-50 text-slate-800 flex flex-col min-h-screen">
    <header class="bg-white border-b border-gray-200 sticky top-0 z-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Pay School Fees</h1>
                <p class="text-xs text-gray-500">Record a payment. An administrator will verify and update the status.
                </p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('student.dashboard') }}"
                    class="text-sm text-gray-600 hover:text-gray-900 transition">← Back to Dashboard</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button
                        class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2 px-4 rounded shadow">Logout</button>
                </form>
            </div>
        </div>
    </header>
    <main class="flex-grow max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        
            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold text-slate-800">Your Payment Records <span
                            class="text-gray-400 font-normal">({{ count($payments) }})</span></h2>
                </div>
                <div class="space-y-4">
                    @foreach($payments as $payment)
                    <div class="border border-gray-100 rounded-lg p-4 bg-gray-50 flex justify-between items-start">
                        <div>
                            <p class="font-bold text-slate-800">{{ number_format($payment['amount'], 2) }} PHP <span
                                    class="text-xs text-gray-500 font-normal ml-2">Txn: {{ $payment['txn_id'] }}</span>
                            </p>
                            <p class="text-xs text-gray-400 mt-1">{{ $payment['date'] }}</p>
                        </div>
                        <div class="text-right">
                            @if($payment['status'] === 'Completed')<span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span>@else<span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>@endif
                            <p class="text-xs text-gray-300 mt-2">#{{ $payment['id'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
</body>

</html>
