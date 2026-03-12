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

<body class="bg-gray-50 text-gray-600 flex flex-col min-h-screen">
    <header class="bg-white border-b border-gray-200 sticky top-0 z-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Pay School Fees</h1>
                <p class="text-xs text-gray-500">Record a payment. An administrator will verify and update the status.
                </p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('student.dashboard') }}"
                    class="text-sm text-gray-500 hover:text-[#10B981] transition">← Back to Dashboard</a>
            </div>
        </div>
    </header>
    <main class="flex-grow max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        
            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Your Payment Records <span
                            class="text-gray-400 font-normal">({{ count($payments ?? []) }})</span></h2>
                </div>
                <div class="space-y-4">
                    @foreach($payments ?? [] as $payment)
                    <div class="border border-gray-100 rounded-lg p-4 bg-white flex justify-between items-start hover:border-gray-300 transition-colors shadow-sm">
                        <div>
                            <p class="font-bold text-gray-900">{{ number_format($payment['amount'], 2) }} PHP <span
                                    class="text-xs text-gray-400 font-normal ml-2">Txn: {{ $payment['txn_id'] }}</span>
                            </p>
                            <p class="text-xs text-gray-500 mt-1">{{ $payment['date'] }}</p>
                        </div>
                        <div class="text-right">
                            @if($payment['status'] === 'Completed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">Completed</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-600 border border-amber-100">Pending</span>
                            @endif
                            <p class="text-xs text-gray-300 mt-2">#{{ $payment['id'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
    </main>
</body>

</html>