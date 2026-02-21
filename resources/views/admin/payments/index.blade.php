<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payments - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Inter', sans-serif; } 
        .table-container { min-height: 300px; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #3F3F46; border-radius: 4px; }
    </style>
</head>
<body class="bg-[#121212] text-[#A1A1AA]">

    <nav class="bg-[#1C1C1E] border-b border-[#27272A] sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <div class="bg-[#10B981] text-white font-bold p-2 rounded-lg text-sm shadow-md shadow-[#10B981]/20">AD</div>
                    <div>
                        <h1 class="text-lg font-bold leading-none text-[#FFFFFF]">Admin Panel</h1>
                        <span class="text-xs text-[#52525B]">Manage Payments</span>
                    </div>
                    <div class="flex space-x-6 text-sm font-medium text-[#A1A1AA] h-16 ml-10">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center hover:text-[#10B981] transition h-full">Dashboard</a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="bg-red-500 hover:bg-[#3F3F46] text-white text-sm font-semibold py-2 px-4 rounded shadow transition-colors">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        @if($errors->any())
            <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 px-4 py-3 rounded-lg relative mb-6">
                <strong class="font-bold">Whoops!</strong>
                <ul class="list-disc list-inside mt-2 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded-lg relative mb-6 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-[#1C1C1E] rounded-xl shadow-md border border-[#27272A] overflow-hidden">
            <div class="px-6 py-5 border-b border-[#27272A] flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-xl font-bold text-[#FFFFFF]">Payments History</h2>
                    <p class="text-sm text-[#52525B] mt-1">Manage and verify student transactions.</p>
                </div>
            </div>

            <div class="bg-[#121212]/50 px-6 py-4 border-b border-[#27272A]">
                <form action="{{ route('admin.payments.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-grow">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-[#52525B]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" class="pl-10 w-full bg-[#121212] border border-[#27272A] rounded-lg p-2 text-sm text-[#FFFFFF] focus:ring-2 focus:ring-[#10B981] outline-none transition-all placeholder-[#3F3F46]" placeholder="Search student name, email, or receipt #...">
                    </div>
                    <div class="w-full sm:w-48">
                        <select name="filter_course" onchange="this.form.submit()" class="w-full bg-[#121212] border border-[#27272A] rounded-lg p-2 text-sm text-[#FFFFFF] focus:ring-2 focus:ring-[#10B981] outline-none cursor-pointer">
                            <option value="ALL" {{ request('filter_course') == 'ALL' ? 'selected' : '' }}>All Programs</option>
                            <option value="BSIS-1" {{ request('filter_course') == 'BSIS-1' ? 'selected' : '' }}>BSIS 1</option>
                            <option value="BSIS-2" {{ request('filter_course') == 'BSIS-2' ? 'selected' : '' }}>BSIS 2</option>
                            <option value="BSIS-3" {{ request('filter_course') == 'BSIS-3' ? 'selected' : '' }}>BSIS 3</option>
                            <option value="BSIS-4" {{ request('filter_course') == 'BSIS-4' ? 'selected' : '' }}>BSIS 4</option>
                            <option value="DIT-1" {{ request('filter_course') == 'DIT-1' ? 'selected' : '' }}>DIT 1</option>
                            <option value="DIT-2" {{ request('filter_course') == 'DIT-2' ? 'selected' : '' }}>DIT 2</option>
                            <option value="DIT-3" {{ request('filter_course') == 'DIT-3' ? 'selected' : '' }}>DIT 3</option>
                            <option value="DIT-4" {{ request('filter_course') == 'DIT-4' ? 'selected' : '' }}>DIT 4</option>
                            <option value="ACT-1" {{ request('filter_course') == 'ACT-1' ? 'selected' : '' }}>ACT 1</option>
                            <option value="ACT-2" {{ request('filter_course') == 'ACT-2' ? 'selected' : '' }}>ACT 2</option>
                            <option value="ACT-3" {{ request('filter_course') == 'ACT-3' ? 'selected' : '' }}>ACT 3</option>
                            <option value="DHRT-1" {{ request('filter_course') == 'DHRT-1' ? 'selected' : '' }}>DHRT 1</option>   
                            <option value="DHRT-2" {{ request('filter_course') == 'DHRT-2' ? 'selected' : '' }}>DHRT 2</option>
                            <option value="DHRT-3" {{ request('filter_course') == 'DHRT-3' ? 'selected' : '' }}>DHRT 3</option>
                            <option value="BTVTED-1" {{ request('filter_course') == 'BTVTED-1' ? 'selected' : '' }}>BTVTED 1</option>     
                            <option value="BTVTED-2" {{ request('filter_course') == 'BTVTED-2' ? 'selected' : '' }}>BTVTED 2</option>
                            <option value="BTVTED-3" {{ request('filter_course') == 'BTVTED-3' ? 'selected' : '' }}>BTVTED 3</option>
                            <option value="BTVTED-4" {{ request('filter_course') == 'BTVTED-4' ? 'selected' : '' }}>BTVTED 4</option>
                        </select>
                    </div>
                    <div class="w-full sm:w-40">
                        <select name="status" onchange="this.form.submit()" class="w-full bg-[#121212] border border-[#27272A] rounded-lg p-2 text-sm text-[#FFFFFF] focus:ring-2 focus:ring-[#10B981] outline-none cursor-pointer">
                            <option value="All statuses" {{ request('status') == 'All statuses' ? 'selected' : '' }}>All Statuses</option>
                            <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-[#10B981] hover:bg-[#059669] text-white text-sm font-bold px-5 py-2 rounded-lg transition-all shadow-md shadow-[#10B981]/10">Search</button>
                    <a href="{{ route('admin.payments.index') }}" class="bg-[#27272A] border border-[#3F3F46] text-[#A1A1AA] hover:text-white hover:bg-[#3F3F46] text-sm font-medium px-4 py-2 rounded-lg transition text-center">Reset</a>
                </form>
            </div>

            <div class="overflow-visible table-container">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#121212] border-b border-[#27272A] text-xs text-[#52525B] uppercase tracking-wider">
                            <th class="py-3 px-6 font-bold">Receipt #</th>
                            <th class="py-3 px-6 font-bold">Student Name</th>
                            <th class="py-3 px-6 font-bold">Program/Year</th> 
                            <th class="py-3 px-6 font-bold">Date</th>
                            <th class="py-3 px-6 font-bold text-right">Amount</th>
                            <th class="py-3 px-6 font-bold text-center">Status</th>
                            <th class="py-3 px-6 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-[#27272A]">
                        @forelse($payments as $payment)
                        <tr class="hover:bg-[#27272A]/30 transition group">
                            <td class="py-4 px-6 font-mono text-xs text-[#52525B]"><span class="bg-[#121212] text-[#A1A1AA] px-2 py-1 rounded border border-[#27272A]">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span></td>
                            <td class="py-4 px-6"><div class="font-bold text-[#FFFFFF]">{{ optional($payment->user)->name ?? 'Unknown' }}</div><div class="text-xs text-[#52525B]">{{ optional($payment->user)->email }}</div></td>
                            <td class="py-4 px-6 text-[#A1A1AA]">@if(optional($payment->application)->course_code)<span class="font-bold text-[#FFFFFF]">{{ $payment->application->course_code }}</span><span class="text-xs text-[#52525B] block">{{ $payment->application->year_level }}</span>@else<span class="text-[#3F3F46] italic text-xs">N/A</span>@endif</td>
                            <td class="py-4 px-6 text-[#A1A1AA]">{{ $payment->created_at->format('M d, Y') }}<span class="text-xs text-[#52525B] block">{{ $payment->payment_method ?? 'Cash' }}</span></td>
                            <td class="py-4 px-6 font-bold text-[#10B981] text-right">₱{{ number_format($payment->amount, 2) }}</td>
                            <td class="py-4 px-6 text-center">
                                @if($payment->status === 'Paid') <span class="bg-[#10B981]/10 text-[#10B981] text-[10px] font-bold px-2.5 py-1 rounded-full border border-[#10B981]/20 uppercase tracking-wide">Paid</span>
                                @elseif($payment->status === 'Rejected') <span class="bg-rose-500/10 text-rose-400 text-[10px] font-bold px-2.5 py-1 rounded-full border border-rose-500/20 uppercase tracking-wide">Rejected</span>
                                @else <span class="bg-amber-500/10 text-amber-400 text-[10px] font-bold px-2.5 py-1 rounded-full border border-amber-500/20 uppercase tracking-wide">Pending</span> @endif
                            </td>
                            <td class="py-4 px-6 text-right relative">
                                <div class="relative inline-block text-left">
                                    <button onclick="toggleDropdown('dropdown-{{ $payment->id }}')" class="inline-flex justify-center w-full rounded-md border border-[#3F3F46] shadow-sm px-3 py-1.5 bg-[#27272A] text-xs font-bold text-[#FFFFFF] hover:bg-[#3F3F46] focus:outline-none transition-all">Actions<svg class="-mr-1 ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></button>
                                    <div id="dropdown-{{ $payment->id }}" class="hidden origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-xl bg-[#1C1C1E] border border-[#27272A] ring-1 ring-black ring-opacity-5 z-50">
                                        <div class="py-1" role="menu">
                                            <button data-payment="{{ json_encode($payment) }}" onclick="editPayment(JSON.parse(this.dataset.payment))" class="w-full text-left block px-4 py-2 text-xs text-[#10B981] hover:bg-[#27272A]" role="menuitem"><span class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>Edit Details</span></button>
                                            @if($payment->status !== 'Paid')
                                            <form action="{{ route('admin.payments.updateStatus', $payment->id) }}" method="POST"> @csrf @method('PATCH') <input type="hidden" name="status" value="Paid"> <button type="submit" class="w-full text-left block px-4 py-2 text-xs text-[#FFFFFF] hover:bg-[#27272A]"><span class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Mark as Paid</span></button> </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="py-12 text-center text-[#52525B] italic text-sm">No payment records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-[#27272A] bg-[#121212]/30"> {{ $payments->withQueryString()->links() }} </div>
        </div>
    </main>

    <div id="paymentModal" class="fixed inset-0 bg-black/80 hidden z-50 flex items-center justify-center backdrop-blur-sm transition-opacity opacity-0 pointer-events-none p-4">
        <div class="bg-[#1C1C1E] rounded-xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-200 border border-[#27272A]" id="modalContent">
            <div class="px-6 py-4 border-b border-[#27272A] flex justify-between items-center bg-[#121212] rounded-t-xl">
                <h3 class="text-lg font-bold text-[#FFFFFF]" id="modalTitle">Process New Payment</h3>
                <button onclick="closeModal()" class="text-[#A1A1AA] hover:text-white transition focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form id="paymentForm" action="{{ route('admin.payments.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div id="methodField"></div>
                
                <div>
                    <label class="block text-xs font-bold text-[#A1A1AA] uppercase tracking-wider mb-2">Select Student</label>
                    <select name="user_id" id="user_id" class="w-full bg-[#121212] border border-[#27272A] text-[#FFFFFF] rounded-lg p-3 text-sm focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all appearance-none" required>
                        <option value="" disabled selected>Search or select a student...</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#A1A1AA] uppercase tracking-wider mb-2">Amount (₱)</label>
                    <input type="number" name="amount" id="amount" step="0.01" class="w-full bg-[#121212] border border-[#27272A] text-[#10B981] rounded-lg p-3 text-sm focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none font-bold transition-all placeholder-[#3F3F46]" placeholder="0.00" required>
                </div>

                <input type="hidden" name="payment_type" id="payment_type" value="Cash">

                <div>
                    <label class="block text-xs font-bold text-[#A1A1AA] uppercase tracking-wider mb-2">Reference No. (Optional)</label>
                    <input type="text" name="reference_no" id="reference_no" class="w-full bg-[#121212] border border-[#27272A] text-[#FFFFFF] rounded-lg p-3 text-sm focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all placeholder-[#3F3F46]" placeholder="OR Number">
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-5 py-2.5 text-sm font-semibold text-[#A1A1AA] hover:text-white transition">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 text-sm bg-[#10B981] hover:bg-[#059669] text-white font-bold rounded-lg shadow-md shadow-[#10B981]/10 transition-all uppercase tracking-wide">Save Payment</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('paymentModal');
        const modalContent = document.getElementById('modalContent');
        const form = document.getElementById('paymentForm');
        const title = document.getElementById('modalTitle');
        const methodField = document.getElementById('methodField');

        function openCreateModal() {
            modal.classList.remove('hidden');
            title.innerText = 'Process New Payment';
            form.action = "{{ route('admin.payments.store') }}";
            methodField.innerHTML = '';
            
            document.getElementById('user_id').value = '';
            document.getElementById('amount').value = '500';
            document.getElementById('payment_type').value = 'Cash'; 
            document.getElementById('reference_no').value = '';

            animateModalIn();
        }

        function editPayment(data) {
            modal.classList.remove('hidden');
            title.innerText = 'Edit Payment Details';
            form.action = "/admin/payments/" + data.id; 
            methodField.innerHTML = '<input type="hidden" name="_method" value="PATCH">'; 

            document.getElementById('user_id').value = data.user_id;
            document.getElementById('amount').value = data.amount;
            document.getElementById('payment_type').value = data.payment_method; 
            document.getElementById('reference_no').value = data.reference_no;

            animateModalIn();
        }

        function animateModalIn() {
            setTimeout(() => {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function closeModal() {
            modal.classList.add('opacity-0', 'pointer-events-none');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        function toggleDropdown(id) {
            var dropdown = document.getElementById(id);
            var allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
            allDropdowns.forEach(d => { if (d.id !== id) d.classList.add('hidden'); });
            dropdown.classList.toggle('hidden');
        }

        window.onclick = function(event) {
            if (event.target == modal) closeModal();
            if (!event.target.matches('button') && !event.target.closest('button')) {
                document.querySelectorAll('[id^="dropdown-"]').forEach(d => d.classList.add('hidden'));
            }
        }
    </script>
</body>
</html>