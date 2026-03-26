<x-layouts.registrar title="Update Student Record">
    <div class="max-w-4xl mx-auto animate-in fade-in slide-in-from-bottom-8 duration-700">
        <div class="mb-10 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('registrar.students.index') }}" class="group/back flex items-center justify-center w-12 h-12 rounded-2xl bg-white/5 border border-white/10 text-white/40 hover:text-purple-400 hover:border-purple-500/30 transition-all active:scale-95 shadow-xl">
                    <svg class="w-5 h-5 group-hover/back:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h2 class="text-3xl font-black text-white tracking-tight uppercase italic">Profile Update</h2>
                    <p class="text-white/30 text-[10px] font-black uppercase tracking-[0.3em] mt-1 italic">Updating Verified Academic Persona Record</p>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-[40px] border-white/5 shadow-2xl overflow-hidden relative group">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-purple-500/10 blur-[100px] rounded-full group-hover:bg-purple-500/15 transition-colors duration-1000"></div>
            
            <form action="{{ route('registrar.students.update', $student->id) }}" method="POST" class="p-10 md:p-14 relative z-10 space-y-10">
                @csrf 
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-white/40 uppercase tracking-[0.2em] ml-2 italic">Given Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" 
                            class="w-full bg-white/[0.03] text-white border border-white/10 py-5 px-6 rounded-2xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-purple-500/50 focus:bg-white/[0.05] transition-all shadow-inner uppercase">
                        @error('first_name') <span class="text-rose-500 text-[10px] font-black uppercase tracking-tighter ml-2">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-white/40 uppercase tracking-[0.2em] ml-2 italic">Middle Accessor</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name', $student->middle_name) }}" 
                            class="w-full bg-white/[0.03] text-white border border-white/10 py-5 px-6 rounded-2xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-purple-500/50 focus:bg-white/[0.05] transition-all shadow-inner uppercase">
                        @error('middle_name') <span class="text-rose-500 text-[10px] font-black uppercase tracking-tighter ml-2">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-white/40 uppercase tracking-[0.2em] ml-2 italic">Ancestral Header</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" 
                            class="w-full bg-white/[0.03] text-white border border-white/10 py-5 px-6 rounded-2xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-purple-500/50 focus:bg-white/[0.05] transition-all shadow-inner uppercase">
                        @error('last_name') <span class="text-rose-500 text-[10px] font-black uppercase tracking-tighter ml-2">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-white/40 uppercase tracking-[0.2em] ml-2 italic">Email</label>
                        <input type="email" name="email" value="{{ old('email', $student->email) }}" 
                            class="w-full bg-white/[0.03] text-white border border-white/10 py-5 px-6 rounded-2xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-purple-500/50 focus:bg-white/[0.05] transition-all shadow-inner">
                        @error('email') <span class="text-rose-500 text-[10px] font-black uppercase tracking-tighter ml-2">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-white/40 uppercase tracking-[0.2em] ml-2 italic">Operational Status</label>
                        <div class="relative">
                            <select name="status" class="w-full bg-white/[0.03] bg-none text-white border border-white/10 py-5 px-6 rounded-2xl outline-none text-sm font-bold tracking-wider focus:border-purple-500/50 hover:bg-white/[0.05] appearance-none transition-all shadow-inner">
                                <option value="Not Enrolled" {{ $student->status == 'Not Enrolled' ? 'selected' : '' }} class="bg-[#0d1f3c]">Not Enrolled</option>
                                <option value="Enrolled" {{ $student->status == 'Enrolled' ? 'selected' : '' }} class="bg-[#0d1f3c]">Enrolled</option>
                                <option value="Active" {{ $student->status == 'Active' ? 'selected' : '' }} class="bg-[#0d1f3c]">Active / Validated</option>
                            </select>
                            <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-white/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        @error('status') <span class="text-rose-500 text-[10px] font-black uppercase tracking-tighter ml-2">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-6 pt-10">
                    <a href="{{ route('registrar.students.index') }}" 
                        class="flex-1 px-10 py-5 text-[10px] font-black text-white/40 uppercase tracking-[0.3em] border border-white/10 rounded-2xl hover:bg-white/5 hover:text-white text-center transition-all bg-white/[0.01]">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="flex-[2] bg-purple-500 hover:bg-purple-400 text-white text-[10px] font-black py-5 px-10 rounded-2xl uppercase tracking-[0.3em] transition-all shadow-[0_20px_50px_rgba(167,139,250,0.3)] active:scale-[0.98] italic">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.registrar>