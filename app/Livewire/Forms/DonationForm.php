<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Donation;
use App\Services\DonationService;
use Illuminate\Validation\Rule;

class DonationForm extends Form
{
    // Tampung ID untuk pengondisian mode Edit
    public ?int $id = null;

    public string $title = '';
    public string $description = '';
    public string $type = 'one_time'; // Default sesuai blueprint migrasi
    public ?int $donation_category_id = null; // Tambahan kolom FK
    public mixed $target_amount = 0;
    public string $end_date = '';
    public bool $is_active = true; // Tambahan kolom status
    public $image = null; // Temporary file object dari Livewire input
    public bool $is_featured = false;

    /**
     * Aturan Validasi Dinamis & Kondisional
     */
    public function rules(): array
    {
        return [
            'title'                => ['required', 'string', 'min:5', 'max:150'],
            'description'          => ['required', 'string', 'min:20'],

            // Validasi Tipe sesuai Enum di DB
            'type'                 => ['required', Rule::in(['one_time', 'recurring_open', 'recurring_subscription'])],

            // Validasi Kategori wajib terdaftar di tabel induk
            'donation_category_id' => ['required', 'integer', 'exists:donation_categories,id'],

            // Target dana hanya wajib dan minimal 100rb jika tipenya 'one_time'
            'target_amount'        => [
                $this->type === 'one_time' ? 'required' : 'nullable',
                'numeric',
                $this->type === 'one_time' ? 'min:100000' : 'max:0'
            ],

            // Tanggal berakhir hanya valid jika program bertipe 'one_time'
            'end_date'             => [
                $this->type === 'one_time' ? 'required' : 'nullable',
                'date',
                'after_or_equal:today'
            ],

            'is_active'            => ['required', 'boolean'],
            'image'                => ['nullable', 'image', 'max:2048', 'mimes:jpeg,jpg,png,webp'],
        ];
    }

    /**
     * Kustomisasi Label Error Bahasa Indonesia yang Bersih
     */
    public function validationAttributes(): array
    {
        return [
            'title'                => 'Judul Program',
            'description'          => 'Deskripsi',
            'type'                 => 'Sifat Program',
            'donation_category_id' => 'Kategori Donasi',
            'target_amount'        => 'Target Dana',
            'end_date'             => 'Batas Tanggal',
            'is_active'            => 'Status Keaktifan',
            'image'                => 'Foto Sampul',
        ];
    }

    /**
     * Set data dari model ke form saat mode EDIT dipicu
     */
    public function setDonation(Donation $donation): void
    {
        $this->id                   = $donation->id;
        $this->title                = $donation->title;
        $this->description          = $donation->description;
        $this->type                 = $donation->type;
        $this->donation_category_id = $donation->donation_category_id;
        $this->target_amount        = $donation->target_amount;
        $this->end_date             = $donation->end_date ? \Carbon\Carbon::parse($donation->end_date)->format('Y-m-d') : '';
        $this->is_active            = (bool) $donation->is_active;
        $this->is_featured          = (bool) $donation->is_featured;

        // Sengaja dikosongkan karena input file bersifat write-only dari client
        $this->image = null;
    }

    /**
     * Memproses Eksekusi Simpan Akhir
     */
    public function store(): array
    {
        // Jalankan validasi internal Livewire sebelum menyusun payload
        $this->validate();

        $service = app(DonationService::class);

        // Proteksi logika bisnis: Bersihkan nilai jika program bertipe Berkelanjutan (Recurring)
        $finalTargetAmount = $this->type === 'one_time' ? (float) $this->target_amount : 0.00;
        $finalEndDate      = $this->type === 'one_time' ? ($this->end_date ?: null) : null;

        // Susun data teks murni sesuai blueprint migrasi
        $payload = [
            'title'                => strip_tags(trim($this->title)),
            'description'          => $this->description,
            'type'                 => $this->type,
            'donation_category_id' => (int) $this->donation_category_id,
            'target_amount'        => $finalTargetAmount,
            'end_date'             => $finalEndDate,
            'is_active'            => (bool) $this->is_active,
            'is_featured'          => (bool) $this->is_featured,
        ];

        // CEK JALUR EKSEKUSI
        if ($this->id) {
            // MODE UPDATE: Kirim ID, data teks, dan file gambar baru (jika ada)
            $service->update($this->id, $payload, $this->image);
            $message = 'Program donasi berhasil diperbarui!';
        } else {
            // MODE CREATE: Kirim data teks dan file gambar baru
            $service->create($payload, $this->image);
            $message = 'Program donasi baru berhasil diterbitkan!';
        }

        $this->clear();

        return [
            'type'    => 'success',
            'message' => $message
        ];
    }

    public function clear(): void
    {
        $this->reset();
    }
}
