<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicYear;

class RegistrarAcademicYearController extends Controller
{
    public function index()
    {
        // Show Active year first, then by ID descending
        $years = AcademicYear::orderBy('is_active', 'desc')
                             ->orderBy('year_name', 'desc')
                             ->paginate(10);

        return view('registrar.academic_years.index', compact('years'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'year_name' => 'required|string',
        ]);

        // Prevention of duplicates with Forbidden Error
        if (AcademicYear::where('year_name', $request->year_name)->exists()) {
            abort(403, 'Forbidden: This Academic Year already exists.');
        }

        // If setting this to Active, deactivate all others and semesters to ensure sync
        if ($request->has('is_active')) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
            \App\Models\Semester::query()->update(['is_active' => false]);
        }

        AcademicYear::create([
            'year_name' => $request->year_name,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('registrar.academic_years.index')->with('success', 'Academic Year created successfully.');
    }

    public function update(Request $request, $id)
    {
        $academicYear = AcademicYear::findOrFail($id);

        $request->validate([
            'year_name' => 'required|string|unique:academic_years,year_name,'.$id,
        ]);

        // If setting this to Active, deactivate others and ALL semesters to ensure sync
        if ($request->has('is_active')) {
            AcademicYear::where('id', '!=', $id)->update(['is_active' => false]);
            \App\Models\Semester::query()->update(['is_active' => false]);
        }

        $academicYear->update([
            'year_name' => $request->year_name,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('registrar.academic_years.index')->with('success', 'Academic Year updated successfully.');
    }

    public function destroy($id)
    {
        AcademicYear::findOrFail($id)->delete();
        return redirect()->route('registrar.academic_years.index')->with('success', 'Academic Year deleted successfully.');
    }
}