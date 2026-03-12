<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application #{{ $application->id }} - Details</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .modal-backdrop { background-color: rgba(255, 255, 255, 0.1); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #E5E7EB; border-radius: 4px; }
    </style>
</head>

<body class="bg-gray-50 text-gray-600 h-screen overflow-hidden flex flex-col">

    <nav class="bg-white border-b border-gray-200 z-10 shrink-0 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-4">
                    <div class="bg-[#10B981] text-white font-bold p-2 rounded-lg text-sm shadow-md shadow-[#10B981]/20">APP</div>
                    <div>
                        <h1 class="text-lg font-bold leading-none text-gray-900">Application Viewer</h1>
                        <span class="text-xs text-gray-500">Manage Applications</span>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2 px-4 rounded shadow transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div class="bg-white w-full max-w-4xl rounded-xl shadow-2xl border border-gray-200 overflow-hidden flex flex-col max-h-[90vh]">

            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white shrink-0">
                <h2 class="text-xl font-bold text-gray-900">Application #{{ $application->id }}</h2>
                <a href="{{ url()->previous() }}" class="text-gray-400 hover:text-gray-900 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>
            </div>

            <div class="p-6 overflow-y-auto custom-scrollbar bg-white">
                <div class="space-y-8">

                    <div>
                        <h3 class="text-xs font-bold text-[#10B981] uppercase tracking-wider mb-3">Student Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 text-sm border-t border-gray-100 pt-4">
                            <div><span class="block text-gray-400 text-xs mb-1">Full Name:</span><span class="font-bold text-gray-900 uppercase">{{ $application->user?->last_name ?? 'N/A' }}, {{ $application->user?->first_name ?? 'N/A' }} {{ $application->user?->middle_name ?? '' }}</span></div>
                            <div><span class="block text-gray-400 text-xs mb-1">Email:</span><span class="font-medium text-gray-700">{{ $application->user?->email ?? 'N/A' }}</span></div>
                            <div><span class="block text-gray-400 text-xs mb-1">Date of Birth:</span><span class="font-medium text-gray-700">{{ $application->user?->birth_date ?? 'N/A' }}</span></div>
                            <div><span class="block text-gray-400 text-xs mb-1">Age:</span><span class="font-medium text-gray-700">{{ $application->user?->age ?? 'N/A' }}</span></div>
                        </div>
                    </div>

                    <div class="bg-gray-50 border border-gray-100 rounded-lg p-5">
                        <h3 class="text-xs font-bold text-[#10B981] uppercase tracking-wider mb-4">Program Details</h3>
                        <div class="space-y-4 text-sm">
                            <p><span class="font-bold text-[#A1A1AA] mr-2">Program:</span><span class="text-[#10B981] font-bold uppercase">{{ $application->course?->course_code ?? 'N/A' }} - {{ $application->course?->course_description ?? '' }}</span></p>
                            <div class="flex gap-4">
                                <span><span class="font-bold text-gray-500">Year:</span> <span class="text-gray-700 font-medium">{{ $application->year_level }}</span></span>
                                <span><span class="font-bold text-gray-500">Status:</span> <span class="text-[#10B981] font-bold uppercase">{{ $application->status }}</span></span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-bold text-[#10B981] uppercase tracking-wider mb-3 text-center md:text-left">Submitted Documents</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 border-t border-gray-100 pt-4">
                            @php 
                                $docs = [
                                    'form_138_path' => 'Form 138', 
                                    'good_moral_path' => 'Good Moral', 
                                    'psa_path' => 'PSA Birth Cert', 
                                    'id_picture_path' => 'ID Picture'
                                ]; 
                            @endphp
                            
                            @foreach ($docs as $path => $label)
                                <div>
                                    @if (!empty($application->$path))
                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                            <div style="display: flex; align-items: center; justify-content: center; width: 22px; height: 22px; background-color: rgba(16, 185, 129, 0.2); border: 2px solid #10B981; border-radius: 50%; flex-shrink: 0;">
                                                <span style="color: #10B981; font-weight: 900; font-size: 14px; line-height: 1;">✓</span>
                                            </div>
                                            <span style="font-size: 11px; font-weight: bold; text-transform: uppercase; color: #111827;">{{ $label }}</span>
                                        </div>

                                        @php
                                            $fileUrl = asset('storage/' . $application->$path);
                                            $isImage = preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $application->$path);
                                        @endphp
                                        
                                        <a href="{{ $fileUrl }}" target="_blank" style="display: block;">
                                            @if($isImage)
                                                <img src="{{ $fileUrl }}" style="width: 100%; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #E5E7EB; background-color: #F9FAFB;">
                                            @else
                                                <div style="width: 100%; height: 100px; border-radius: 8px; border: 1px solid #E5E7EB; background-color: #F9FAFB; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #10B981; transition: 0.2s;">
                                                    <span style="font-size: 24px;">📄</span>
                                                    <span style="font-size: 10px; font-weight: bold; text-transform: uppercase; margin-top: 4px;">PDF</span>
                                                </div>
                                            @endif
                                        </a>

                                    @else
                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                            <div style="display: flex; align-items: center; justify-content: center; width: 22px; height: 22px; background-color: rgba(244, 63, 94, 0.2); border: 2px solid #f43f5e; border-radius: 50%; flex-shrink: 0;">
                                                <span style="color: #f43f5e; font-weight: 900; font-size: 14px; line-height: 1;">✗</span>
                                            </div>
                                            <span style="font-size: 11px; font-weight: bold; text-transform: uppercase; color: #f43f5e;">{{ $label }}</span>
                                        </div>

                                        <div style="width: 100%; height: 100px; border-radius: 8px; background-color: #FFF1F2; border: 1px dashed rgba(225, 29, 72, 0.4); display: flex; flex-direction: column; align-items: center; justify-content: center; opacity: 0.7;">
                                            <span style="font-size: 20px; color: rgba(244, 63, 94, 0.6);">⚠️</span>
                                            <span style="font-size: 10px; font-weight: bold; text-transform: uppercase; color: rgba(244, 63, 94, 0.6); margin-top: 4px;">Missing</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

            <div class="bg-gray-50 px-6 py-5 border-t border-gray-100 flex justify-end gap-3 shrink-0">
                <a href="{{ url()->previous() }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg text-sm font-semibold transition">Close</a>
                
                @if ($application->status === 'Pending')
                    <form action="{{ route('registrar.applications.update', $application->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Approved">
                        <button type="submit" class="bg-[#10B981] hover:bg-[#059669] text-white px-6 py-2 rounded-lg text-sm font-bold shadow-md shadow-[#10B981]/20">Approve Application</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

</body>
</html>