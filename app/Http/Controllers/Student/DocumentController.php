<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function create()
    {
        // Make sure this matches the path where you saved the blade file
        return view('student.upload-documents'); 
    }

    public function store(Request $request)
    {
        // 1. Validate the incoming files (Max 5MB per file)
        $request->validate([
            'form_137' => 'nullable|mimes:jpeg,png,jpg,pdf|max:5120',
            'good_moral' => 'nullable|mimes:jpeg,png,jpg,pdf|max:5120',
            'psa' => 'nullable|mimes:jpeg,png,jpg,pdf|max:5120',
            'id_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // 2. Get the student's current application
        $application = Auth::user()->application; // Assumes a relationship exists

        if (!$application) {
            return back()->withErrors('You must start an application before uploading documents.');
        }

        // 3. Save the files to the 'public/documents' disk and update database
        if ($request->hasFile('form_137')) {
            $application->form_137_path = $request->file('form_137')->store('documents/form137', 'public');
        }
        
        if ($request->hasFile('good_moral')) {
            $application->good_moral_path = $request->file('good_moral')->store('documents/good_moral', 'public');
        }

        if ($request->hasFile('psa')) {
            $application->psa_path = $request->file('psa')->store('documents/psa', 'public');
        }

        if ($request->hasFile('id_picture')) {
            $application->id_picture_path = $request->file('id_picture')->store('documents/id_pictures', 'public');
        }

        $application->save();

        return back()->with('success', 'Documents uploaded successfully!');
    }
}