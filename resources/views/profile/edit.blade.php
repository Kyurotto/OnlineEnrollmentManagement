@php
    $role = auth()->user()->role ?? 'admin';
    $title = "Account Settings";
@endphp

@if($role === 'cashier')
    <x-layouts.cashier :title="$title">
        @include('profile.partials.edit-content')
    </x-layouts.cashier>
@elseif($role === 'registrar')
    <x-layouts.registrar :title="$title">
        @include('profile.partials.edit-content')
    </x-layouts.registrar>
@elseif($role === 'teacher')
    <x-layouts.teacher :title="$title">
        @include('profile.partials.edit-content')
    </x-layouts.teacher>
@else
    <x-layouts.admin :title="$title">
        @include('profile.partials.edit-content')
    </x-layouts.admin>
@endif
