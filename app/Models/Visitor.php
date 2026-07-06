<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'url',
        'referrer',
        'device',
        'browser',
        'os',
        'country',
        'city',
        'is_unique',
    ];

    protected $casts = [
        'is_unique' => 'boolean',
    ];

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeUnique($query)
    {
        return $query->where('is_unique', true);
    }

    public function scopeLastDays($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function getDeviceIconAttribute(): string
    {
        return match ($this->device) {
            'desktop' => '🖥️',
            'mobile' => '📱',
            'tablet' => '📟',
            default => '🌐',
        };
    }
}
