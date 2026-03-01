<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Course;       
use App\Models\AcademicYear; 

class SectionController extends Controller
{
    public function index()
    {
        // 1. Fetch Sections with Course info
        $sections = Section::with('course')
                           ->orderBy('id', 'desc')
                           ->paginate(10);
        
        // 2. Fetch Dropdown Data
        $courses = Course::all(); 
        
        // Fetch active years, fallback to all if none active
        $academicYears = AcademicYear::where('is_active', true)->get();
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
            'section_name' => 'required|string|max:10', // Limit length to encourage "1A"
        ]);

        Section::create([
            'academic_year' => $request->academic_year,
            'course_id' => $request->course_id,
            'section_name' => strtoupper($request->section_name), // Force Uppercase
        ]);

        return back()->with('success', 'Section created successfully.');
    }

    public function update(Request $request, $id)
    {
        $section = Section::findOrFail($id);

        $request->validate([
            'academic_year' => 'required|string',
            'course_id' => 'required|exists:courses,id',
            'section_name' => 'required|string|max:10',
        ]);

        $section->update([
            'academic_year' => $request->academic_year,
            'course_id' => $request->course_id,
            'section_name' => strtoupper($request->section_name),
        ]);

        return back()->with('success', 'Section updated successfully.');
    }

    public function destroy($id)
    {
        Section::findOrFail($id)->delete();
        return back()->with('success', 'Section deleted successfully.');
    }
}