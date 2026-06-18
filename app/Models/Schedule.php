<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'employee_id',
        'day_of_week',
        'start_time',
        'end_time',
        'room',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
