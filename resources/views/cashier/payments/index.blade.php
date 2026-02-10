<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier - Manage Payments</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-slate-800">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">

                <div class="flex items-center gap-2">
                    <span class="bg-slate-900 text-white font-bold px-2 py-1 rounded text-sm">CP</span>
                    <h1 class="text-xl font-bold text-slate-900">Cashier Panel</h1>
                </div>

                <div class="flex items-center gap-6">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2 px-4 rounded shadow transition">Logout</button>
                    </form>
                </div>

            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900">Manage Payments</h2>
            <p class="text-sm text-gray-500">Review, filter and update payment records.</p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
            <form action="{{ route('cashier.payments.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">

                <select name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                    <option value="All statuses">All statuses</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>

                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search username, email or ID" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">

                <button type="submit" class="text-white bg-slate-800 hover:bg-slate-900 focus:ring-4 focus:ring-slate-300 font-medium rounded-lg text-sm px-5 py-2.5">Search</button>
                <a href="{{ route('cashier.payments.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">Reset</a>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200">
                <h3 class="font-bold text-slate-800">Payments List</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">ID</th>
                            <th class="px-6 py-3">User</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Course</th>
                            <th class="px-6 py-3">Year Level</th>
                            <th class="px-6 py-3">Amount</th>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $payment->id }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $payment->user->name ?? 'Unknown' }}
                                <div class="text-xs text-gray-500">{{ $payment->user->username ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $payment->user->email ?? 'N/A' }}</td>
                            <td class="px-6 py-4 font-bold text-slate-700">{{ $payment->application->course_code ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-xs">{{ $payment->application->year_level ?? 'N/A' }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">
                                <form action="{{ route('cashier.payments.update', $payment->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" step="0.01" name="amount" value="{{ $payment->amount }}" class="w-24 text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 px-2 py-1" onchange="this.form.submit()">
                                </form>
                            </td>
                            <td class="px-6 py-4">{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4">
                                @if($payment->status === 'Completed')
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Completed</span>
                                @elseif($payment->status === 'Pending')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Pending</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $payment->status }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 flex items-center gap-2">
                                <form action="{{ route('cashier.payments.update', $payment->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="text-xs border-gray-300 rounded focus:ring-blue-500">
                                        <option value="Pending" {{ $payment->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Completed" {{ $payment->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="Rejected" {{ $payment->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </form>

                                <form action="{{ route('cashier.payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1.5 rounded">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-400">No payments found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $payments->links() }}
            </div>
        </div>
    </main>
</body>
</html>
