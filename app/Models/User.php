<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_primary',
        'target_activity',
        'target_volume',
        'target_profit',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isPrimaryAdmin(): bool
    {
        return $this->is_primary && $this->role === 'SUPER ADMIN';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'SUPER ADMIN';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'ADMIN';
    }

    public function isMarketing(): bool
    {
        return $this->role === 'MARKETING';
    }

    public function isGuest(): bool
    {
        return $this->role === 'GUEST';
    }

    public function hasAdminAccess(): bool
    {
        return in_array($this->role, ['SUPER ADMIN', 'ADMIN']);
    }

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }

    public function shippers()
    {
        return $this->hasMany(Shipper::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
