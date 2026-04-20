<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Serve a secured document from the private disk.
     */
    public function show($path)
    {
        // Find the enrollment record associated with this document path
        $enrollment = Enrollment::where('form_137_path', $path)
            ->orWhere('sf10_path', $path)
            ->orWhere('good_moral_path', $path)
            ->orWhere('psa_path', $path)
            ->orWhere('id_picture_path', $path)
            ->orWhere('promissory_note_path', $path)
            ->firstOrFail();

        // Check for authorization: Owner OR Admin/Registrar
        if ($enrollment->user_id !== Auth::id() && !in_array(Auth::user()->role, ['admin', 'registrar'])) {
            abort(403, 'Unauthorized access to document.');
        }

        // Check if file exists in the private 'local' disk
        if (!Storage::disk('local')->exists($path)) {
            // Fallback: check if it still exists in 'public' disk (for backward compatibility during migration)
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->response($path);
            }
            abort(404, 'Document file not found.');
        }

        return Storage::disk('local')->response($path);
    }
}
