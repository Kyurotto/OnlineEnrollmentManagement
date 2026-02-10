<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    // Fixes "Mass Assignment" error. Allows all columns to be filled.
    protected $guarded = [];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(Enrollment::class, 'application_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
