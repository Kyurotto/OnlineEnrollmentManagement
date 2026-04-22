<x-layouts.registrar title="Application Details">
    <div class="max-w-5xl mx-auto animate-in fade-in slide-in-from-bottom-8 duration-700">
        <div class="mb-10 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('registrar.applications.index') }}" class="group/back flex items-center justify-center w-12 h-12 rounded-2xl bg-white/5 border border-white/10 text-white/40 hover:text-cyan-400 hover:border-cyan-500/30 transition-all active:scale-95 shadow-xl">
                    <svg class="w-5 h-5 group-hover/back:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h2 class="text-3xl font-black text-white tracking-tight uppercase italic">Application Details</h2>
                    <p class="text-[10px] text-white/30 uppercase tracking-[0.3em] mt-1 italic">Review Process • ID #{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
            @if (in_array(strtolower($application->status), ['pending', 'enrolled', 'paid']))
                <div class="hidden md:flex items-center gap-4">
                    <!-- Voucher Button -->
                    <div class="relative group/voucher">
                        <button class="group px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all active:scale-95 shadow-xl bg-purple-500/10 text-purple-400 border border-purple-500/20 hover:bg-purple-500/20 hover:border-purple-500/40 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>
                            Voucher
                            <svg class="w-4 h-4 transform group-hover/voucher:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        </button>

                        <!-- Voucher Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-slate-900 border border-white/10 rounded-2xl shadow-2xl opacity-0 invisible group-hover/voucher:opacity-100 group-hover/voucher:visible transition-all duration-200 z-50">
                            @if($application->voucher_type)
                                <div class="p-3 border-b border-white/5">
                                    <p class="text-[9px] font-black text-white/40 uppercase tracking-wider mb-2">Current Voucher</p>
                                    <div class="flex items-center gap-2 p-3 rounded-xl {{ $application->voucher_type === 'free_tuition' ? 'bg-green-500/10 border border-green-500/20' : 'bg-yellow-500/10 border border-yellow-500/20' }}">
                                        <svg class="w-4 h-4 {{ $application->voucher_type === 'free_tuition' ? 'text-green-400' : 'text-yellow-400' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>
                                        <span class="text-[10px] font-bold {{ $application->voucher_type === 'free_tuition' ? 'text-green-400' : 'text-yellow-400' }}">
                                            {{ $application->voucher_type === 'free_tuition' ? 'Free Tuition' : 'Discounted' }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                            <button wire:click="removeVoucher({{ $application->id }})" class="w-full text-left px-4 py-3 text-[10px] font-bold text-red-400 hover:bg-red-500/10 transition-colors">
                                Remove Voucher
                            </button>
                            <button wire:click="applyVoucher({{ $application->id }}, 'free_tuition')" class="w-full text-left px-4 py-3 text-[10px] font-bold text-green-400 hover:bg-green-500/10 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>
                                Free Tuition
                            </button>
                            <button wire:click="applyVoucher({{ $application->id }}, 'discounted')" class="w-full text-left px-4 py-3 text-[10px] font-bold text-yellow-400 hover:bg-yellow-500/10 transition-colors flex items-center gap-2 border-t border-white/5">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>
                                Discounted
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('registrar.applications.toggle-physical', $application->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all active:scale-95 shadow-xl {{ $application->physical_documents_received ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : 'bg-cyan-500 text-black' }}">
                            {{ $application->physical_documents_received ? 'Cancel Physical Receipt' : 'Received Physical Documents' }}
                        </button>
                    </form>
                    <form action="{{ route('registrar.applications.update', $application->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Approved">
                        <button type="submit" class="bg-emerald-500 hover:bg-emerald-400 text-white text-[10px] font-black py-4 px-10 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl shadow-emerald-500/10 active:scale-95">
                            Approve
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="glass-card rounded-[48px] border-white/5 shadow-2xl overflow-hidden relative group">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-cyan-500/10 blur-[100px] rounded-full group-hover:bg-cyan-500/15 transition-colors duration-1000"></div>

            <div class="p-10 md:p-14 relative z-10 space-y-16">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
                    {{-- Student Information --}}
                    <div class="lg:col-span-2 space-y-8">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                            <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Student Profile & Details</h3>
                        </div>
                        <div class="grid grid-cols-1 gap-8 bg-white/[0.02] border border-white/5 rounded-[40px] p-10 space-y-6">
                            {{-- Full Name Section --}}
                            <div class="flex flex-col gap-1">
                                <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Full Name</span>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-xl font-black text-white uppercase italic tracking-tight">{{ $application->last_name ?? 'N/A' }}, {{ $application->first_name ?? 'N/A' }} {{ $application->middle_name ?? '' }}</span>
                                    @if($application->extension)
                                    <span class="text-sm font-bold text-white/40 italic">{{ $application->extension }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Basic Information --}}
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 pt-6 border-t border-white/5">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Gender</span>
                                    <span class="text-sm font-bold text-white/60 capitalize">{{ $application->gender ?? 'N/A' }}</span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Birth Date</span>
                                    <span class="text-sm font-bold text-white/60">{{ $application->birth_date ? \Carbon\Carbon::parse($application->birth_date)->format('M d, Y') : 'N/A' }}</span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Age</span>
                                    <span class="text-sm font-bold text-white/60">{{ $application->age ?? 'N/A' }} Years</span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">LRN</span>
                                    <span class="text-sm font-bold text-white/60">{{ $application->lrn ?? 'N/A' }}</span>
                                </div>
                            </div>

                            {{-- Religion & Church --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-white/5">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Religion</span>
                                    <span class="text-sm font-bold text-white/60">{{ $application->religion ?? 'N/A' }}</span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Religious Affiliation</span>
                                    <span class="text-sm font-bold text-white/60">{{ $application->religion_church ?? 'N/A' }}</span>
                                </div>
                            </div>

                            {{-- Contact & Email --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-white/5">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Email Address</span>
                                    <span class="text-sm font-bold text-white/60 lowercase">{{ $application->email ?? 'N/A' }}</span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Contact Number</span>
                                    <span class="text-sm font-bold text-white/60">{{ $application->contact ?? 'N/A' }}</span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Facebook Account</span>
                                    <span class="text-sm font-bold text-white/60">{{ $application->facebook_account ?? 'N/A' }}</span>
                                </div>
                            </div>

                            {{-- Birth & Current Address --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-white/5">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Birthplace</span>
                                    <span class="text-sm font-bold text-white/60">{{ $application->birthplace ?? 'N/A' }}</span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Current Address</span>
                                    <span class="text-sm font-bold text-white/60">{{ $application->address_full ?? 'N/A' }}</span>
                                </div>
                            </div>

                            {{-- Educational Background (SHS Only) --}}
                            @if($application->level === 'shs')
                            <div class="pt-6 border-t border-white/5 space-y-6">
                                <h4 class="text-[10px] font-black text-cyan-400/60 uppercase tracking-[0.2em] italic">Educational Background</h4>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Junior High School Attended</span>
                                    <span class="text-sm font-bold text-white/60">{{ $application->junior_high_school ?? 'N/A' }}</span>
                                </div>
                            </div>
                            @endif

                            {{-- Family Information --}}
                            <div class="pt-6 border-t border-white/5 space-y-6">
                                <h4 class="text-[10px] font-black text-cyan-400/60 uppercase tracking-[0.2em] italic">Family Background</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Father's Name</span>
                                        <span class="text-sm font-bold text-white/60">{{ $application->father_name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Mother's Maiden Name</span>
                                        <span class="text-sm font-bold text-white/60">{{ $application->mother_maiden_name ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                {{-- Guardian Info --}}
                                @if($application->guardian_name)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Guardian's Name</span>
                                        <span class="text-sm font-bold text-white/60">{{ $application->guardian_name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Guardian's Contact</span>
                                        <span class="text-sm font-bold text-white/60">{{ $application->guardian_contact ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>

                            {{-- Health Information --}}
                            @if($application->health_concerns)
                            <div class="pt-6 border-t border-white/5 space-y-6">
                                <h4 class="text-[10px] font-black text-pink-400/60 uppercase tracking-[0.2em] italic">Health Information</h4>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Health Concerns / Medical Issues</span>
                                    <p class="text-sm font-bold text-white/60 leading-relaxed">{{ $application->health_concerns }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Program & Lifecycle --}}
                    <div class="space-y-8">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                            <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Enrollment Details</h3>
                        </div>
                        <div class="bg-cyan-500/5 border border-cyan-500/10 rounded-[40px] p-10 space-y-10 h-full flex flex-col justify-start shadow-inner">
                            {{-- Strand/Program --}}
                            <div class="flex flex-col gap-2">
                                <span class="text-[9px] font-black text-cyan-400 uppercase tracking-widest italic">Strand / Program</span>
                                <span class="text-3xl font-black text-white uppercase italic tracking-tighter leading-tight">{{ $application->course_code ?? 'N/A' }}</span>
                                <span class="text-[10px] font-bold text-white/30 uppercase tracking-wider">{{ $application->course?->course_description ?? '' }}</span>
                            </div>

                            {{-- Year Level --}}
                            <div class="pt-6 border-t border-cyan-500/10">
                                <h4 class="text-[10px] font-black text-cyan-400/60 uppercase tracking-[0.2em] italic mb-4">Academic Information</h4>
                                <div class="grid grid-cols-1 gap-6">
                                    @php
                                        // Parse year_level which is formatted as "Year | Semester | Academic Year"
                                        $yearParts = array_map('trim', explode('|', $application->year_level ?? ''));
                                        $yearLevel = $yearParts[0] ?? 'N/A';
                                        $semester = $yearParts[1] ?? 'N/A';
                                        $academicYear = $yearParts[2] ?? 'N/A';
                                    @endphp
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Year Level</span>
                                        <span class="text-lg font-black text-white uppercase italic">{{ $yearLevel }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Semester & Academic Year --}}
                            <div class="pt-6 border-t border-cyan-500/10">
                                <h4 class="text-[10px] font-black text-cyan-400/60 uppercase tracking-[0.2em] italic mb-4">Schedule Information</h4>
                                <div class="grid grid-cols-2 gap-6">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Semester</span>
                                        <span class="text-sm font-bold text-white/60">{{ $semester }}</span>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Academic Year</span>
                                        <span class="text-sm font-bold text-white/60">{{ $academicYear }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="pt-6 border-t border-cyan-500/10">
                                <h4 class="text-[10px] font-black text-cyan-400/60 uppercase tracking-[0.2em] italic mb-4">Application Status</h4>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Status</span>
                                    <span class="text-lg font-black text-cyan-400 uppercase tracking-[0.1em] italic">{{ $application->status ?? 'N/A' }}</span>
                                </div>
                            </div>

                            {{-- Voucher Status --}}
                            @if($application->voucher_type)
                            <div class="pt-6 border-t border-cyan-500/10">
                                <h4 class="text-[10px] font-black text-cyan-400/60 uppercase tracking-[0.2em] italic mb-4">Voucher Information</h4>
                                <div class="flex items-center gap-3 p-4 rounded-2xl {{ $application->voucher_type === 'free_tuition' ? 'bg-green-500/10 border border-green-500/20' : 'bg-yellow-500/10 border border-yellow-500/20' }}">
                                    <svg class="w-6 h-6 flex-shrink-0 {{ $application->voucher_type === 'free_tuition' ? 'text-green-400' : 'text-yellow-400' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>
                                    <div>
                                        <span class="text-[9px] font-black text-white/40 uppercase tracking-widest">Voucher Type</span>
                                        <p class="text-sm font-bold {{ $application->voucher_type === 'free_tuition' ? 'text-green-400' : 'text-yellow-400' }} uppercase">
                                            {{ $application->voucher_type === 'free_tuition' ? 'Free Tuition' : 'Discounted' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Application Timeline --}}
                            <div class="pt-6 border-t border-cyan-500/10">
                                <h4 class="text-[10px] font-black text-cyan-400/60 uppercase tracking-[0.2em] italic mb-4">Timeline</h4>
                                <div class="grid grid-cols-1 gap-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Submitted Date</span>
                                        <span class="text-sm font-bold text-white/60">{{ $application->created_at ? $application->created_at->format('F d, Y - g:i A') : 'N/A' }}</span>
                                    </div>
                                    @if($application->updated_at && $application->updated_at->ne($application->created_at))
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Last Updated</span>
                                        <span class="text-sm font-bold text-white/60">{{ $application->updated_at->format('F d, Y - g:i A') }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Document Assets --}}
                <div class="space-y-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                            <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Required Documents</h3>
                        </div>
                        @php
                            $isSHS = in_array($application->course_code, ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT']);
                        @endphp
                        <span class="px-3 py-1 text-[9px] font-bold rounded-full border {{ $isSHS ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-blue-500/10 text-blue-400 border-blue-500/30' }}">
                            {{ $isSHS ? 'SHS Documents' : 'College Documents' }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        @php
                            $isSHS = in_array($application->course_code, ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT']);
                            $docs = $isSHS
                                ? [
                                    'form_137_path' => 'JHS Card (SF9)',
                                    'sf10_path' => 'SF10 Record',
                                    'good_moral_path' => 'Good Moral',
                                    'psa_path' => 'PSA Birth Cert',
                                    'id_picture_path' => '2x2 ID Picture'
                                ]
                                : [
                                    'form_137_path' => 'Form 137',
                                    'good_moral_path' => 'Good Moral',
                                    'psa_path' => 'PSA Birth Cert',
                                    'id_picture_path' => 'ID Picture'
                                ];
                        @endphp

                        @foreach ($docs as $path => $label)
                            <div class="space-y-4">
                                <div class="flex items-center gap-2">
                                    @if (!empty($application->$path))
                                        <div class="flex items-center justify-center w-5 h-5 bg-emerald-500/20 border-2 border-emerald-500 rounded-full shrink-0">
                                            <span class="text-emerald-500 font-black text-xs">✓</span>
                                        </div>
                                        <span class="text-[9px] font-black uppercase text-white tracking-widest">{{ $label }}</span>
                                    @else
                                        <div class="flex items-center justify-center w-5 h-5 bg-rose-500/20 border-2 border-rose-500 rounded-full shrink-0">
                                            <span class="text-rose-500 font-black text-xs">!</span>
                                        </div>
                                        <span class="text-[9px] font-black uppercase text-rose-500 tracking-widest">{{ $label }}</span>
                                    @endif
                                </div>

                                @if (!empty($application->$path))
                                    @php
                                        $fileUrl = \Storage::disk('public')->url($application->$path);
                                        $isImage = preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $application->$path);
                                    @endphp
                                    <a href="{{ $fileUrl }}" target="_blank" class="group/asset block relative overflow-hidden rounded-[32px] border border-white/10 bg-white/[0.03] shadow-xl">
                                        @if($isImage)
                                            <img src="{{ $fileUrl }}" class="w-full h-44 object-cover transition-transform duration-700 group-hover/asset:scale-110">
                                            <div class="absolute inset-0 bg-cyan-500/20 opacity-0 group-hover/asset:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                                                <svg class="w-8 h-8 text-white scale-75 group-hover/asset:scale-100 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                            </div>
                                        @else
                                            <div class="w-full h-44 flex flex-col items-center justify-center text-cyan-400/40 group-hover/asset:text-cyan-400 transition-colors">
                                                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                <span class="text-[8px] font-black uppercase tracking-[0.4em]">VIEW PDF</span>
                                            </div>
                                        @endif
                                    </a>
                                @else
                                    <div class="w-full h-44 rounded-[32px] bg-rose-500/5 border-2 border-dashed border-rose-500/10 flex flex-col items-center justify-center opacity-40">
                                        <span class="text-[8px] font-black text-rose-500 tracking-[0.4em] italic uppercase">Missing</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Promissory Note Review --}}
                @if($application->promissory_note_path || $application->promissory_reason)
                <div class="space-y-8 pt-10 border-t border-white/5">
                    <div class="flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Promissory Note & Reasoning</h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 bg-amber-500/5 border border-amber-500/10 rounded-[40px] p-10">
                        <div class="lg:col-span-1 space-y-4">
                            <span class="text-[9px] font-black text-amber-500/40 uppercase tracking-widest italic">Note Attachment</span>
                            @if($application->promissory_note_path)
                                @php
                                    $noteUrl = \Storage::disk('public')->url($application->promissory_note_path);
                                    $isPdf = Str::endsWith($application->promissory_note_path, '.pdf');
                                @endphp
                                <a href="{{ $noteUrl }}" target="_blank" class="group/note block p-6 rounded-3xl border border-amber-500/20 bg-amber-500/5 hover:bg-amber-500/10 transition-all">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-2xl bg-amber-500/20 flex items-center justify-center text-amber-400">
                                            @if($isPdf)
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            @else
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-white uppercase tracking-wider">Download Note</p>
                                            <p class="text-[8px] text-amber-500/60 uppercase font-bold mt-0.5">{{ $isPdf ? 'PDF Format' : 'Word Document' }}</p>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <div class="p-6 rounded-3xl border border-dashed border-white/5 bg-white/[0.02] flex items-center justify-center">
                                    <span class="text-[8px] font-black text-white/20 uppercase tracking-widest italic">No File Attached</span>
                                </div>
                            @endif
                        </div>

                        <div class="lg:col-span-2 space-y-4">
                            <span class="text-[9px] font-black text-amber-500/40 uppercase tracking-widest italic">Student's Explanation</span>
                            <div class="p-8 rounded-3xl bg-white/[0.03] border border-white/5 min-h-[100px]">
                                <p class="text-xs text-white/70 leading-relaxed italic">
                                    {{ $application->promissory_reason ?? 'No explanation provided.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="flex md:hidden flex-col gap-4 pt-10">
                    @if (in_array(strtolower($application->status), ['pending', 'enrolled', 'paid']))
                        <form action="{{ route('registrar.applications.update', $application->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="Approved">
                            <button type="submit" class="w-full bg-emerald-500 text-white text-[10px] font-black py-5 px-10 rounded-2xl uppercase tracking-[0.2em]">
                                Approve
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.registrar>
