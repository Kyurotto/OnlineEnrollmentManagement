<x-layouts.teacher title="Teacher Profile">
    <div class="w-full">
        <div class="space-y-8 animate-in fade-in duration-700">

            {{-- Header --}}
            <div class="p-10 rounded-[2rem] border relative overflow-hidden group shadow-xl shadow-blue-900/5 bg-white"
                style="border-color: rgba(37,99,235,0.1);">

                <div
                    class="absolute top-0 right-0 p-12 opacity-[0.03] mt-[-20px] mr-[-20px] transition-transform group-hover:scale-110 duration-700">
                    <svg class="w-64 h-64 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>

                <div class="flex items-center gap-8 relative z-10">
                    <div
                        class="w-20 h-20 rounded-2xl bg-blue-600 border border-blue-400/20 flex items-center justify-center text-white shadow-2xl shadow-blue-600/30">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-4xl font-black text-slate-900 leading-tight tracking-tight">My Profile</h2>
                        <p class="text-xs mt-2 font-bold uppercase tracking-[0.25em] text-slate-400">Teacher Account
                            Information</p>
                    </div>
                </div>
            </div>

            {{-- Profile Cards --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- Account Info --}}
                <div class="p-8 rounded-[2rem] border bg-white shadow-xl shadow-blue-900/5"
                    style="border-color: rgba(37,99,235,0.1);">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-2 h-8 bg-blue-600 rounded-full shadow-lg shadow-blue-600/30"></div>
                        <h3 class="text-lg font-black text-slate-900 tracking-tight uppercase">Account Details</h3>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-center justify-between py-4 border-b border-slate-50">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Full
                                Name</span>
                            <span class="text-sm font-black text-slate-800 uppercase tracking-tight">
                                {{ $user->first_name }} {{ $user->middle_name ?? '' }} {{ $user->last_name }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-4 border-b border-slate-50">
                            <span
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Username</span>
                            <span class="text-sm font-bold text-slate-600">{{ $user->username }}</span>
                        </div>
                        <div class="flex items-center justify-between py-4 border-b border-slate-50">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Email</span>
                            <span class="text-sm font-bold text-slate-600">{{ $user->email }}</span>
                        </div>
                        <div class="flex items-center justify-between py-4 border-b border-slate-50">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Role</span>
                            <span
                                class="text-xs font-black text-blue-600 bg-blue-50 px-4 py-1.5 rounded-full border border-blue-100 uppercase tracking-widest">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-4">
                            <span
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</span>
                            <span
                                class="text-xs font-black text-emerald-600 bg-emerald-50 px-4 py-1.5 rounded-full border border-emerald-100 uppercase tracking-widest">
                                {{ $user->status ?? 'Active' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Employee Info --}}
                <div class="p-8 rounded-[2rem] border bg-white shadow-xl shadow-blue-900/5"
                    style="border-color: rgba(37,99,235,0.1);">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-2 h-8 bg-indigo-600 rounded-full shadow-lg shadow-indigo-600/30"></div>
                        <h3 class="text-lg font-black text-slate-900 tracking-tight uppercase">Employee Record</h3>
                    </div>

                    @if ($employee)
                        <div class="space-y-6">
                            <div class="flex items-center justify-between py-4 border-b border-slate-50">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Employee
                                    ID</span>
                                <span
                                    class="text-sm font-mono font-bold text-slate-600">#{{ str_pad($employee->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="flex items-center justify-between py-4 border-b border-slate-50">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Full
                                    Name</span>
                                <span class="text-sm font-black text-slate-800 uppercase tracking-tight">
                                    {{ $employee->first_name }} {{ $employee->middle_name ?? '' }}
                                    {{ $employee->last_name }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-4 border-b border-slate-50">
                                <span
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Phone</span>
                                <span
                                    class="text-sm font-bold text-slate-600">{{ $employee->phone ?? 'Not set' }}</span>
                            </div>
                            <div class="flex items-center justify-between py-4 border-b border-slate-50">
                                <span
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Address</span>
                                <span
                                    class="text-sm font-bold text-slate-600 text-right max-w-[250px]">{{ $employee->address ?? 'Not set' }}</span>
                            </div>
                            <div class="flex items-center justify-between py-4">
                                <span
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Department
                                    Role</span>
                                <span
                                    class="text-xs font-black text-indigo-600 bg-indigo-50 px-4 py-1.5 rounded-full border border-indigo-100 uppercase tracking-widest">
                                    {{ ucfirst($employee->role) }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <div class="text-slate-300">
                                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <p class="text-sm font-black uppercase tracking-widest">No employee record found</p>
                                <p class="text-xs text-slate-300 mt-2">Contact the administrator to link your employee
                                    profile.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.teacher>
