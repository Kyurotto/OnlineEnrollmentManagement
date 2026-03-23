<nav class="sticky top-0 z-20 shadow-lg border-b"
    style="background: rgba(6,13,26,0.95); backdrop-filter: blur(12px); border-color: rgba(26,58,110,0.4);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <div class="flex items-center gap-3">
                    <div class="text-white font-bold p-2 rounded-lg text-sm"
                        style="background: linear-gradient(135deg, #0d1f3c, #1a3a6e); box-shadow: 0 4px 14px rgba(13,31,60,0.6);">
                        AD</div>
                    <div>
                        <h1 class="text-lg font-bold leading-none text-white">Admin Panel</h1>
                        <span class="text-xs" style="color: #8ab4d8;">
                            @if ($currentRoute === 'admin.dashboard')
                                Dashboard
                            @elseif($currentRoute === 'admin.applications.index')
                                Manage Applications
                            @elseif($currentRoute === 'admin.courses.index')
                                Manage Courses
                            @elseif($currentRoute === 'admin.payments.index')
                                Manage Payments
                            @elseif($currentRoute === 'admin.students.index')
                                Manage Students
                            @endif
                        </span>
                    </div>
                </div>
                <div class="flex space-x-6 text-sm font-medium h-16" style="color: #8ab4d8;">
                    <a wire:navigate href="{{ route('admin.dashboard') }}"
                        class="flex items-center transition h-full"
                        style="{{ $currentRoute === 'admin.dashboard' ? 'color: #a8d5f5; border-bottom: 2px solid #1a3a6e;' : '' }}"
                        onmouseover="this.style.color='#ffffff'"
                        onmouseout="this.style.color='{{ $currentRoute === 'admin.dashboard' ? '#a8d5f5' : '#8ab4d8' }}'">Dashboard</a>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <!-- Notifications Dropdown -->
                <div class="relative mr-2">
                    <button wire:click="toggleDropdown" class="relative p-2 transition focus:outline-none"
                        style="color: #8ab4d8;">
                        <svg class="w-6 h-6 transition" style="color: {{ $showDropdown ? '#ffffff' : '#8ab4d8' }};"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        @if ($newEnrolleesCount > 0)
                            <span class="absolute top-1 -right-1 flex h-3 w-3">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span
                                    class="relative inline-flex rounded-full h-3 w-3 bg-rose-500 border border-white"></span>
                            </span>
                        @endif
                    </button>

                    @if ($showDropdown)
                        <div class="absolute right-0 top-12 w-80 shadow-2xl rounded-xl z-50 overflow-hidden transform transition-all"
                            style="background: rgba(6,13,26,0.97); backdrop-filter: blur(16px); border: 1px solid rgba(26,58,110,0.5);">
                            <div class="absolute inset-0 z-[-1]" wire:click="toggleDropdown"></div>
                            <div class="px-4 py-3 border-b flex justify-between items-center"
                                style="background: rgba(13,31,60,0.8); border-color: rgba(26,58,110,0.4);">
                                <h3 class="text-sm font-bold uppercase tracking-wide text-white">Notifications</h3>
                                @if ($newEnrolleesCount > 0)
                                    <span
                                        class="bg-rose-100 text-rose-600 text-xs font-bold px-2 py-0.5 rounded-full">{{ $newEnrolleesCount }}
                                        New</span>
                                @endif
                            </div>

                            <div class="max-h-64 overflow-y-auto custom-scrollbar p-2 space-y-2"
                                style="background: rgba(6,13,26,0.6);">
                                @if (isset($notifications) && count($notifications) > 0)
                                    @foreach ($notifications as $notif)
                                        <a wire:navigate href="{{ route('admin.applications.index') }}"
                                            class="block p-3 rounded-lg border transition group cursor-pointer"
                                            style="background: rgba(13,31,60,0.6); border-color: rgba(26,58,110,0.3);"
                                            onmouseover="this.style.borderColor='rgba(26,58,110,0.9)'"
                                            onmouseout="this.style.borderColor='rgba(26,58,110,0.3)'">
                                            @if ($notif->status === 'Enrolled')
                                                @php
                                                    $paidAmount = \App\Models\Payment::where(
                                                        'application_id',
                                                        $notif->id,
                                                    )->value('amount');
                                                @endphp
                                                <p class="text-sm font-bold flex items-center gap-1"
                                                    style="color: #8ab4d8;">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Student Paid
                                                </p>
                                                <p class="text-xs mt-1" style="color: #8ab4d8;">
                                                    <span
                                                        class="font-bold text-white uppercase">{{ $notif->first_name ?? ($notif->user->first_name ?? '') }}
                                                        {{ $notif->last_name ?? ($notif->user->last_name ?? '') }}</span>
                                                    is now <span class="font-bold" style="color: #a8d5f5;">PAID
                                                        ₱{{ number_format($paidAmount ?? 0, 2) }}</span>.
                                                </p>
                                            @else
                                                <p class="text-sm font-bold text-white">New Application</p>
                                                <p class="text-xs mt-1" style="color: #8ab4d8;">
                                                    <span
                                                        class="font-medium text-white uppercase">{{ $notif->first_name ?? ($notif->user->first_name ?? '') }}
                                                        {{ $notif->last_name ?? ($notif->user->last_name ?? '') }}</span>
                                                    applied for <span class="uppercase font-bold"
                                                        style="color: #a8d5f5;">{{ $notif->course_code }}</span>.
                                                </p>
                                            @endif
                                            <p class="text-xs mt-2 text-right" style="color: #4a6fa5;">
                                                {{ $notif->updated_at->diffForHumans() }}</p>
                                        </a>
                                    @endforeach
                                @else
                                    <div class="text-center py-6 text-sm" style="color: #4a6fa5;">No notifications</div>
                                @endif
                            </div>
                            <div class="p-2 border-t text-center"
                                style="background: rgba(13,31,60,0.8); border-color: rgba(26,58,110,0.4);">
                                <a wire:navigate href="{{ route('admin.applications.index') }}"
                                    class="text-xs font-bold" style="color: #8ab4d8;"
                                    onmouseover="this.style.color='#ffffff'"
                                    onmouseout="this.style.color='#8ab4d8'">View All Applications →</a>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    @if ($currentRoute === 'admin.dashboard')
                        <div class="text-right hidden sm:block">
                            <div class="text-xs" style="color: #8ab4d8;">Signed in as</div>
                            <div class="text-sm font-bold text-white">Administrator</div>
                        </div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="text-white text-sm font-semibold py-2 px-4 rounded-full transition-all"
                                style="background: rgba(220,38,38,0.8); border: 1px solid rgba(220,38,38,0.5);"
                                onmouseover="this.style.background='rgba(220,38,38,1)'"
                                onmouseout="this.style.background='rgba(220,38,38,0.8)'">Logout</button>
                        </form>
                    @endif

                    <!-- Mobile Menu Button -->
                    <button wire:click="toggleMobileMenu" class="sm:hidden p-2 rounded-lg transition"
                        style="color: #8ab4d8; background: rgba(255,255,255,0.05);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if ($showMobileMenu)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16m-7 6h7"></path>
                            @endif
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="sm:hidden overflow-hidden transition-all duration-300 {{ $showMobileMenu ? 'max-h-[500px] opacity-100 border-t' : 'max-h-0 opacity-0 border-none' }}"
        style="background: rgba(6,13,26,0.98); border-color: rgba(26,58,110,0.4);">
        <div class="px-4 py-6 space-y-4">
            <a wire:navigate href="{{ route('admin.dashboard') }}"
                class="block py-3 px-4 rounded-xl text-sm font-bold transition-all {{ $currentRoute === 'admin.dashboard' ? 'text-white' : '' }}"
                style="{{ $currentRoute === 'admin.dashboard' ? 'background: rgba(26,58,110,0.4); color: #a8d5f5;' : 'color: #8ab4d8;' }}">
                Dashboard
            </a>

            @if ($currentRoute === 'admin.dashboard')
                <div class="pt-4 border-t border-white/5 font-bold">
                    <div class="px-4 py-2">
                        <p class="text-[10px] font-black uppercase tracking-widest" style="color: #4a6fa5;">Account Manager
                        </p>
                        <p class="text-sm font-bold text-white mt-1">Administrator</p>
                    </div>
                </div>
            @endif
        </div>
</nav>
