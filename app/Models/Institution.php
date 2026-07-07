<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'favicon',
        'profile_video_url',
        'vision',
        'mission',
        'motto',
        'tagline',
        'description',
        'history',
        'address',
        'email',
        'whatsapp',
        'whatsapp_2',
        'facebook',
        'instagram',
        'youtube',
        'twitter',
        'tiktok',
        'website',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'timezone',
        'locale',
        'is_active',
        'established_year',
        'accreditation',
        'total_students',
        'total_alumni',
        'total_teachers',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'established_year' => 'integer',
        'total_students' => 'integer',
        'total_alumni' => 'integer',
        'total_teachers' => 'integer',
    ];

    /**
     * Boot: Auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($institution) {
            if (empty($institution->slug)) {
                $institution->slug = Str::slug($institution->name);
            }
        });
    }

    /**
     * Accessor: Logo URL
     */
    public function getLogoUrlAttribute(): string
    {
        return $this->logo
            ? asset('storage/' . $this->logo)
            : asset('images/default-logo.png');
    }

    /**
     * Accessor: Favicon URL
     */
    public function getFaviconUrlAttribute(): string
    {
        return $this->favicon
            ? asset('storage/' . $this->favicon)
            : asset('images/favicon.ico');
    }

    /**
     * Accessor: Vision as array
     */
    public function getVisionListAttribute(): array
    {
        if (empty($this->vision)) {
            return [];
        }
        return array_filter(explode("\n", trim($this->vision)));
    }

    /**
     * Accessor: Mission as array
     */
    public function getMissionListAttribute(): array
    {
        if (empty($this->mission)) {
            return [];
        }
        return array_filter(explode("\n", trim($this->mission)));
    }

    /**
     * Scope: Active only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
