<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Enrollment Application') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($hasApproved)
                <div class="bg-green-50 p-8 text-center">
                    <h3 class="text-xl font-bold">Application Approved</h3>
                    <p>You are already enrolled.</p>
                </div>
            @else
                <form method="POST" action="{{ route('enroll.store') }}" enctype="multipart/form-data">
                    @csrf <div class="bg-white shadow rounded-lg p-6 mb-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Course Selection</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach ($courses as $c)
                            <label class="flex items-center space-x-2 p-2 border rounded cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="course_id" value="{{ $c->id }}" class="text-sky-600">
                                <span>
                                    <strong>{{ $c->course_code }}</strong> — {{ $c->course_name }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
                    </div>

                    <div class="bg-white shadow rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium">First Name</label>
                                <input type="text" name="student_first_name" value="{{ auth()->user()->first_name }}" readonly class="w-full border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Payment Amount</label>
                                <input type="number" name="amount" class="w-full border-gray-300 rounded-md">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="bg-sky-600 text-white px-4 py-2 rounded-md hover:bg-sky-700">
                        Submit Application
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
