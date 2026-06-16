<?php

namespace App\Models;

use App\Notifications\CustomResetPassword; // Added this import
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'password',
    'role',
    'is_active'
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Override the default password reset notification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }
}