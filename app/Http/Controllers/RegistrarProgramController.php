<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course; 
use App\Models\Section;

class RegistrarProgramController extends Controller
{
    public function index()
    {
        // Fetch programs, ordered by latest
        $programs = Course::orderBy('id', 'desc')->paginate(10);
        return view('registrar.programs.index', compact('programs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string|unique:courses,course_name',
            'description' => 'nullable|string',
        ]);

        // Auto-generate a simple code if your DB requires it, otherwise just save name
        // Here we use the first few letters of the name as a fallback code if needed
        $generatedCode = strtoupper(substr($request->course_name, 0, 4)) . rand(10,99);

        Course::create([
            'course_name' => $request->course_name,
            'course_code' => $generatedCode, // Auto-filled behind the scenes
            'description' => $request->description ?? '',
        ]);

        return back()->with('success', 'Program added successfully.');
    }

    public function update(Request $request, $id)
    {
        $program = Course::findOrFail($id);
        
         $request->validate([
            'course_name' => 'required|string|unique:courses,course_name,' . $id,
            'description' => 'nullable|string',
        ]);

        $program->update([
            'course_name' => $request->course_name,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Program updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $course = Course::findOrFail($id);
            
            // Delete related sections first to prevent constraint errors
            Section::where('course_id', $id)->delete();

            $course->delete();
            return back()->with('success', 'Program deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot delete program. It contains active enrollments or records.');
        }
    }
}