<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Announcements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Announcements Board</h3>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($announcements->isEmpty())
                        <p>No announcements yet.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($announcements as $announcement)
                                <div class="border p-4 rounded shadow-sm">
                                    <h4 class="font-bold text-lg">{{ $announcement->title }}</h4>
                                    <p class="text-sm text-gray-500 mb-2">By {{ $announcement->author->name ?? 'Unknown' }} | For: {{ $announcement->target_role }}</p>
                                    <p>{{ $announcement->content }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
