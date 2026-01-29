<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    // Allow all columns to be filled
    protected $guarded = [];

    // Link back to the User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
