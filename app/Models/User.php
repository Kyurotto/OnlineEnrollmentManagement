<?php

namespace App\Models;

// Make sure you have these imports
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * YOU MUST ADD THE 4 NEW LINES BELOW TO THIS LIST:
     */
    protected $fillable = [
        'username',      // <--- REQUIRED
        'first_name',    // <--- REQUIRED
        'middle_name',   // <--- REQUIRED
        'last_name',     // <--- REQUIRED
        'name',
        'email',
        'password',
        'role',
    ];

    // ... (rest of the file stays the same) ...
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
