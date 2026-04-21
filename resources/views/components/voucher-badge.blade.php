@props(['voucherType', 'size' => 'md'])

@if($voucherType)
    @php
        $isFreeTuition = $voucherType === 'free_tuition';
        $styles = [
            'sm' => [
                'container' => 'inline-flex items-center gap-1 px-2 py-1 rounded-lg',
                'icon' => 'w-3 h-3',
                'text' => 'text-[8px] font-bold'
            ],
            'md' => [
                'container' => 'inline-flex items-center gap-2 px-3 py-1.5 rounded-xl',
                'icon' => 'w-4 h-4',
                'text' => 'text-[9px] font-bold'
            ],
            'lg' => [
                'container' => 'inline-flex items-center gap-2 px-4 py-2 rounded-2xl',
                'icon' => 'w-5 h-5',
                'text' => 'text-sm font-bold'
            ]
        ];
        $style = $styles[$size] ?? $styles['md'];
        $bgColor = $isFreeTuition ? 'bg-green-500/10' : 'bg-yellow-500/10';
        $borderColor = $isFreeTuition ? 'border-green-500/20' : 'border-yellow-500/20';
        $textColor = $isFreeTuition ? 'text-green-400' : 'text-yellow-400';
    @endphp

    <div class="{{ $style['container'] }} border {{ $bgColor }} {{ $borderColor }} {{ $textColor }}">
        <svg class="{{ $style['icon'] }} fill-current" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/>
        </svg>
        <span class="{{ $style['text'] }} uppercase tracking-wider">
            {{ $isFreeTuition ? 'Free Tuition' : 'Discounted' }}
        </span>
    </div>
@endif
