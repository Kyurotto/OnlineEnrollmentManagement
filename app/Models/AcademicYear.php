<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'year_name', // e.g., "2025 - 2026"
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}