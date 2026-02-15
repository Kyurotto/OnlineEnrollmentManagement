<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Payments</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-slate-800">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-8">
                    <div class="flex items-center gap-3">
                        <div class="bg-black text-white font-bold p-2 rounded-lg text-sm">AD</div>
                        <div>
                            <h1 class="text-lg font-bold leading-none text-slate-900">Admin Panel</h1>
                            <span class="text-xs text-gray-500">Manage Payments</span>
                        </div>
                    </div>
                    <div class="flex space-x-6 text-sm font-medium text-gray-500 h-16">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center hover:text-slate-900 transition h-full">Dashboard</a>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="relative cursor-pointer group">
                        <div class="relative">
                            <svg class="w-6 h-6 text-gray-500 group-hover:text-gray-700 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @if(isset($pendingCount) && $pendingCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white animate-pulse shadow-sm">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </div>
                        @if(isset($pendingCount) && $pendingCount > 0)
                        <div class="absolute right-0 top-10 w-64 bg-white border border-gray-200 shadow-xl rounded-lg hidden group-hover:block z-50">
                            <div class="p-4">
                                <p class="text-sm font-bold text-slate-800">{{ $pendingCount }} New Application(s)</p>
                                <p class="text-xs text-gray-500 mt-1">Students are waiting for approval.</p>
                                <a href="{{ route('admin.applications.index') }}" class="block mt-3 text-center bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 rounded transition">
                                    View Applications →
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>

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
            <form action="{{ route('admin.payments.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-gray-500 mb-1">Status</label>
                    <select name="status" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                        <option value="All statuses">All statuses</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Paid</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-gray-500 mb-1">Filter Program</label>
                    <select name="filter_course" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                        <option value="ALL">All Programs</option>
                            <option value="BSIS-1" {{ request('filter_course') == 'BSIS-1' ? 'selected' : '' }}>BSIS 1</option>
                            <option value="BSIS-2" {{ request('filter_course') == 'BSIS-2' ? 'selected' : '' }}>BSIS 2</option>
                            <option value="BSIS-3" {{ request('filter_course') == 'BSIS-3' ? 'selected' : '' }}>BSIS 3</option>
                            <option value="BSIS-4" {{ request('filter_course') == 'BSIS-4' ? 'selected' : '' }}>BSIS 4</option>
                            <option value="BTVTED-1" {{ request('filter_course') == 'BTVTED-1' ? 'selected' : '' }}>BTVTED 1</option>  
                            <option value="BTVTED-2" {{ request('filter_course') == 'BTVTED-2' ? 'selected' : '' }}>BTVTED 2</option>
                            <option value="BTVTED-3" {{ request('filter_course') == 'BTVTED-3' ? 'selected' : '' }}>BTVTED 3</option>
                            <option value="ACT-1" {{ request('filter_course') == 'ACT-1' ? 'selected' : '' }}>ACT 1</option>
                            <option value="ACT-2" {{ request('filter_course') == 'ACT-2' ? 'selected' : '' }}>ACT 2</option>
                            <option value="DIT-1" {{ request('filter_course') == 'DIT-1' ? 'selected' : '' }}>DIT 1</option>
                            <option value="DIT-2" {{ request('filter_course') == 'DIT-2' ? 'selected' : '' }}>DIT 2</option>
                            <option value="DIT-3" {{ request('filter_course') == 'DIT-3' ? 'selected' : '' }}>DIT 3</option>
                            <option value="DHRT-1" {{ request('filter_course') == 'DHRT-1' ? 'selected' : '' }}>DHRT 1</option>
                            <option value="DHRT-2" {{ request('filter_course') == 'DHRT-2' ? 'selected' : '' }}>DHRT 2</option>
                            <option value="DHRT-3" {{ request('filter_course') == 'DHRT-3' ? 'selected' : '' }}>DHRT 3</option>
                    </select>
                </div>

                <div class="md:col-span-6 flex gap-2 items-end">
                    <div class="w-full">
                        <label class="block text-xs font-bold text-gray-500 mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name/ID" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                    </div>
                    <button type="submit" class="text-white bg-slate-800 hover:bg-slate-900 font-medium rounded-lg text-sm px-4 py-2.5 h-[42px] mb-[1px]">Filter</button>
                    <a href="{{ route('admin.payments.index') }}" class="text-center text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-4 py-2.5 h-[42px] mb-[1px]">Reset</a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Payments List</h3>
                
                @if(request('filter_course') && request('filter_course') != 'ALL')
                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded font-bold">
                        Filtered by: {{ str_replace('-', ' ', request('filter_course')) }}
                    </span>
                @endif
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
                            
                            <td class="px-6 py-4 font-bold text-slate-700">
                                {{ optional($payment->application)->course_code ?? 'N/A' }}
                            </td>
                            
                            <td class="px-6 py-4 text-xs">
                                {{ strtok(optional($payment->application)->year_level ?? 'N/A', '|') }}
                            </td>
                            
                            <td class="px-6 py-4 font-bold text-slate-800">{{ number_format($payment->amount, 2) }}</td>
                            <td class="px-6 py-4">{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4">
                                @if($payment->status === 'Completed') <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Completed</span>
                                @elseif($payment->status === 'Pending') <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Pending</span>
                                @else <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $payment->status }}</span> @endif
                            </td>
                            <td class="px-6 py-4 flex items-center gap-2">
                                <form action="{{ route('admin.payments.update', $payment->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="text-xs border-gray-300 rounded focus:ring-blue-500">
                                        <option value="Pending" {{ $payment->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Completed" {{ $payment->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="Rejected" {{ $payment->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </form>
                                <form action="{{ route('admin.payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Void this payment?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs font-medium px-3 py-1.5 rounded transition">Void</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="px-6 py-4 text-center text-gray-400">No payments found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $payments->links() }}</div>
        </div>
    </main>
</body>
</html>