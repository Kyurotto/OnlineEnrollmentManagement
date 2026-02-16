<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Course;       // To get list of programs (BSIS, etc.)
use App\Models\AcademicYear; // To get list of years

class SectionController extends Controller
{
    public function index()
    {
        // Fetch sections with their associated course info, ordered by latest
        $sections = Section::with('course')
                           ->orderBy('id', 'desc')
                           ->paginate(10);
        
        // Fetch data for the dropdown menus in the Modal
        $courses = Course::all(); 
        $academicYears = AcademicYear::where('is_active', true)->get(); // Only show active years usually

        // If no active years, fallback to all years so the dropdown isn't empty
        if($academicYears->isEmpty()) {
            $academicYears = AcademicYear::all();
        }

        return view('registrar.sections.index', compact('sections', 'courses', 'academicYears'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_year' => 'required|string',
            'course_id' => 'required|exists:courses,id',
            'section_name' => 'required|string',
        ]);

        Section::create([
            'academic_year' => $request->academic_year,
            'course_id' => $request->course_id,
            'section_name' => $request->section_name,
        ]);

        return back()->with('success', 'Section created successfully.');
    }

    public function update(Request $request, $id)
    {
        $section = Section::findOrFail($id);

        $request->validate([
            'academic_year' => 'required|string',
            'course_id' => 'required|exists:courses,id',
            'section_name' => 'required|string',
        ]);

        $section->update([
            'academic_year' => $request->academic_year,
            'course_id' => $request->course_id,
            'section_name' => $request->section_name,
        ]);

        return back()->with('success', 'Section updated successfully.');
    }

    public function destroy($id)
    {
        Section::findOrFail($id)->delete();
        return back()->with('success', 'Section deleted successfully.');
    }
}