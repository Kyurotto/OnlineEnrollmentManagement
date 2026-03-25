<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    /**
     * Display the Manage Courses page.
     */
    public function index()
    {
        $courses = Course::where('type', '=', 'course', 'and')->latest()->paginate(10);
        $pendingCount = Enrollment::where('status', '=', 'Pending', 'and')->count();
        return view('admin.courses.index', compact('courses', 'pendingCount'));
    }

    /**
     * Store a new course (Form Submission).
     */
    public function store(Request $request)
    {
        // 1. Validate (Check 'course_code' column in DB)
        $request->validate([
            'course_code' => 'required|string|unique:courses,course_code',
            'course_name' => 'required|string',
            'credits'     => 'required|integer',
            'description' => 'nullable|string',
        ]);

        // 2. Create Course (Map inputs to DB columns)
        $course = new Course();
        $course->course_code = $request->course_code; // Fixed: Saving to 'course_code'
        $course->course_name = $request->course_name; // Fixed: Saving to 'course_name'
        $course->credits = $request->credits;
        $course->description = $request->description;
        $course->save();

        return redirect()->route('admin.courses.index')->with('success', 'Course added successfully!');
    }

    /**
     * Show the edit form.
     */
    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $pendingCount = Enrollment::where('status', '=', 'Pending', 'and')->count();

        // Pass both variables to the view
        return view('admin.courses.edit', compact('course', 'pendingCount'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        // 1. Validate
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'course_code' => ['required', 'string', 'max:50', Rule::unique('courses', 'course_code')->ignore($course->id)],
            'course_name' => ['required', 'string', 'max:255'],
            'credits'     => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
        ]);

        // Handle Validation Failure for Autosave
        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // 2. Update Data
        $course->course_code = $request->course_code;
        $course->course_name = $request->course_name;
        $course->credits = $request->credits;
        $course->description = $request->description;
        $course->save();

        // 3. RETURN RESPONSE
        // If it's an Autosave (AJAX) request, return JSON
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Saved successfully', 'last_updated' => now()->format('h:i:s A')]);
        }

        // If it's a normal Button click, Redirect
        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully!');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy($id)
    {
        Course::findOrFail($id)->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully!');
    }
}
