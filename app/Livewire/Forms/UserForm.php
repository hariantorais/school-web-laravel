<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserForm extends Form
{
    public ?User $user = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('nullable|string|min:8')]
    public string $password = '';

    #[Validate('required_with:password|string|min:8|same:password')]
    public string $password_confirmation = '';

    #[Validate('boolean')]
    public bool $is_active = true;


    public function setUser(User $user): void
    {

        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->is_active = $user->is_active ?? true;
    }

    public function clear(): void
    {
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'is_active']);
        $this->user = null;
        $this->resetErrorBag();
    }

    public function store(): string
    {
        // Cegah update user ID 1 oleh admin lain
        if ($this->user && $this->user->id === 1 && auth()->guard('web')->user()->id !== 1) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit Super Admin!');
        }

        $rules = [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user?->id),
            ],
        ];



        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'is_active' => $this->is_active
        ];


        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->user && $this->user->exists) {
            $this->user->update($data);
            $message = 'Pengguna berhasil diperbarui!';
        } else {
            $this->user = User::create($data);
            $message = 'Pengguna berhasil ditambahkan!';
        }

        $this->reset(['password', 'password_confirmation']);

        return $message;
    }
}
