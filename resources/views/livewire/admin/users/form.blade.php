<?php

use Livewire\Volt\Component;
use App\Livewire\Forms\UserForm as UserFormObject;
use App\Models\User;
use Livewire\Attributes\On;

new class extends Component {
    public UserFormObject $form;

    #[On('open-modal')]
    public function loadData(?int $id = null)
    {
        $this->form->clear();

        if ($id) {
            $user = User::findOrFail($id);
            $this->form->setUser($user);
        }
    }

    public function save()
    {
        $this->form->validate();

        try {
            $message = $this->form->store();

            $this->dispatch('toast', type: 'success', message: $message);
            $this->dispatch('close-modal', name: 'modal-user');
            $this->dispatch('refresh-table');
            $this->form->clear();
        } catch (\Throwable $e) {
            \Log::error('Kegagalan simpan data User: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            $this->dispatch('toast', type: 'error', message: 'Terjadi kegagalan sistem saat memproses data pengguna.');
        }
    }
};

?>

<x-ui.modal name="modal-user" title="{{ $form->user ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}" loadAction="loadData">
    <form wire:submit.prevent="save" class="space-y-4">

        {{-- Error Alert --}}
        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 p-3 rounded-xl mb-4">
                <p class="text-xs font-bold text-rose-800">Ada Error Validasi:</p>
                <ul class="list-disc pl-4 text-[11px] text-rose-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <x-form.input label="Nama Lengkap" name="form.name" placeholder="Masukkan nama" />
        <x-form.input label="Email" name="form.email" type="email" placeholder="Masukkan email" />

        {{-- Password --}}
        <x-form.input label="{{ $form->user ? 'Password (Kosongkan jika tidak diubah)' : 'Password' }}"
            name="form.password" type="password" placeholder="Minimal 8 karakter" />

        {{-- Password Confirmation --}}
        <x-form.input label="Konfirmasi Password" name="form.password_confirmation" type="password"
            placeholder="Ulangi password" />

        <x-form.modal-submit-button modalName="modal-user" />
    </form>
</x-ui.modal>
