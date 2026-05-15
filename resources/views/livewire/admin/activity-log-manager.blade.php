<div class="space-y-6 animate-in fade-in duration-500">
    <style>
        * { -ms-overflow-style: none; scrollbar-width: none; }
        *::-webkit-scrollbar { display: none; }
    </style>
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-2xl shadow-xl backdrop-blur-md flex items-center gap-3 mb-6">
            <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]"></span>
            <span class="text-xs font-black uppercase tracking-widest">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-[32px] border border-blue-500/10 shadow-xl overflow-hidden">
        <div class="p-8 md:p-10 border-b border-blue-500/10 bg-blue-50/30 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex-shrink-0">
                <h2 class="text-2xl font-black text-black tracking-tight uppercase">Activity Logs</h2>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mt-1">Admin Actions & Audit Trail</p>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
                <div class="relative group w-full sm:w-64">
                    <input type="text" wire:model.live.debounce.500ms="search"
                        placeholder="Search logs..."
                        class="w-full bg-white border border-slate-200 rounded-2xl px-6 py-3.5 text-[11px] font-bold text-black placeholder:text-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500/40 transition-all shadow-sm tracking-wider">
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none opacity-40 group-focus-within:opacity-100 transition-opacity">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                <div class="w-full sm:w-48">
                    <select wire:model.live="actionFilter" class="w-full bg-white border border-slate-200 rounded-2xl px-6 py-3.5 text-[11px] font-black uppercase tracking-widest text-black focus:outline-none focus:ring-2 focus:ring-blue-500/20 appearance-none cursor-pointer transition-all shadow-sm">
                        @foreach($actionTypes as $action)
                            <option value="{{ $action }}">{{ $action === 'All actions' ? 'All Actions' : str_replace('_', ' ', ucwords($action, '_')) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-max w-full text-left border-collapse font-bold">
                <thead class="text-[10px] text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 bg-slate-50/50">
                    <tr>
                        <th class="py-6 px-8 text-left cursor-pointer group/th hover:text-blue-600 transition-colors" wire:click="sortBy('activity_logs.id')">
                            <div class="flex items-center gap-2">
                                ID
                                <span class="transition-opacity {{ $sortField === 'activity_logs.id' ? 'opacity-100' : 'opacity-0 group-hover/th:opacity-50' }}">
                                    @if($sortField === 'activity_logs.id' && $sortDirection === 'asc')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    @endif
                                </span>
                            </div>
                        </th>
                        <th class="py-6 px-8 text-left cursor-pointer group/th hover:text-blue-600 transition-colors" wire:click="sortBy('users.first_name')">
                            <div class="flex items-center gap-2">
                                Admin / User
                                <span class="transition-opacity {{ $sortField === 'users.first_name' ? 'opacity-100' : 'opacity-0 group-hover/th:opacity-50' }}">
                                    @if($sortField === 'users.first_name' && $sortDirection === 'asc')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    @endif
                                </span>
                            </div>
                        </th>
                        <th class="py-6 px-8 text-left text-slate-400">Action</th>
                        <th class="py-6 px-8 text-left text-slate-400">Target</th>
                        <th class="py-6 px-8 text-left text-slate-400">Description</th>
                        <th class="py-6 px-8 text-left cursor-pointer group/th hover:text-blue-600 transition-colors" wire:click="sortBy('activity_logs.created_at')">
                            <div class="flex items-center gap-2">
                                Date & Time
                                <span class="transition-opacity {{ $sortField === 'activity_logs.created_at' ? 'opacity-100' : 'opacity-0 group-hover/th:opacity-50' }}">
                                    @if($sortField === 'activity_logs.created_at' && $sortDirection === 'asc')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    @endif
                                </span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="text-[12px]">
                    @forelse($logs as $log)
                        <tr class="border-b border-slate-50 hover:bg-blue-50/30 transition-colors group/row">
                            <td class="py-5 px-8 text-slate-500 font-medium">
                                #{{ $log->id }}
                            </td>
                            <td class="py-5 px-8">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-black text-xs shadow-lg shadow-blue-500/20">
                                        {{ strtoupper(substr($log->user->first_name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-black font-bold">{{ $log->user->first_name ?? '' }} {{ $log->user->last_name ?? '' }}</div>
                                        <div class="text-slate-400 text-[10px] font-medium">{{ $log->user->role ?? 'admin' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-5 px-8">
                                @php
                                    $actionColors = [
                                        'payment_approved' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                                        'payment_rejected' => 'bg-rose-500/10 text-rose-600 border-rose-500/20',
                                        'application_approved' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                                        'application_rejected' => 'bg-rose-500/10 text-rose-600 border-rose-500/20',
                                        'clearance_approved' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                                        'clearance_revoked' => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
                                    ];
                                    $colorClass = $actionColors[$log->action] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                @endphp
                                <span class="px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $colorClass }}">
                                    {{ str_replace('_', ' ', ucwords($log->action, '_')) }}
                                </span>
                            </td>
                            <td class="py-5 px-8 text-slate-600">
                                {{ $log->target_type }} #{{ $log->target_id }}
                            </td>
                            <td class="py-5 px-8 text-slate-500 font-medium max-w-xs truncate" title="{{ $log->description }}">
                                {{ $log->description }}
                            </td>
                            <td class="py-5 px-8 text-slate-400 font-medium">
                                {{ $log->created_at->format('M d, Y') }}
                                <div class="text-[10px]">{{ $log->created_at->format('h:i A') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 px-8 text-center">
                                <div class="flex flex-col items-center justify-center gap-4">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-slate-400 font-bold text-sm">No activity logs found</p>
                                        <p class="text-slate-300 text-[10px] font-medium">Actions will appear here once recorded</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-6 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>