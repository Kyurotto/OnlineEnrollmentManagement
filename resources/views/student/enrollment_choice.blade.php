<x-layouts.student title="Choose Enrollment Level">
    <div class="max-w-4xl mx-auto py-20 text-center">
        <div class="space-y-4 mb-12">
            <h2 class="text-4xl font-bold text-white mb-2">Select Enrollment Level</h2>
            <p class="text-white/50 text-lg">Choose your educational path to get started with your enrollment</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Senior High School Card --}}
            <a href="{{ route('student.enrollment.create', ['level' => 'shs']) }}"
               class="group relative p-10 rounded-2xl border border-white/10 bg-white/5 hover:bg-emerald-500/10 hover:border-emerald-500/50 transition-all duration-300 transform hover:-translate-y-2 hover:shadow-2xl hover:shadow-emerald-500/20">

                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl" style="background: radial-gradient(circle at right, rgba(16, 185, 129, 0.1), transparent);"></div>

                <div class="relative z-10">
                    <div class="text-emerald-400 mb-6 flex justify-center">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        </svg>
                    </div>

                    <h3 class="text-2xl font-bold text-white mb-2 group-hover:text-emerald-400 transition-colors">Senior High School</h3>
                    <p class="text-white/60 text-sm mb-6">Academic & Technical-Vocational Tracks</p>

                    <div class="space-y-2 text-left mb-6">
                        <p class="text-xs text-emerald-400/80 font-semibold">Available Strands:</p>
                        <ul class="text-xs text-white/40 space-y-1">
                            <li class="flex items-center"><span class="w-1 h-1 bg-emerald-400 rounded-full mr-2"></span>STEM, HUMSS, GAS, ABM</li>
                            <li class="flex items-center"><span class="w-1 h-1 bg-emerald-400 rounded-full mr-2"></span>Home Economics & ICT (Tech-Voc)</li>
                        </ul>
                    </div>

                    <div class="inline-flex items-center gap-2 text-emerald-400 font-bold text-sm">
                        Get Started
                        <svg class="w-4 h-4 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- College Card --}}
            <a href="{{ route('student.enrollment.create', ['level' => 'college']) }}"
               class="group relative p-10 rounded-2xl border border-white/10 bg-white/5 hover:bg-blue-500/10 hover:border-blue-500/50 transition-all duration-300 transform hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-500/20">

                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl" style="background: radial-gradient(circle at right, rgba(59, 130, 246, 0.1), transparent);"></div>

                <div class="relative z-10">
                    <div class="text-blue-400 mb-6 flex justify-center">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>

                    <h3 class="text-2xl font-bold text-white mb-2 group-hover:text-blue-400 transition-colors">College</h3>
                    <p class="text-white/60 text-sm mb-6">Undergraduate Programs</p>

                    <div class="space-y-2 text-left mb-6">
                        <p class="text-xs text-blue-400/80 font-semibold">Available Programs:</p>
                        <ul class="text-xs text-white/40 space-y-1">
                            <li class="flex items-center"><span class="w-1 h-1 bg-blue-400 rounded-full mr-2"></span>ACT, BSIS, BTVTED</li>
                            <li class="flex items-center"><span class="w-1 h-1 bg-blue-400 rounded-full mr-2"></span>DHRT, DIT & More</li>
                        </ul>
                    </div>

                    <div class="inline-flex items-center gap-2 text-blue-400 font-bold text-sm">
                        Get Started
                        <svg class="w-4 h-4 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>
        </div>

        <p class="text-white/30 text-xs mt-12 uppercase tracking-widest">You can change your selection later if needed</p>
    </div>
</x-layouts.student>
