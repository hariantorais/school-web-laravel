<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Donation;
use App\Services\DonationService;

class DonationForm extends Form
{
    // Tampung ID untuk pengondisian mode Edit
    public ?int $id = null;

    public string $title = '';
    public string $description = '';
    public mixed $target_amount = 0;
    public string $end_date = '';
    public $image = null; // Ini tetap sebagai temporary file object dari Livewire input
    public bool $is_featured = false;

    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'min:5', 'max:150'],
            'description'   => ['required', 'string', 'min:20'],
            'target_amount' => ['required', 'numeric', 'min:100000'],
            'end_date'      => ['nullable', 'date', 'after_or_equal:today'],
            'image'         => ['nullable', 'image', 'max:2048', 'mimes:jpeg,jpg,png,webp'],
        ];
    }

    /**
     * Set data dari model ke form saat mode EDIT dipicu
     */
    public function setDonation(Donation $donation): void
    {
        $this->id = $donation->id;
        $this->title = $donation->title;
        $this->description = $donation->description;
        $this->target_amount = $donation->target_amount;
        $this->end_date = $donation->end_date ? \Carbon\Carbon::parse($donation->end_date)->format('Y-m-d') : '';
        $this->is_featured = (bool) $donation->is_featured;

        // Sengaja dikosongkan karena input file bersifat write-only dari client
        $this->image = null;
    }

    /**
     * Memproses Eksekusi Simpan Akhir
     */
    public function store(): array
    {
        $service = app(DonationService::class);

        // Siapkan data teks murni
        $payload = [
            'title'         => strip_tags(trim($this->title)),
            'description'   => $this->description,
            'target_amount' => (float) $this->target_amount,
            'end_date'      => $this->end_date ?: null,
            'is_featured'   => $this->is_featured,
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
