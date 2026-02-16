<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course; // Assuming 'Course' is your model for Programs

class ProgramController extends Controller
{
    public function index()
    {
        // Fetch programs with pagination (10 per page as shown in your screenshot)
        $programs = Course::orderBy('id', 'asc')->paginate(10);
        return view('registrar.programs.index', compact('programs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_code' => 'required|unique:courses,course_code',
            'description' => 'nullable|string',
        ]);

        Course::create([
            'course_code' => strtoupper($request->course_code),
            'description' => $request->description ?? '',
        ]);

        return back()->with('success', 'Program added successfully.');
    }

    public function update(Request $request, $id)
    {
        $program = Course::findOrFail($id);
        
        $request->validate([
            'course_code' => 'required|unique:courses,course_code,'.$id,
        ]);

        $program->update([
            'course_code' => strtoupper($request->course_code),
            'description' => $request->description,
        ]);

        return back()->with('success', 'Program updated successfully.');
    }

    public function destroy($id)
    {
        Course::findOrFail($id)->delete();
        return back()->with('success', 'Program deleted successfully.');
    }
}