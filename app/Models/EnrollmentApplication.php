<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollmentApplication extends Model
{
    // Fixes "Mass Assignment" error.
    protected $guarded = [];

    // Automatically converts JSON columns to Arrays
    protected $casts = [
        'course_ids' => 'array',
        'files' => 'array',
        'parent_info' => 'array',
        'student_info' => 'array',
        'processed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'application_id');
    }
}
