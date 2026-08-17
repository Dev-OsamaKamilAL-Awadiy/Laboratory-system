<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $table = 'login_sessions';

    protected $fillable = [
        'user_id',
        'login_time',
        'logout_time',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'login_time' => 'datetime',
        'logout_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return $this->logout_time === null;
    }

    public function getDurationAttribute(): int
    {
        $endTime = $this->logout_time ?? now();
        return $this->login_time->diffInSeconds($endTime);
    }

    public function getDurationHoursAttribute(): float
    {
        return round($this->duration / 3600, 2);
    }
}
