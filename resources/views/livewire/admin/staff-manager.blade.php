<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manage System Staff</h1>
            <p class="text-sm text-gray-500">Register new employees securely into the system.</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded relative mb-6 shadow-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-sm border border-gray-200 h-fit">
            <h2 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Employee Details</h2>

            <form wire:submit.prevent="saveStaff" class="space-y-4">

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">First Name</label>
                        <input type="text" wire:model="first_name" class="w-full border border-gray-300 rounded p-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                        @error('first_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Middle Name</label>
                        <input type="text" wire:model="middle_name" class="w-full border border-gray-300 rounded p-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Last Name</label>
                    <input type="text" wire:model="last_name" class="w-full border border-gray-300 rounded p-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                    @error('last_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">System Username</label>
                    <input type="text" wire:model="username" class="w-full border border-gray-300 rounded p-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                    @error('username') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Email</label>
                        <input type="email" wire:model="email" class="w-full border border-gray-300 rounded p-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                        @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Phone</label>
                        <input type="text" wire:model="phone" class="w-full border border-gray-300 rounded p-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Address</label>
                    <input type="text" wire:model="address" class="w-full border border-gray-300 rounded p-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Account Role</label>
                        <select wire:model="role" class="w-full border border-gray-300 rounded p-2 text-sm outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">Select Role...</option>
                            <option value="admin">Administrator</option>
                            <option value="registrar">Registrar</option>
                            <option value="cashier">Cashier</option>
                        </select>
                        @error('role') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Password</label>
                        <input type="password" wire:model="password" class="w-full border border-gray-300 rounded p-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                        @error('password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 rounded shadow transition">
                        Register Employee
                    </button>
                </div>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="font-bold text-slate-800">Current System Staff</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Name</th>
                            <th class="px-6 py-3 font-semibold">Username</th>
                            <th class="px-6 py-3 font-semibold text-center">Role</th>
                            <th class="px-6 py-3 font-semibold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($staffList as $staff)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $staff->name }}
                                <div class="text-xs text-gray-400 font-normal">{{ $staff->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-500 font-mono text-xs">{{ $staff->username }}</td>
                            <td class="px-6 py-4 text-center">
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm
                                                {{ $staff->role === 'admin' ? 'bg-purple-500/10 text-purple-400 border-purple-500/20' : 
                                                   ($staff->role === 'registrar' ? 'bg-blue-500/10 text-blue-400 border-blue-500/20' : 
                                                    'bg-emerald-500/10 text-emerald-400 border-emerald-500/20') }}">
                                    {{ $staff->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-green-50 text-green-600 px-2 py-1 rounded text-xs font-semibold">{{ $staff->status ?? 'Active' }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100">
                {{ $staffList->links('livewire.glass-pagination') }}
            </div>
        </div>
    </div>
</div>
