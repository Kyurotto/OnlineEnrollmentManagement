{{-- Dashboard Skeleton Loading Screen —— Pure CSS/JS, no logic changes --}}
<div id="skeleton-loader" class="fixed inset-0 z-[9999] flex" style="background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%);">

    {{-- Skeleton Sidebar --}}
    <div class="hidden sm:flex flex-col flex-shrink-0 w-64 h-screen border-r" style="background: #ffffff; border-color: rgba(0,0,0,0.06);">
        {{-- Sidebar Toggle Area --}}
        <div class="h-20 flex items-center px-6 flex-shrink-0" style="border-bottom: 1px solid rgba(0,0,0,0.06);">
            <div class="w-6 h-6 rounded-lg skeleton-bone"></div>
        </div>
        {{-- Sidebar Branding --}}
        <div class="flex items-center gap-3 px-6 h-20 flex-shrink-0" style="border-bottom: 1px solid rgba(0,0,0,0.06);">
            <div class="w-10 h-10 rounded-xl skeleton-bone flex-shrink-0"></div>
            <div class="space-y-2 flex-1">
                <div class="h-3 rounded-full skeleton-bone w-24"></div>
                <div class="h-2 rounded-full skeleton-bone w-16"></div>
            </div>
        </div>
        {{-- Sidebar Nav Items --}}
        <div class="py-6 px-4 space-y-2 flex-1">
            <div class="h-2 rounded-full skeleton-bone w-20 mb-4 mx-2"></div>
            @for($i = 0; $i < 3; $i++)
            <div class="flex items-center gap-3 px-3 py-3">
                <div class="w-5 h-5 rounded-lg skeleton-bone flex-shrink-0"></div>
                <div class="h-3 rounded-full skeleton-bone w-24"></div>
            </div>
            @endfor
            <div class="my-4 mx-2 border-t" style="border-color: rgba(0,0,0,0.04);"></div>
            <div class="h-2 rounded-full skeleton-bone w-16 mb-4 mx-2"></div>
            @for($i = 0; $i < 4; $i++)
            <div class="flex items-center gap-3 px-3 py-3">
                <div class="w-5 h-5 rounded-lg skeleton-bone flex-shrink-0"></div>
                <div class="h-3 rounded-full skeleton-bone" style="width: {{ rand(60, 100) }}px;"></div>
            </div>
            @endfor
        </div>
    </div>

    {{-- Skeleton Content Area --}}
    <div class="flex flex-col flex-1 min-w-0">

        {{-- Skeleton Navbar --}}
        <div class="h-20 flex items-center px-8 border-b flex-shrink-0" style="background: rgba(255,255,255,0.8); border-color: rgba(0,0,0,0.06);">
            <div class="w-6 h-6 rounded-lg skeleton-bone sm:hidden"></div>
            <div class="ml-auto flex items-center gap-3">
                <div class="hidden sm:flex flex-col items-end gap-1.5">
                    <div class="h-2 rounded-full skeleton-bone w-16"></div>
                    <div class="h-3 rounded-full skeleton-bone w-28"></div>
                </div>
                <div class="w-4 h-4 rounded skeleton-bone"></div>
            </div>
        </div>

        {{-- Skeleton Main Content --}}
        <div class="flex-1 px-8 py-10 overflow-hidden">
            <div class="space-y-8 max-w-full">

                {{-- Header Card Skeleton --}}
                <div class="p-10 rounded-[2rem] border bg-white shadow-sm" style="border-color: rgba(37,99,235,0.08);">
                    <div class="flex items-center gap-8">
                        <div class="w-20 h-20 rounded-2xl skeleton-bone flex-shrink-0"></div>
                        <div class="space-y-3 flex-1">
                            <div class="h-6 rounded-full skeleton-bone w-72 max-w-full"></div>
                            <div class="h-3 rounded-full skeleton-bone w-48 max-w-full"></div>
                        </div>
                        <div class="hidden sm:flex items-center gap-3">
                            <div class="h-8 rounded-full skeleton-bone w-24"></div>
                            <div class="h-8 rounded-full skeleton-bone w-20"></div>
                        </div>
                    </div>
                </div>

                {{-- Info Alert Skeleton --}}
                <div class="p-8 rounded-[1.5rem] border bg-white shadow-sm flex items-center gap-6" style="border-color: rgba(37,99,235,0.08);">
                    <div class="w-16 h-16 rounded-2xl skeleton-bone flex-shrink-0"></div>
                    <div class="space-y-3 flex-1">
                        <div class="h-4 rounded-full skeleton-bone w-56 max-w-full"></div>
                        <div class="h-3 rounded-full skeleton-bone w-80 max-w-full"></div>
                    </div>
                    <div class="hidden sm:block h-12 w-32 rounded-xl skeleton-bone flex-shrink-0"></div>
                </div>

                {{-- Action Cards Grid Skeleton --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @for($i = 0; $i < 4; $i++)
                    <div class="p-8 rounded-[2rem] border bg-white shadow-sm" style="border-color: rgba(37,99,235,0.08);">
                        <div class="flex justify-between items-start mb-6">
                            <div class="space-y-3 flex-1">
                                <div class="h-2 rounded-full skeleton-bone w-16"></div>
                                <div class="h-5 rounded-full skeleton-bone w-28"></div>
                            </div>
                            <div class="w-12 h-12 rounded-2xl skeleton-bone flex-shrink-0"></div>
                        </div>
                        <div class="space-y-2">
                            <div class="h-2.5 rounded-full skeleton-bone w-full"></div>
                            <div class="h-2.5 rounded-full skeleton-bone w-3/4"></div>
                        </div>
                    </div>
                    @endfor
                </div>

                {{-- Large Content Card Skeleton --}}
                <div class="p-10 rounded-[2rem] border bg-white shadow-sm" style="border-color: rgba(37,99,235,0.08);">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-2 h-8 rounded-full skeleton-bone"></div>
                        <div class="space-y-2">
                            <div class="h-5 rounded-full skeleton-bone w-48"></div>
                            <div class="h-2 rounded-full skeleton-bone w-32"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        @for($i = 0; $i < 2; $i++)
                        <div class="p-8 rounded-2xl border" style="border-color: rgba(37,99,235,0.06); background: rgba(37,99,235,0.015);">
                            <div class="h-3 rounded-full skeleton-bone w-36 mb-6"></div>
                            <div class="space-y-4">
                                @for($j = 0; $j < 4; $j++)
                                <div class="flex items-center gap-3 p-2 rounded-lg bg-white border" style="border-color: rgba(37,99,235,0.04);">
                                    <div class="w-6 h-6 rounded skeleton-bone flex-shrink-0"></div>
                                    <div class="h-2.5 rounded-full skeleton-bone" style="width: {{ rand(80, 160) }}px;"></div>
                                </div>
                                @endfor
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    /* Skeleton Shimmer Animation */
    .skeleton-bone {
        background: linear-gradient(90deg, #e8eef6 25%, #dfe7f2 37%, #e8eef6 63%);
        background-size: 400% 100%;
        animation: skeleton-shimmer 1.4s ease infinite;
        border-radius: 8px;
    }

    @keyframes skeleton-shimmer {
        0% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Skeleton Fade-out */
    #skeleton-loader {
        transition: opacity 0.5s ease, visibility 0.5s ease;
    }
    #skeleton-loader.skeleton-hidden {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }
</style>

<script>
    // Hide skeleton once the page has fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Small delay so the actual content can render first
        setTimeout(function() {
            var skeleton = document.getElementById('skeleton-loader');
            if (skeleton) {
                skeleton.classList.add('skeleton-hidden');
                // Remove from DOM after fade-out animation completes
                setTimeout(function() {
                    if (skeleton.parentNode) {
                        skeleton.parentNode.removeChild(skeleton);
                    }
                }, 600);
            }
        }, 300);
    });
</script>
