<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year', // e.g., "2025-2026"
        'course_id',     // Links to the 'courses' table
        'section_name',  // e.g., "BSIS-1A" or just "1A" depending on your preference
    ];

    // Relationship to get the Course Code (e.g., BSIS, ACT)
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}