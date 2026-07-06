<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'category_id', 'title', 'slug', 'featured_image', 'content', 'status', 'published_at'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function incrementViews(): void
    {
        // Menggunakan increment bawaan Eloquent agar langsung disimpan ke DB
        $this->increment('views');
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                // 1. Jika di database kolom featured_image terisi, dan filenya benar-benar ada di disk
                if ($this->featured_image && Storage::disk('public')->exists($this->featured_image)) {
                    return asset('storage/' . $this->featured_image);
                }

                // 2. Jika tidak ada gambar, kembalikan gambar placeholder aset sekolah resmi
                //    Pastikan Anda menaruh file 'default-post.webp' di folder public/assets/images/
                return asset('images/default.jpeg');
            }
        );
    }
}
