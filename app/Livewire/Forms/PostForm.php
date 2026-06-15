<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Post;
use App\Services\PostService;
use App\Traits\HasImageProcess;
use Livewire\Attributes\Validate;

class PostForm extends Form
{
    use HasImageProcess;

    // Model referensi untuk mode Edit
    public ?Post $post = null;

    // Properti Form Bindings
    public string $title = '';
    public string $category_id = '';
    public string $content = '';
    public string $status = 'draft';
    public $featured_image;

    /**
     * Aturan Validasi Dinamis
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:5|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|min:20',
            'status' => 'required|in:draft,published',
            'featured_image' => 'nullable|' . ($this->post ? 'image|max:2048' : 'required|image|max:2048'),
        ];
    }

    /**
     * Set Data Postingan untuk Mode Edit (Hydrate)
     */
    public function setPost(Post $post): void
    {
        $this->post = $post;
        $this->title = $post->title;
        $this->category_id = $post->category_id;
        $this->content = $post->content;
        $this->status = $post->status;
    }

    /**
     * Proses Penyimpanan Utama (Menangani Create & Update secara cerdas)
     */
    public function store(PostService $postService): void
    {
        $this->validate();

        // Kompresi gambar via Trait jika ada file baru diunggah
        $imagePath = null;
        if ($this->featured_image) {
            $imagePath = $this->compressAndStore($this->featured_image, 'blog-images');
        }

        $formData = $this->all();

        if ($this->post) {
            // Jalankan fungsi update jika objek model post terdeteksi
            $postService->update($this->post, $formData, $imagePath);
        } else {
            // Jalankan fungsi create jika postingan baru
            $postService->create($formData, $imagePath);
        }
    }

    /**
     * Bersihkan Form State setelah eksekusi
     */
    public function clear(): void
    {
        $this->reset(['title', 'category_id', 'content', 'featured_image', 'status']);
        $this->post = null;
    }
}
