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

    #[Validate('nullable|string|min:8|same:password')]
    public string $password_confirmation = '';

    public function setUser(User $user): void
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function clear(): void
    {
        $this->reset(['name', 'email', 'password', 'password_confirmation']);
        $this->user = null;
        $this->resetErrorBag();
    }

    public function store(): string
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                // Unique: kecuali untuk user yang sedang diedit
                Rule::unique('users', 'email')->ignore($this->user?->id),
            ],
        ];

        if (!$this->user || $this->password) {
            $rules['password'] = 'required|string|min:8';
            $rules['password_confirmation'] = 'required|string|min:8|same:password';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
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
