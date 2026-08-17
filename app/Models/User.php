<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'phone',
        'role',
        'is_active',
        'language',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function sessions()
    {
        return $this->hasMany(UserSession::class);
    }

    public function cases()
    {
        return $this->hasMany(CaseRecord::class, 'created_by');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function onlineSessions()
    {
        return $this->hasMany(UserSession::class)->whereNull('logout_time');
    }

    public function getTotalHoursAttribute(): float
    {
        $totalSeconds = $this->sessions()
            ->selectRaw('COALESCE(SUM(TIMESTAMPDIFF(SECOND, login_time, COALESCE(logout_time, NOW()))), 0) as total')
            ->value('total');

        return round($totalSeconds / 3600, 2);
    }
}
