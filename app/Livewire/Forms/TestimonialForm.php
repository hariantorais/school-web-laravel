<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Storage;

class TestimonialForm extends Form
{
    public ?Testimonial $testimonial = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:255')]
    public string $role = '';

    #[Validate('nullable|image|max:2048')]
    public $avatar = null;

    #[Validate('required|string')]
    public string $content = '';

    #[Validate('required|integer|min:1|max:5')]
    public int $rating = 5;

    #[Validate('boolean')]
    public bool $is_active = true;

    #[Validate('integer|min:0')]
    public int $order = 0;

    public ?string $oldAvatar = null;

    public function setTestimonial(Testimonial $testimonial): void
    {
        $this->testimonial = $testimonial;
        $this->name = $testimonial->name;
        $this->role = $testimonial->role ?? '';
        $this->content = $testimonial->content;
        $this->rating = $testimonial->rating;
        $this->is_active = $testimonial->is_active;
        $this->order = $testimonial->order ?? 0;
        $this->oldAvatar = $testimonial->avatar ?? null;
    }

    public function clear(): void
    {
        $this->reset(['name', 'role', 'content', 'rating', 'is_active', 'order', 'avatar']);
        $this->oldAvatar = null;
        $this->testimonial = null;
    }

    public function store(): string
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'role' => $this->role,
            'content' => $this->content,
            'rating' => $this->rating,
            'is_active' => $this->is_active,
            'order' => $this->order,
        ];

        if ($this->avatar) {
            if ($this->oldAvatar) {
                Storage::disk('public')->delete($this->oldAvatar);
            }
            $data['avatar'] = $this->avatar->store('testimonials', 'public');
        }

        if ($this->testimonial && $this->testimonial->exists) {
            $this->testimonial->update($data);
            $message = 'Testimoni berhasil diperbarui!';
        } else {
            $this->testimonial = Testimonial::create($data);
            $message = 'Testimoni berhasil ditambahkan!';
        }

        $this->reset(['avatar']);
        $this->oldAvatar = $this->testimonial->avatar ?? null;

        return $message;
    }

    public function removeAvatar(): void
    {
        if ($this->oldAvatar) {
            Storage::disk('public')->delete($this->oldAvatar);
            $this->testimonial->update(['avatar' => null]);
            $this->oldAvatar = null;
        }
    }

    public function getAvatarUrl(): string
    {
        return $this->oldAvatar
            ? asset('storage/' . $this->oldAvatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=A31D1D&color=fff&size=100';
    }
}
