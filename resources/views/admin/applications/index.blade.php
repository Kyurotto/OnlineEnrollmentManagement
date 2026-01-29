<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Applications</title>
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
            <h2 class="text-2xl font-bold text-slate-900">Enrollment Applications</h2>
            <p class="text-sm text-gray-500">Review and manage student enrollment applications.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 min-h-[500px]">
            <h3 class="font-bold text-lg text-slate-800 mb-6">All Applications ({{ $applications->count() }})</h3>

            <div class="space-y-6">
                @forelse($applications as $app)
                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-sm transition">

                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
                        <div>
                            <h4 class="font-bold text-slate-800">Application #{{ $app->id }}</h4>
                            <p class="text-xs text-gray-500">Submitted: {{ $app->created_at->format('F j, Y, g:i a') }}</p>
                        </div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="text-xs font-bold px-2.5 py-1 rounded
                                @if($app->status === 'Approved') bg-green-100 text-green-800
                                @elseif($app->status === 'Rejected') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $app->status }}
                            </span>

                            <div class="flex items-center gap-2">
                                @if($app->status === 'Pending')
                                    <form action="{{ route('admin.applications.approve', $app->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-3 py-1 rounded shadow-sm transition">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.applications.reject', $app->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-3 py-1 rounded shadow-sm transition">
                                            Reject
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.applications.destroy', $app->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this application? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold px-3 py-1 rounded shadow-sm transition">
                                        Delete
                                    </button>
                                </form>
                            </div>

                            @if($app->is_processed)
                                <span class="text-xs text-gray-400 ml-2">Processed</span>
                            @endif
                        </div>
                    </div>

                    <div class="text-sm text-slate-700 space-y-3">
                        <div>
                            <span class="font-bold">Student:</span> {{ $app->first_name }} {{ $app->last_name }} — <span class="text-blue-600">{{ $app->email }}</span>
                            <div class="text-xs text-gray-500 mt-1">
                                DOB: {{ $app->birth_date }} - Age: {{ $app->age }} - Gender: {{ $app->gender }}
                            </div>
                            <div class="text-xs text-gray-400 mt-1 break-words w-3/4">
                                {{ $app->address_full }}
                            </div>
                        </div>

                        <div>
                            <span class="font-bold">Course Applied:</span> {{ $app->course_code }} ({{ $app->year_level }})
                        </div>

                        <div class="pt-2">
                            <span class="font-bold block mb-1">Parent / Guardian Details:</span>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-1 text-sm text-slate-600">
                                <div><span class="font-semibold text-slate-800">Father:</span> {{ $app->father_name ?? 'N/A' }}</div>
                                <div><span class="font-semibold text-slate-800">Mother:</span> {{ $app->mother_maiden_name ?? 'N/A' }}</div>
                                <div><span class="font-semibold text-slate-800">Guardian:</span> {{ $app->guardian_name ?? 'N/A' }}</div>
                                <div><span class="font-semibold text-slate-800">Contact:</span> {{ $app->guardian_contact ?? $app->contact }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">
                    No applications found.
                </div>
                @endforelse
            </div>
        </div>

        <div class="mt-12 text-center text-sm text-gray-500">
            © 2026 Your Institution — Admin Panel
        </div>
    </main>
</body>
</html>
