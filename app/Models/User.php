<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'date_birth',
        'gender',
        'image',
        'role',              // enum('admin','customer')
        'status',            // enum('active','locked','banned')
        'banned_until',
        'google_id',
        'email_verified_at',
        'phone_verified_at',
        'verification_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'banned_until' => 'datetime',
        'date_birth' => 'date',
    ];

    public function orders()
    {
        return $this->hasMany(\App\Models\Admin\Order::class);
    }
}