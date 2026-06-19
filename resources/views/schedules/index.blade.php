<x-dynamic-component :component="auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.registrar'" title="Course Schedules">
    <div class="py-12 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-teal-50 border-l-4 border-teal-500 p-4 mb-6 rounded-r-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-teal-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-teal-700 font-medium">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm text-red-800 font-bold mb-1">There were errors with your submission:</h3>
                            <ul class="text-sm text-red-700 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Header Section -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-800">Class Schedules</h3>
                    <p class="text-sm text-slate-500 mt-1">Manage and view all course timings and room assignments.</p>
                </div>
                @if(auth()->user()->role !== 'admin')
                <button onclick="document.getElementById('add-schedule-modal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg" style="color: #ffffff !important;">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #ffffff !important;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add Schedule
                </button>
                @endif
            </div>

            <!-- Content Area -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-slate-100">
                @if($schedules->isEmpty())
                    <div class="p-12 text-center flex flex-col items-center justify-center">
                        <div class="w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mb-4 border-8 border-white shadow-sm shadow-indigo-100">
                            <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-1">No schedules configured</h3>
                        <p class="text-slate-500 mb-6 max-w-sm text-sm">Get started by creating a new schedule to assign sections to rooms and time slots.</p>
                        @if(auth()->user()->role !== 'admin')
                        <button onclick="document.getElementById('add-schedule-modal').classList.remove('hidden')" class="text-indigo-600 font-semibold text-sm hover:text-indigo-800 transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Create the first schedule
                        </button>
                        @endif
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Student</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Day</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Time</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Room</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Instructor</th>
                                    @if(auth()->user()->role !== 'admin')
                                    <th scope="col" class="relative px-6 py-4"><span class="sr-only">Actions</span></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100">
                                @foreach($schedules as $schedule)
                                    <tr class="hover:bg-slate-50 transition-colors duration-200 group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-slate-900">{{ $schedule->enrollment->user->first_name ?? '' }} {{ $schedule->enrollment->user->last_name ?? 'Enrollment ID: ' . $schedule->enrollment_id }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                {{ $schedule->day_of_week }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-slate-700 font-medium">
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - 
                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-slate-500 flex items-center font-medium">
                                                <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                {{ $schedule->room ?? 'TBA' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                            {{ $schedule->employee->first_name ?? '' }} {{ $schedule->employee->last_name ?? 'TBA' }}
                                        </td>
                                        @if(auth()->user()->role !== 'admin')
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <!-- Edit Button -->
                                                <button onclick="openEditModal({{ $schedule->id }}, '{{ $schedule->day_of_week }}', '{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}', '{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}', '{{ addslashes($schedule->room) }}', '{{ $schedule->employee_id }}')" class="text-indigo-500 hover:text-indigo-700 transition-colors p-2 rounded-full hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </button>
                                                <!-- Delete Form -->
                                                <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 transition-colors p-2 rounded-full hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Schedule Modal -->
    <div id="add-schedule-modal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <!-- Background backdrop -->
            <div class="fixed inset-0 bg-slate-900 bg-opacity-40 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('add-schedule-modal').classList.add('hidden')"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <form action="{{ route('schedules.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start mb-4">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-50 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-bold text-slate-900" id="modal-title">
                                    Add New Schedule
                                </h3>
                                <p class="text-sm text-slate-500 mt-1">Fill in the details to assign a section to a time and room.</p>
                            </div>
                        </div>

                        <div class="space-y-5 mt-6">
                            <div>
                                <label for="enrollment_id" class="block text-sm font-semibold text-slate-700 mb-1">Select Student Enrollment</label>
                                <select name="enrollment_id" id="enrollment_id" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 rounded-lg py-2.5 px-3 border transition-colors" required>
                                    <option value="" disabled selected>-- Select an enrolled student --</option>
                                    @foreach($enrollments as $enrollment)
                                        <option value="{{ $enrollment->id }}">
                                            {{ $enrollment->user->first_name ?? '' }} {{ $enrollment->user->last_name ?? '' }} ({{ $enrollment->getLevel() === 'shs' ? 'SHS' : 'College' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div id="schedule-rows-container" class="space-y-4">
                                <!-- First Row -->
                                <div class="schedule-row bg-slate-50 p-4 rounded-xl border border-slate-100 relative">
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Day of Week</label>
                                            <select name="schedules[0][day_of_week]" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 rounded-lg py-2 px-3 border" required>
                                                <option value="Monday">Monday</option>
                                                <option value="Tuesday">Tuesday</option>
                                                <option value="Wednesday">Wednesday</option>
                                                <option value="Thursday">Thursday</option>
                                                <option value="Friday">Friday</option>
                                                <option value="Saturday">Saturday</option>
                                                <option value="Sunday">Sunday</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Room</label>
                                            <input type="text" name="schedules[0][room]" placeholder="e.g. Room 101" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 rounded-lg py-2 px-3 border">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Start Time</label>
                                            <input type="time" name="schedules[0][start_time]" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 rounded-lg py-2 px-3 border" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">End Time</label>
                                            <input type="time" name="schedules[0][end_time]" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 rounded-lg py-2 px-3 border" required>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Instructor (Optional)</label>
                                        <select name="schedules[0][employee_id]" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 rounded-lg py-2 px-3 border">
                                            <option value="">-- TBA / No Instructor Yet --</option>
                                            @foreach($instructors as $instructor)
                                                <option value="{{ $instructor->id }}">{{ $instructor->first_name }} {{ $instructor->last_name }} ({{ $instructor->role }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" class="remove-row-btn hidden absolute -right-2 -top-2 bg-red-100 text-red-600 rounded-full p-1.5 hover:bg-red-200 transition-colors shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </div>
                            
                            <button type="button" id="add-row-btn" class="mt-3 inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Add Another Subject
                            </button>
                            
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const container = document.getElementById('schedule-rows-container');
                                    const addBtn = document.getElementById('add-row-btn');
                                    let rowCount = 1;

                                    function updateRemoveButtons() {
                                        const rows = container.querySelectorAll('.schedule-row');
                                        rows.forEach((row, index) => {
                                            const btn = row.querySelector('.remove-row-btn');
                                            if (rows.length > 1) {
                                                btn.classList.remove('hidden');
                                            } else {
                                                btn.classList.add('hidden');
                                            }
                                        });
                                    }

                                    addBtn.addEventListener('click', function() {
                                        const firstRow = container.querySelector('.schedule-row');
                                        const newRow = firstRow.cloneNode(true);
                                        
                                        // Update names
                                        newRow.innerHTML = newRow.innerHTML.replace(/schedules\[0\]/g, `schedules[${rowCount}]`);
                                        
                                        // Clear values
                                        const inputs = newRow.querySelectorAll('input');
                                        inputs.forEach(input => input.value = '');
                                        
                                        // Setup remove button
                                        const removeBtn = newRow.querySelector('.remove-row-btn');
                                        removeBtn.addEventListener('click', function() {
                                            newRow.remove();
                                            updateRemoveButtons();
                                        });

                                        container.appendChild(newRow);
                                        rowCount++;
                                        updateRemoveButtons();
                                    });

                                    // Setup initial remove button
                                    const initialRemoveBtn = container.querySelector('.remove-row-btn');
                                    initialRemoveBtn.addEventListener('click', function() {
                                        initialRemoveBtn.closest('.schedule-row').remove();
                                        updateRemoveButtons();
                                    });
                                });
                            </script>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-slate-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-5 py-2 bg-indigo-600 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto transition-colors" style="color: #ffffff !important;">
                            Save Schedule
                        </button>>
                        <button type="button" onclick="document.getElementById('add-schedule-modal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-5 py-2 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Schedule Modal -->
    <div id="edit-schedule-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-40 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="closeEditModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <form id="edit-schedule-form" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start mb-4">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-50 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-bold text-slate-900">Edit Schedule</h3>
                                <p class="text-sm text-slate-500 mt-1">Update the timing, room, and instructor.</p>
                            </div>
                        </div>
                        <div class="space-y-4 mt-6 bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Day of Week</label>
                                    <select id="edit_day_of_week" name="day_of_week" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 rounded-lg py-2 px-3 border" required>
                                        <option value="Monday">Monday</option>
                                        <option value="Tuesday">Tuesday</option>
                                        <option value="Wednesday">Wednesday</option>
                                        <option value="Thursday">Thursday</option>
                                        <option value="Friday">Friday</option>
                                        <option value="Saturday">Saturday</option>
                                        <option value="Sunday">Sunday</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Room</label>
                                    <input type="text" id="edit_room" name="room" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 rounded-lg py-2 px-3 border">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Start Time</label>
                                    <input type="time" id="edit_start_time" name="start_time" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 rounded-lg py-2 px-3 border" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">End Time</label>
                                    <input type="time" id="edit_end_time" name="end_time" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 rounded-lg py-2 px-3 border" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Instructor (Optional)</label>
                                <select id="edit_employee_id" name="employee_id" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 rounded-lg py-2 px-3 border">
                                    <option value="">-- TBA / No Instructor Yet --</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->id }}">{{ $instructor->first_name }} {{ $instructor->last_name }} ({{ $instructor->role }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-slate-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-5 py-2 bg-indigo-600 text-sm font-semibold text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto transition-colors" style="color: #ffffff !important;">Save Changes</button>
                        <button type="button" onclick="closeEditModal()" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-5 py-2 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-50 sm:mt-0 sm:ml-3 sm:w-auto transition-colors">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, day, start, end, room, employee) {
            document.getElementById('edit-schedule-form').action = `/schedules/${id}`;
            document.getElementById('edit_day_of_week').value = day;
            document.getElementById('edit_start_time').value = start;
            document.getElementById('edit_end_time').value = end;
            document.getElementById('edit_room').value = room || '';
            document.getElementById('edit_employee_id').value = employee || '';
            document.getElementById('edit-schedule-modal').classList.remove('hidden');
        }
        function closeEditModal() {
            document.getElementById('edit-schedule-modal').classList.add('hidden');
        }
    </script>
</x-dynamic-component>
