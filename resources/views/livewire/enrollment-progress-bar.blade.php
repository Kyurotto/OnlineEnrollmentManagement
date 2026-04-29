<div wire:poll.5s class="mt-10 pt-8 border-t border-white/5">
    <div class="relative">
        {{-- Connection Line --}}
        <div class="absolute top-5 left-0 w-full h-0.5 bg-white/5 -z-0"></div>
        
        <div class="relative z-10 flex justify-between items-start">
            @php
                if ($isOldStudentWithMissingDocs) {
                    // Dynamic steps for old students
                    $progressBar = [];
                    $allLabels = [
                        'online_docs' => 'Upload Online Documents',
                        'physical_docs' => 'Pass Physical Documents',
                        'application' => 'Fill Up Application',
                        'registrar_clearance' => 'Registrar Clearance',
                        'payment' => 'Pay Physical in Cashier',
                        'enroll' => 'Enroll',
                    ];
                    
                    foreach ($oldStudentStepsKeys as $key) {
                        $progressBar[] = ['key' => $key, 'label' => $allLabels[$key]];
                    }
                } else {
                    // Previous 5-step bar for new students or fully cleared students
                    $progressBar = [
                        ['key' => 'application', 'label' => 'Fill Up Application'],
                        ['key' => 'online_docs', 'label' => 'Upload Online Documents'],
                        ['key' => 'physical_docs', 'label' => 'Physical Documents'],
                        ['key' => 'payment', 'label' => 'Cashier Payment'],
                        ['key' => 'enroll', 'label' => 'Enrolled'],
                    ];
                }
            @endphp

            @foreach($progressBar as $step)
                <div class="flex flex-col items-center group flex-1">
                    @php
                        $color = $steps[$step['key']];
                        $circleClass = match($color) {
                            'green' => 'bg-emerald-500 shadow-emerald-500/40 text-white',
                            'ongoing' => 'bg-indigo-500 shadow-indigo-500/40 text-white',
                            'yellow' => 'bg-amber-400 shadow-amber-400/40 text-white animate-pulse',
                            default => 'bg-white/5 border-2 border-white/10 text-white/20'
                        };
                    @endphp
                    
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500 {{ $circleClass }} shadow-lg">
                        {{-- Include 'ongoing' state to show the checkmark icon --}}
                        @if($color === 'green' || $color === 'ongoing')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        @elseif($color === 'yellow')
                            <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        @else
                            <span class="text-[10px] font-black">{{ $loop->iteration }}</span>
                        @endif
                    </div>
                    
                    <span class="mt-4 text-[9px] font-black uppercase tracking-widest text-center px-2 {{ $color !== 'grey' ? 'text-slate-800' : 'text-slate-300' }}">
                        {{ $step['label'] }}
                    </span>
                    
                    {{-- Badge Status Rendering --}}
                    @if($color === 'yellow')
                        <span class="mt-2 text-[7px] font-black uppercase text-amber-600 tracking-[0.2em] bg-amber-50 px-2 py-1 rounded shadow-sm border border-amber-100">Pending</span>
                    @elseif($color === 'ongoing')
                        <span class="mt-2 text-[7px] font-black uppercase text-indigo-600 tracking-[0.2em] bg-indigo-50 px-2 py-1 rounded shadow-sm border border-indigo-100">Ongoing</span>
                    @elseif($color === 'green')
                        <span class="mt-2 text-[7px] font-black uppercase text-emerald-600 tracking-[0.2em] bg-emerald-50 px-2 py-1 rounded shadow-sm border border-emerald-100">Done</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
