<?php

use Livewire\Volt\Component;
use App\Livewire\Forms\TestimonialForm as TestimonialFormObject;
use App\Models\Testimonial;
use Livewire\Attributes\On;

new class extends Component {
    public TestimonialFormObject $form;

    #[On('open-modal')]
    public function loadData(?int $id = null)
    {
        $this->form->clear();
        $this->resetErrorBag();

        if ($id) {
            $testimonial = Testimonial::findOrFail($id);
            $this->form->setTestimonial($testimonial);
        }
    }

    public function save()
    {
        // 1. Eksekusi validasi di baris terdepan
        $this->form->validate();

        // 2. Kawal manipulasi database dengan try-catch
        try {
            $message = $this->form->store();

            $this->dispatch('toast', type: 'success', message: $message);
            $this->dispatch('close-modal', name: 'modal-testimonial');
            $this->dispatch('refresh-table');
            $this->form->clear();
        } catch (\Throwable $e) {
            \Log::error('Kegagalan simpan data Testimoni: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            $this->dispatch('toast', type: 'error', message: 'Terjadi kegagalan sistem saat memproses data testimoni. ' . $e->getMessage());
        }
    }

    public function removeAvatar()
    {
        $this->form->removeAvatar();
    }
};

?>

<x-ui.modal name="modal-testimonial" title="{{ $form->testimonial ? 'Edit Testimoni' : 'Tambah Testimoni Baru' }}"
    loadAction="loadData">
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form.input label="Nama Lengkap" name="form.name" placeholder="Masukkan nama" />
            <x-form.input label="Role / Posisi" name="form.role" placeholder="Contoh: Orang Tua Santri" />
        </div>

        {{-- Rating --}}
        <div>
            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                Rating
            </label>
            <div class="flex items-center gap-2">
                @for ($i = 1; $i <= 5; $i++)
                    <button type="button" wire:click="$set('form.rating', {{ $i }})"
                        class="text-2xl transition-all duration-200 hover:scale-110 focus:outline-none">
                        <span class="{{ $i <= $form->rating ? 'text-yellow-400' : 'text-slate-300' }}">★</span>
                    </button>
                @endfor
                <span class="text-sm text-slate-500 ml-2">({{ $form->rating }} dari 5)</span>
            </div>
            @error('form.rating')
                <span class="text-[10px] text-rose-600 font-bold mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        {{-- Konten --}}
        <x-form.textarea label="Konten Testimoni" name="form.content" placeholder="Tulis testimoni di sini..."
            rows="4" />


        {{-- Status & Order --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                    Status
                </label>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model="form.is_active" value="1"
                            class="w-4 h-4 text-[var(--accent-primary)]">
                        <span class="text-sm text-slate-600">Aktif</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model="form.is_active" value="0" class="w-4 h-4 text-slate-400">
                        <span class="text-sm text-slate-600">Nonaktif</span>
                    </label>
                </div>
                @error('form.is_active')
                    <span class="text-[10px] text-rose-600 font-bold mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <x-form.input label="Urutan Tampilan" name="form.order" type="number" placeholder="0" />
        </div>

        <x-form.modal-submit-button modalName="modal-testimonial" />
    </form>
</x-ui.modal>
