<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Services\PostService;

class PostForm extends Form
{
    public ?int $id = null;
    public string $title = '';
    public string $category_id = '';
    public string $content = '';
    public string $status = 'draft';
    public $image = null;

    // 🔥 NEW PROPERTY: Daftarkan properti penampung tanggal
    public ?string $published_at = null;

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'min:5', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'content'     => ['required', 'string', 'min:20'],
            'status'      => ['required', 'in:draft,published'],
            'image'       => ['nullable', 'image', 'max:2048', 'mimes:jpeg,jpg,png,webp'],
        ];
    }

    /**
     * Map data dari model ke form saat mode EDIT dipicu
     */
    public function setPost(\App\Models\Post $post): void
    {
        $this->id = $post->id;
        $this->title = $post->title;
        $this->category_id = $post->category_id;
        $this->content = $post->content;
        $this->status = $post->status;

        // 🔥 AMBIL DATA DARI DB: Pertahankan data penanggalan yang sudah ada sebelumnya
        $this->published_at = $post->published_at ? $post->published_at->toDateTimeString() : null;
        $this->image = null;
    }

    /**
     * Memproses Eksekusi Simpan Akhir melalui Service Layer Terpusat
     */
    public function store(): array
    {
        $service = app(PostService::class);

        // 🔥 LOGIKA ALA WORDPRESS: Kelola nilai tanggal terbit sebelum masuk ke payload
        if ($this->status === 'published') {
            // Jika status publish, gunakan tanggal lama (jika ada) atau set ke waktu sekarang jika baru terbit
            $publishedAtValue = $this->published_at ?? now()->toDateTimeString();
        } else {
            // Jika disimpan sebagai draft, tanggal terbit harus dihilangkan (null)
            $publishedAtValue = null;
        }

        // Siapkan payload bersih
        $payload = [
            'title'        => strip_tags(trim($this->title)),
            'category_id'  => (int) $this->category_id,
            'content'      => $this->content,
            'status'       => $this->status,
            'published_at' => $publishedAtValue, // 🔥 MASUKKAN KE PAYLOAD UNTUK SERVICE
        ];

        // Eksekusi percabangan berdasarkan keberadaan ID
        if ($this->id) {
            $service->update($this->id, $payload, $this->image);
            $message = 'Artikel postingan berhasil diperbarui!';
        } else {
            $service->create($payload, $this->image);
            $message = 'Artikel postingan baru berhasil diterbitkan!';
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
