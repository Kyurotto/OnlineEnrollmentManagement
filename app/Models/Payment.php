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
        'installment_type', // Prelim, Midterm, Final, or Full Payment
        'down_payment_total', // Total downpayment amount
        'is_installment', // Flag to identify installment payments
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

    /**
     * Scope to get installment payments only
     */
    public function scopeInstallments($query)
    {
        return $query->where('is_installment', true);
    }

    /**
     * Scope to get full payments
     */
    public function scopeFullPayments($query)
    {
        return $query->where('is_installment', false);
    }

    /**
     * Scope to get payments by installment type
     */
    public function scopeByInstallmentType($query, $type)
    {
        return $query->where('installment_type', $type);
    }

    /**
     * Get all installments for a specific enrollment
     */
    public static function getEnrollmentInstallments($enrollmentId)
    {
        return self::where('application_id', $enrollmentId)
            ->where('is_installment', true)
            ->orderByRaw("FIELD(installment_type, 'Prelim', 'Midterm', 'Final')")
            ->get()
            ->groupBy('installment_type');
    }
}
