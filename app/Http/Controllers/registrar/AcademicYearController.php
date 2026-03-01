<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicYear;

class AcademicYearController extends Controller
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
            'year_name' => 'required|string|unique:academic_years,year_name',
        ]);

        // If setting this to Active, deactivate all others
        if ($request->has('is_active')) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }

        AcademicYear::create([
            'year_name' => $request->year_name,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return back()->with('success', 'Academic Year created successfully.');
    }

    public function update(Request $request, $id)
    {
        $academicYear = AcademicYear::findOrFail($id);

        $request->validate([
            'year_name' => 'required|string|unique:academic_years,year_name,'.$id,
        ]);

        // If setting this to Active, deactivate others
        if ($request->has('is_active')) {
            AcademicYear::where('id', '!=', $id)->update(['is_active' => false]);
        }

        $academicYear->update([
            'year_name' => $request->year_name,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return back()->with('success', 'Academic Year updated successfully.');
    }

    public function destroy($id)
    {
        AcademicYear::findOrFail($id)->delete();
        return back()->with('success', 'Academic Year deleted successfully.');
    }
}