<div wire:poll.10s class="relative" x-data="{ showDropdown: @entangle('showDropdown') }">
    <button @click="showDropdown = !showDropdown; @this.loadNotifications()" @click.away="showDropdown = false" class="relative p-2 transition focus:outline-none group">
        <svg class="w-6 h-6 transition-colors {{ $unreadCount > 0 ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
            </path>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-1 -right-1 flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-600 border border-white"></span>
            </span>
        @endif
    </button>

    <template x-if="showDropdown">
        <div class="absolute right-0 top-12 w-80 bg-white shadow-[0_10px_40px_rgba(30,58,138,0.15)] rounded-2xl z-50 overflow-hidden border border-blue-500/10 transform animate-in fade-in zoom-in-95 duration-200">
            <div class="px-5 py-4 border-b border-blue-500/10 flex justify-between items-center bg-blue-50/30">
                <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-800">Notifications</h3>
                @if($unreadCount > 0)
                    <button type="button" wire:click="markAllAsRead" class="text-[9px] font-bold text-rose-500 hover:text-rose-600 uppercase tracking-widest transition-colors">Clear All</button>
                @endif
            </div>
            <div class="max-h-64 overflow-y-auto custom-scrollbar p-3 space-y-2 bg-white">
                @if(count($notifications) > 0)
                    @foreach($notifications as $notification)
                        @php
                            $level = $notification->data['level'] ?? null;
                            if (!$level && !empty($notification->data['enrollment_id'])) {
                                $level = optional(\App\Models\Enrollment::find($notification->data['enrollment_id']))->level;
                            }
                            $targetRoute = $level === 'shs'
                                ? route('registrar.applications.shs')
                                : route('registrar.applications.college');
                        @endphp
                        <a href="javascript:void(0)"
                            wire:click="markAndNavigate('{{ $notification->id }}', '{{ $targetRoute }}')"
                            class="block p-4 rounded-xl border transition group cursor-pointer hover:bg-blue-50/50 active:scale-[0.98] bg-blue-50/20 border-blue-500/5">
                            <p class="text-xs text-slate-800 font-medium tracking-tight leading-tight">
                                {{ $notification->data['message'] ?? 'New activity recorded.' }}
                            </p>
                            <p class="text-[9px] mt-2 text-right text-slate-400 font-medium uppercase">{{ $notification->created_at->diffForHumans() }}</p>
                        </a>
                    @endforeach
                @else
                    <div class="text-center py-12">
                        <p class="text-[10px] font-medium text-slate-300 uppercase tracking-[0.4em]">No active alerts</p>
                    </div>
                @endif
            </div>
            <div class="p-3 border-t border-blue-500/10 text-center bg-blue-50/30">
                <a href="{{ route('registrar.applications.index') }}"
                    class="text-[10px] font-bold text-blue-600 hover:text-blue-700 uppercase tracking-widest transition-all">View All Protocols →</a>
            </div>
        </div>
    </template>
</div>
