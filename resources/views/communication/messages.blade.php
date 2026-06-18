<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Messages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Inbox</h3>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($messages->isEmpty())
                        <p>No messages found.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($messages as $message)
                                <div class="border p-4 rounded shadow-sm">
                                    <h4 class="font-bold text-lg">{{ $message->subject ?? 'No Subject' }}</h4>
                                    <p class="text-sm text-gray-500 mb-2">
                                        From: {{ $message->sender->name ?? 'Unknown' }} | 
                                        To: {{ $message->receiver->name ?? 'Unknown' }}
                                    </p>
                                    <p>{{ $message->body }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
