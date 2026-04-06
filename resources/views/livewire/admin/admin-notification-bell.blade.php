<div class="relative" x-data="{ showDropdown: @entangle('showDropdown') }">
    <button @click="showDropdown = !showDropdown; @this.loadNotifications()" @click.away="showDropdown = false" class="relative p-2 transition focus:outline-none"
        style="color: #8ab4d8;">
        <svg class="w-6 h-6 transition" :style="showDropdown ? 'color: #ffffff' : 'color: #8ab4d8'"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
            </path>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-1 -right-1 flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500 border border-white"></span>
            </span>
        @endif
    </button>

    <template x-if="showDropdown">
        <div class="absolute right-0 top-12 w-80 shadow-2xl rounded-xl z-50 overflow-hidden transform transition-all"
            style="background: rgba(6,13,26,0.97); backdrop-filter: blur(16px); border: 1px solid rgba(26,58,110,0.5);">
            <div class="px-4 py-3 border-b flex justify-between items-center"
                style="background: rgba(13,31,60,0.8); border-color: rgba(26,58,110,0.4);">
                <h3 class="text-sm font-bold uppercase tracking-wide text-white">Notifications</h3>
                @if($unreadCount > 0)
                    <button type="button" wire:click="markAllAsRead" class="text-[10px] font-bold text-rose-400 hover:text-rose-300 uppercase tracking-widest transition-colors">Clear All</button>
                @endif
            </div>
            <div class="max-h-64 overflow-y-auto custom-scrollbar p-2 space-y-2" style="background: rgba(6,13,26,0.6);">
                @if(count($notifications) > 0)
                    @foreach($notifications as $notification)
                        <div class="block p-3 rounded-lg border transition group cursor-pointer hover:bg-white/5 active:scale-[0.98] bg-emerald-500/10 border-emerald-500/30">
                            <p class="text-xs text-white/90 font-bold">
                                {{ $notification->data['message'] ?? 'New activity recorded.' }}
                            </p>
                            <p class="text-[9px] mt-2 text-right text-white/20">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-6 text-sm text-white/20">No new notifications</div>
                @endif
            </div>
            <div class="p-2 border-t text-center" style="background: rgba(13,31,60,0.8); border-color: rgba(26,58,110,0.4);">
                <a href="{{ route('admin.applications.index') }}"
                    class="text-xs font-bold" style="color: #8ab4d8;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#8ab4d8'">View All Applications →</a>
            </div>
        </div>
    </template>
</div>
