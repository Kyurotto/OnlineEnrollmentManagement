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
        // Fetch College Programs and SHS Strands separately
        $programs = Course::where('type', 'program')->orderBy('id', 'desc')->get();
        $strands = Course::where('type', 'shs')->orderBy('id', 'desc')->get();

        return view('registrar.programs.index', compact('programs', 'strands'));
    }

    public function store(Request $request)
    {
        $rules = [
            'course_code' => 'required|string|unique:courses,course_code',
            'course_name' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|in:program,shs',
        ];

        // Track is required only for SHS strands
        if ($request->type === 'shs') {
            $rules['track'] = 'required|in:ACAD,TVL';
        }

        $validated = $request->validate($rules);

        $data = [
            'course_code' => $validated['course_code'],
            'course_name' => $validated['course_name'],
            'description' => $validated['description'] ?? '',
            'type' => $validated['type'],
        ];

        // Add track for SHS strands - always include if type is SHS
        if ($validated['type'] === 'shs') {
            $data['track'] = $validated['track'] ?? null;
        }

        Course::create($data);

        $label = $request->type === 'program' ? 'Program' : 'Strand';
        return redirect()->route('registrar.programs.index')->with('success', $label . ' added successfully.');
    }

    public function update(Request $request, $id)
    {
        $program = Course::findOrFail($id);

        $rules = [
            'course_code' => 'required|string|unique:courses,course_code,' . $id,
            'course_name' => 'required|string',
            'description' => 'nullable|string',
        ];

        // Track is required for SHS strands
        if ($program->type === 'shs') {
            $rules['track'] = 'required|in:ACAD,TVL';
        }

        $validated = $request->validate($rules);

        $data = [
            'course_code' => $validated['course_code'],
            'course_name' => $validated['course_name'],
            'description' => $validated['description'] ?? '',
        ];

        // Always add track for SHS strands - ensure it's saved to database
        if ($program->type === 'shs') {
            $data['track'] = $validated['track'] ?? null;
        }

        $program->update($data);

        $label = $program->type === 'program' ? 'Program' : 'Strand';
        return redirect()->route('registrar.programs.index')->with('success', $label . ' updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $course = Course::findOrFail($id);

            // Delete related sections first to prevent constraint errors
            Section::where('course_id', $id)->delete();

            $course->delete();
            return redirect()->route('registrar.programs.index')->with('success', 'Program deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('registrar.programs.index')->with('error', 'Cannot delete program. It contains active enrollments or records.');
        }
    }
}
