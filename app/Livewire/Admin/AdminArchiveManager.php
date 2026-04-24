<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class AdminArchiveManager extends Component
{
    use WithPagination;

    public $search = '';
    public $level = '';
    public $selectedCourse = '';
    public $selectedFolder = null; // Format: "semester_name|academic_year_name"

    protected $listeners = ['refresh-archives' => '$refresh'];

    public function selectFolder($folder)
    {
        $this->selectedFolder = $folder;
        $this->resetPage();
    }

    public function backToFolders()
    {
        $this->selectedFolder = null;
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'level', 'selectedCourse', 'selectedFolder']);
        $this->resetPage();
    }

    public function render()
    {
        // Get all archived folder groupings (semester + academic year combinations)
        $folders = Enrollment::whereNotNull('archived_at')
            ->whereNotNull('semester_name')
            ->whereNotNull('academic_year_name')
            ->select('semester_name', 'academic_year_name')
            ->selectRaw('COUNT(*) as student_count')
            ->groupBy('semester_name', 'academic_year_name')
            ->orderBy('academic_year_name', 'desc')
            ->orderBy('semester_name', 'desc')
            ->get();

        // Also get legacy archives (archived_at is null but not matching current term)
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();

        $legacyEnrollments = collect();
        if ($activeYear && $activeSemester) {
            $legacyEnrollments = Enrollment::whereNull('archived_at')
                ->where(function($q) use ($activeYear, $activeSemester) {
                    // Match any enrollment NOT in the current active term (case-insensitive)
                    $q->where('enrollments.year_level', 'NOT LIKE', "%{$activeYear->year_name}%")
                      ->orWhere('enrollments.year_level', 'NOT LIKE', "%{$activeSemester->name}%");
                })
                ->get();
        }

        // Categorize legacy enrollments into folders dynamically
        $legacyFolders = collect();
        foreach ($legacyEnrollments as $enrollment) {
            $parts = array_map('trim', explode('|', $enrollment->year_level));
            if (count($parts) >= 3) {
                $semesterName = $parts[1];
                $academicYearName = $parts[2];
            } else {
                $semesterName = 'Unknown Semester';
                $academicYearName = 'Unknown Year';
            }

            $key = $semesterName . '|' . $academicYearName;
            if (!$legacyFolders->has($key)) {
                $legacyFolders->put($key, (object)[
                    'semester_name' => $semesterName,
                    'academic_year_name' => $academicYearName,
                    'student_count' => 0
                ]);
            }
            $legacyFolders[$key]->student_count++;
        }

        // Merge legacy folders into properly archived folders
        foreach ($legacyFolders as $key => $legacyFolder) {
            $existingFolder = $folders->first(function ($folder) use ($legacyFolder) {
                return $folder->semester_name === $legacyFolder->semester_name && 
                       $folder->academic_year_name === $legacyFolder->academic_year_name;
            });

            if ($existingFolder) {
                $existingFolder->student_count += $legacyFolder->student_count;
            } else {
                $folders->push($legacyFolder);
            }
        }

        // Sort folders descending
        $folders = $folders->sortByDesc(function ($folder) {
            return $folder->academic_year_name . '-' . $folder->semester_name;
        })->values();

        // No need for a separate legacyCount anymore, as they are fully categorized!
        $legacyCount = 0;

        $applications = collect(); // Default empty

        if ($this->selectedFolder) {
            if ($this->selectedFolder === 'legacy') {
                // Show legacy archived records (before folder system)
                $query = Enrollment::query()
                    ->select('enrollments.*')
                    ->join('users', 'enrollments.user_id', '=', 'users.id')
                    ->with(['user'])
                    ->whereNull('enrollments.archived_at');

                if ($activeYear && $activeSemester) {
                    $query->where(function($q) use ($activeYear, $activeSemester) {
                        $q->where('enrollments.year_level', 'NOT LIKE', "%{$activeYear->year_name}%")
                          ->orWhere('enrollments.year_level', 'NOT LIKE', "%{$activeSemester->name}%");
                    });
                }
            } else {
                // Show records from a specific folder (both properly archived AND legacy)
                $parts = explode('|', $this->selectedFolder);
                $semesterName = trim($parts[0] ?? '');
                $academicYearName = trim($parts[1] ?? '');

                $query = Enrollment::query()
                    ->select('enrollments.*')
                    ->join('users', 'enrollments.user_id', '=', 'users.id')
                    ->with(['user'])
                    ->where(function($q) use ($semesterName, $academicYearName) {
                        // 1. Properly archived matching the folder
                        $q->where(function($sub) use ($semesterName, $academicYearName) {
                            $sub->whereNotNull('enrollments.archived_at')
                                ->where('enrollments.semester_name', $semesterName)
                                ->where('enrollments.academic_year_name', $academicYearName);
                        })
                        // 2. Legacy records matching the folder
                        ->orWhere(function($sub) use ($semesterName, $academicYearName) {
                            $sub->whereNull('enrollments.archived_at')
                                ->where('enrollments.year_level', 'LIKE', "%{$semesterName}%")
                                ->where('enrollments.year_level', 'LIKE', "%{$academicYearName}%");
                        });
                    });
            }

            // Apply filters
            if ($this->level) {
                $query->where('enrollments.level', $this->level);
            }

            if ($this->selectedCourse) {
                $query->where('enrollments.course_code', $this->selectedCourse);
            }

            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->whereHas('user', function ($sub) {
                        $sub->where('first_name', 'like', "%{$this->search}%")
                            ->orWhere('last_name', 'like', "%{$this->search}%")
                            ->orWhere('email', 'like', "%{$this->search}%");
                    })
                    ->orWhere('enrollments.course_code', 'like', "%{$this->search}%")
                    ->orWhere('enrollments.year_level', 'like', "%{$this->search}%");
                });
            }

            $applications = $query->orderBy('enrollments.created_at', 'desc')->paginate(10);
        }

        return view('livewire.admin.admin-archive-manager', [
            'applications' => $applications,
            'folders' => $folders,
            'legacyCount' => $legacyCount,
            'courses' => \App\Models\Course::orderBy('course_name')->get(),
        ]);
    }
}
