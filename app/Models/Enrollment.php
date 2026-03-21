<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $guarded = [];

    // *** THIS IS THE MISSING PART ***
    public function user()
    {
        // This tells Laravel that "Enrollment" belongs to a "User"
        return $this->belongsTo(User::class, 'user_id');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'application_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

}
