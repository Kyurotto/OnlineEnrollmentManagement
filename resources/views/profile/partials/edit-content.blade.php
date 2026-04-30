@php
    $roleName = match(auth()->user()->role) {
        'cashier' => 'Cashier',
        'registrar' => 'Registrar',
        'admin' => 'Admin',
        default => 'User'
    };
@endphp

<div class="max-w-5xl mx-auto space-y-10 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-12">
        <div>
            <h2 class="text-4xl font-black text-slate-900 tracking-tight">{{ $roleName }} Profile</h2>
            <p class="text-[10px] mt-2 font-black uppercase tracking-[0.25em] text-slate-400">Account Management & Security</p>
        </div>
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-8 py-3.5 rounded-full bg-white border border-blue-500/10 text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] hover:bg-blue-50 transition-all shadow-xl shadow-blue-900/5 active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Dashboard
        </a>
    </div>

    <div class="space-y-10">
        <!-- Profile Information Section -->
        <div class="p-12 rounded-[2.5rem] border bg-white shadow-2xl shadow-blue-900/5 transition-all hover:shadow-blue-900/10"
             style="border-color: rgba(37,99,235,0.1);">
            @include('profile.partials.update-profile-information-form')
        </div>

        <!-- Update Password Section -->
        <div class="p-12 rounded-[2.5rem] border bg-white shadow-2xl shadow-blue-900/5 transition-all hover:shadow-blue-900/10"
             style="border-color: rgba(37,99,235,0.1);">
            @include('profile.partials.update-password-form')
        </div>

        <!-- Danger Zone: Delete Account -->
        <div class="p-12 rounded-[2.5rem] border border-rose-100 bg-white shadow-2xl shadow-rose-900/5 transition-all hover:shadow-rose-900/10">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
