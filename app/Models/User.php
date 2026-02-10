<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens; // Added based on your use trait

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'first_name',
        'middle_name',
        'last_name',
        'name',
        'email',
        'password',
        // 'role',
        'status'
    ];

    // Link to Application
    public function application()
    {
        return $this->hasOne(Enrollment::class, 'user_id')->latest();
    }

    // Helper for Real Status
    public function getDisplayStatusAttribute()
    {
        if (in_array($this->role, ['admin', 'registrar', 'cashier'])) {
            return 'Active';
        }
        return $this->application->status ?? 'Not Enrolled';
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
