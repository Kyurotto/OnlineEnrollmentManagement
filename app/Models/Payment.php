<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // 1. ALLOW MASS ASSIGNMENT (Critical for saving)
    protected $fillable = [
        'user_id',
        'application_id',
        'amount',
        'status',
        'payment_date',
        'transaction_id',
        'payment_method',
    ];

    // 2. DEFINE RELATIONSHIPS (Critical for Cashier View)
    
    // Links payment to the Student
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Links payment to the Enrollment Application (to see Course/Year)
    public function application()
    {
        return $this->belongsTo(Enrollment::class, 'application_id');
    }
}