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

    /**
     * Determine if enrollment is SHS based on course_code
     */
    public function isSHS()
    {
        $shsStrands = ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT'];
        return in_array($this->course_code, $shsStrands);
    }

    /**
     * Get enrollment level (shs or college)
     */
    public function getLevel()
    {
        return $this->isSHS() ? 'shs' : 'college';
    }

    /**
     * Get level-specific document fields
     */
    public function getDocumentFields()
    {
        if ($this->isSHS()) {
            return [
                'form_137_path' => 'JHS Report Card (SF9)',
                'sf10_path' => 'SF10 (Permanent Record)',
                'good_moral_path' => 'Certificate of Good Moral',
                'psa_path' => 'PSA Birth Certificate',
                'id_picture_path' => '2x2 ID Portrait'
            ];
        }
        return [
            'form_137_path' => 'Form 137 (Report Card)',
            'good_moral_path' => 'Certificate of Good Moral',
            'psa_path' => 'PSA Birth Certificate',
            'id_picture_path' => '2x2 ID Portrait'
        ];
    }

}
