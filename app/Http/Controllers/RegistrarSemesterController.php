<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Semester;
use App\Models\AcademicYear; // Import is required!
use Carbon\Carbon;

class RegistrarSemesterController extends Controller
{
    public function index()
    {
        // 1. Fetch Semesters
        $semesters = Semester::orderBy('is_active', 'desc')
                             ->orderBy('id', 'desc')
                             ->paginate(10);
        
        // 2. Fetch Academic Years (THIS WAS MISSING causing the error)
        $academicYears = AcademicYear::orderBy('year_name', 'desc')->get();
                             
        return view('registrar.semesters.index', compact('semesters', 'academicYears'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_year' => 'required|string',
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $isActive = $request->has('is_active');

        // Logic: If setting to Active, auto-update Academic Year
        if ($isActive) {
            $this->activateSemesterAndYear($request->academic_year);
        }

        Semester::create([
            'academic_year' => $request->academic_year,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $isActive,
        ]);

        return redirect()->route('registrar.semesters.index')->with('success', 'Semester created successfully.');
    }

    public function update(Request $request, $id)
    {
        $semester = Semester::findOrFail($id);

        $request->validate([
            'academic_year' => 'required|string',
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $isActive = $request->has('is_active');

        if ($isActive) {
             $this->activateSemesterAndYear($request->academic_year, $id);
        }

        $semester->update([
            'academic_year' => $request->academic_year,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $isActive,
        ]);

        return redirect()->route('registrar.semesters.index')->with('success', 'Semester updated successfully.');
    }

    public function destroy($id)
    {
        Semester::findOrFail($id)->delete();
        return redirect()->route('registrar.semesters.index')->with('success', 'Semester deleted successfully.');
    }

    // --- NEW: Custom Activate Function (Button Click) ---
    public function activate($id)
    {
        $semester = Semester::findOrFail($id);
        
        // Call helper to activate this semester and its year
        $this->activateSemesterAndYear($semester->academic_year, $id);

        // Explicitly update this semester to active (redundancy check)
        $semester->update(['is_active' => true]);

        return redirect()->route('registrar.semesters.index')->with('success', "Active status updated for {$semester->academic_year} - {$semester->name}.");
    }

    // Helper function to keep code clean
    private function activateSemesterAndYear($academicYearName, $excludeSemesterId = null)
    {
        // 1. Deactivate ALL Semesters
        Semester::query()->update(['is_active' => false]);

        // 2. Deactivate ALL Academic Years
        AcademicYear::query()->update(['is_active' => false]);

        // 3. Activate the specific Academic Year
        AcademicYear::where('year_name', $academicYearName)->update(['is_active' => true]);
        
        // (Note: The specific Semester gets activated in the calling function)
    }
}