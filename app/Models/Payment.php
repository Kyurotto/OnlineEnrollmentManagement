<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // ALIGNED WITH NEW UNIFIED DATABASE SCHEMA
    protected $fillable = [
        'application_id',
        'user_id',
        'amount',
        'status', // Replaced 'payment_status' with 'status'
        'payment_date',
        'transaction_id',
        'proof',
        'notes',
        'payment_method'
    ];

    /**
     * Get the student (user) that owns the payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the application/enrollment associated with the payment.
     */
    public function application()
    {
        return $this->belongsTo(Enrollment::class, 'application_id');
    }
}
