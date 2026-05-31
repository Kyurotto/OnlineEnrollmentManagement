<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Added based on your use trait

/**
 * @property string $status
 * @property string $role
 * @property string $username
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $name
 * @property string $email
 */
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
        'role',
        'status',
    ];

    // Link to Application (Legacy/Latest)
    public function application()
    {
        return $this->hasOne(Enrollment::class, 'user_id')->latest();
    }

    // Historical Enrollment Records
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'user_id');
    }

    // Helper for Real Status
    public function getDisplayStatusAttribute()
    {
        if (in_array($this->role, ['admin', 'registrar', 'cashier', 'teacher'])) {
            return 'Active';
        }

        return $this->application?->status ?? 'Not Enrolled';
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'string',
        'role' => 'string',
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }
}
