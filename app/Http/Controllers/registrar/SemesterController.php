<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Semester; // Ensure you have this Model created
use Carbon\Carbon;

class SemesterController extends Controller
{
    public function index()
    {
        // Order by active status first, then by latest academic year
        $semesters = Semester::orderBy('is_active', 'desc')
                             ->orderBy('id', 'desc')
                             ->paginate(10);
                             
        return view('registrar.semesters.index', compact('semesters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_year' => 'required|string',
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // If this new semester is set to Active, deactivate all others
        if ($request->has('is_active')) {
            Semester::where('is_active', true)->update(['is_active' => false]);
        }

        Semester::create([
            'academic_year' => $request->academic_year,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return back()->with('success', 'Semester created successfully.');
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

        // If setting to Active, deactivate others
        if ($request->has('is_active')) {
            Semester::where('id', '!=', $id)->update(['is_active' => false]);
        }

        $semester->update([
            'academic_year' => $request->academic_year,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return back()->with('success', 'Semester updated successfully.');
    }

    public function destroy($id)
    {
        Semester::findOrFail($id)->delete();
        return back()->with('success', 'Semester deleted successfully.');
    }
}