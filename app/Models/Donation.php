<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * Ditambahkan: donation_category_id, type, dan is_featured
     */
    protected $fillable = [
        'donation_category_id',
        'title',
        'slug',
        'description',
        'image_path',
        'type',
        'target_amount',
        'current_amount',
        'is_active',
        'is_featured',
        'end_date',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'end_date' => 'date',
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Otomatis membuat atau memperbarui slug secara aman saat memanipulasi model langsung
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($program) {
            // Hanya buat slug otomatis jika slug kosong ATAU jika judulnya berubah
            if (empty($program->slug) || $program->isDirty('title')) {
                $slug = Str::slug($program->title);

                // Pengaman internal: Cegah duplikasi slug di level model jika tidak lewat Service
                $count = static::where('slug', 'like', $slug . '%')
                    ->where('id', '!=', $program->id)
                    ->count();

                $program->slug = $count ? "{$slug}-" . Str::lower(Str::random(5)) : $slug;
            }
        });
    }

    /**
     * Aksesor Premium untuk Mengambil URL Gambar Sampul
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Gunakan disk 'public' secara konsisten sesuai sistem penyimpanan Anda
                if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
                    return Storage::disk('public')->url($this->image_path);
                }

                // Placeholder lokal yang rapi jika gambar tidak ditemukan
                return asset('images/default.jpeg');
            }
        );
    }

    /**
     * Relasi ke Tabel Mutasi Transaksi Sukses / Pending
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(DonationTransaction::class, 'donation_id', 'id');
    }

    /**
     * Relasi ke Tabel Induk Kategori Donasi
     * Diperbaiki: Menggunakan return type BelongsTo untuk standarisasi IDE Hinting
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(DonationCategory::class, 'donation_category_id', 'id');
    }
}
