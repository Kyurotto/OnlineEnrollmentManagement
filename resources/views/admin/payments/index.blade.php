<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payments</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-slate-800 flex flex-col min-h-screen">

    <nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <h1 class="text-xl font-bold text-slate-900">Admin Panel</h1>

                <div class="flex space-x-6 text-sm font-medium text-gray-500 h-16">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center hover:text-slate-900 transition h-full">
                        Dashboard
                    </a>
                </div>
            </div>

            <div class="flex items-center">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2 px-4 rounded shadow transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900">Manage Payments</h2>
            <p class="text-sm text-gray-500">Review, filter and update payment records. You can now search by and edit the Payment ID (transaction_id).</p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6 flex flex-col md:flex-row gap-4 items-center">

            <div class="flex items-center gap-2 w-full md:w-auto">
                <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                    <option>All statuses</option>
                    <option>Completed</option>
                    <option>Pending</option>
                </select>
                <button class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-4 py-2.5">Filter</button>
            </div>

            <div class="flex-grow flex items-center gap-2 w-full">
                <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Search username, name, email, or Payment ID">
                <button class="text-white bg-slate-800 hover:bg-slate-900 font-medium rounded-lg text-sm px-5 py-2.5">Search</button>
                <a href="#" class="text-gray-500 hover:text-gray-700 text-sm font-medium px-2">Reset</a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-lg text-slate-800">Payments <span class="text-gray-500 font-normal">({{ count($payments) }})</span></h3>
                <p class="text-xs text-gray-400">Showing {{ count($payments) }} results.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($payments as $payment)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment['id'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-800">{{ $payment['username'] }}</div>
                                <div class="text-xs text-gray-500">{{ $payment['full_name'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment['email'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-700">{{ $payment['amount'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">{{ $payment['date'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payment['status'] === 'Completed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-md bg-green-100 text-green-800">Completed</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-md bg-yellow-100 text-yellow-800">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <select class="text-xs border-gray-300 rounded shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <option {{ $payment['status'] == 'Completed' ? 'selected' : '' }}>Completed</option>
                                        <option {{ $payment['status'] == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    </select>

                                    <button class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs">Delete</button>
                                    <a href="#" class="text-blue-600 hover:text-blue-800 text-xs">Details</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-lg">
                <p class="text-xs text-gray-500">Tip: Integrate your payment gateway to automatically create and update payment records.</p>
            </div>
        </div>

        <div class="mt-12 text-center text-sm text-gray-500">
            © 2026 Your Institution — Admin Panel
        </div>
    </main>
</body>
</html>
