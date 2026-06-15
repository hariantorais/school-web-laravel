<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Donation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image_path',
        'target_amount',
        'current_amount',
        'is_active',
        'end_date',
    ];

    // Otomatis membuat slug dari title saat saving data
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($program) {
            if (empty($program->slug)) {
                $program->slug = Str::slug($program->title);
            }
        });
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
                    return Storage::url($this->image_path);
                }

                return asset('assets/images/placeholder.webp');
            }
        );
    }
}
