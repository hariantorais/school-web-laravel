<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonationTransaction extends Model
{
    use HasFactory;
    /**
     * Atribut yang diizinkan untuk mass-assignment
     */
    protected $fillable = [
        'donation_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'amount',
        'payment_method',
        'status',
        'verified_by', // Menyimpan ID Panitia/Admin yang melakukan konfirmasi dana
        'reference_code',
        'notes',
        'verified_at',
    ];

    /**
     * Casting tipe data kolom database ke tipe objek internal PHP
     */
    protected $casts = [
        'verified_at' => 'datetime',
        'amount'      => 'float',
    ];

    /**
     * Relasi: Setiap transaksi donasi melekat pada satu program donasi induk
     */
    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class);
    }

    /**
     * Relasi: Menghubungkan log transaksi ke data Panitia Penerima Dana (Model User)
     */
    public function recipientPanitia(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * ACCESSOR: Standardisasi badge warna status transaksi untuk menyelaraskan visual UI
     */
    protected function statusMeta(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->payment_status) {
                    'success' => [
                        'label' => 'Berhasil',
                        'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'pulse' => true
                    ],
                    'pending' => [
                        'label' => 'Menunggu',
                        'class' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'pulse' => false
                    ],
                    'failed'  => [
                        'label' => 'Gagal',
                        'class' => 'bg-rose-50 text-rose-700 border-rose-200',
                        'pulse' => false
                    ],
                    default   => [
                        'label' => 'Unknown',
                        'class' => 'bg-slate-50 text-slate-500 border-slate-200',
                        'pulse' => false
                    ],
                };
            }
        );
    }
}
