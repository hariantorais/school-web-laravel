<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;

new class extends Component {
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    #[Layout('layouts.guest')]
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];
    }

    public function login()
    {
        $this->validate();

        // Mencoba autentikasi user
        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ]);
        }

        // Regenerasi session demi keamanan
        session()->regenerate();

        // Alihkan langsung ke halaman form postingan admin
        return redirect()->intended(route('admin.dashboard.index'));
    }
}; ?>

<div class="bg-white py-8 px-6 shadow-sm border border-slate-200 rounded-xl sm:px-10">

    <form wire:submit.prevent="login" class="space-y-5">

        <div>
            <label for="email" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                Alamat Email Resmi
            </label>
            <input id="email" type="email" wire:model="email" placeholder="nama@sekolah.sch.id"
                class="w-full px-3.5 py-3 sm:py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-700 transition-all appearance-none">
            @error('email')
                <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                Password
            </label>
            <input id="password" type="password" wire:model="password" placeholder="••••••••"
                class="w-full px-3.5 py-3 sm:py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-700 transition-all appearance-none">
            @error('password')
                <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember" type="checkbox" wire:model="remember"
                    class="h-4 w-4 text-teal-700 focus:ring-teal-500/20 border-slate-300 rounded cursor-pointer">
                <label for="remember" class="ml-2 block text-xs sm:text-sm text-slate-600 cursor-pointer select-none">
                    Ingat perangkat ini
                </label>
            </div>
        </div>

        <div>
            <button type="submit" wire:loading.attr="disabled"
                class="w-full inline-flex items-center justify-center gap-2 bg-teal-700 hover:bg-teal-800 text-white font-semibold text-sm px-5 py-3 sm:py-2.5 rounded-lg transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 active:scale-[0.99]">
                <span wire:loading.remove>Masuk ke Panel</span>
                <span wire:loading
                    class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full"></span>
                <span wire:loading>Memverifikasi...</span>
            </button>
        </div>

    </form>

</div>
