<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Livewire\Forms\DonationForm;
use App\Services\DonationService;
use App\Models\DonationCategory;
use Livewire\Attributes\{On, Computed};

new class extends Component {
    use WithFileUploads;

    // Daftarkan Form Object sebagai satu-satunya state utama form
    public DonationForm $form;

    /**
     * Mengambil daftar kategori donasi untuk kebutuhan select option
     */
    #[Computed]
    public function categories()
    {
        return DonationCategory::select('id', 'name')->orderBy('name')->get();
    }

    #[On('open-modal')]
    public function loadData($id = null)
    {
        // 1. Reset data awal form
        $this->form->clear();
        $this->resetErrorBag();
        $this->dispatch('trix-clear');

        // 2. Jika ID ada, berarti masuk mode EDIT
        if ($id) {
            $service = app(DonationService::class);
            $donation = $service->findById($id);

            $this->form->setDonation($donation);

            // Kirim data ke editor Trix di browser
            $this->dispatch('fill-trix-form_description', content: $this->form->description);
        }
    }

    /**
     * Proses Penyimpanan Data: Menyerahkan eksekusi penuh ke Form Object
     */
    public function save()
    {
        // Validasi dan simpan diserahkan ke Form Object internal
        try {
            $result = $this->form->store();

            $this->dispatch('refresh-table');
            $this->dispatch('close-modal', name: 'modal-form');
            $this->dispatch('toast', type: $result['type'], message: $result['message']);
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Gagal: ' . $e->getMessage());
        }
    }
}; ?>

<div class="w-full">

    {{-- Alert Ringkasan Error Validasi --}}
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

    {{-- Form Utama terikat dengan Alpine.js untuk reaktivitas tipe program --}}
    <form wire:submit.prevent="save" class="space-y-5" x-data="{ programType: @entangle('form.type') }">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">

            {{-- Komponen Upload Gambar Utama --}}
            <x-form.image-upload label="Banner Kampanye" name="form.image" :modelId="$this->form->id"
                modelClass="App\Models\Donation" />

            {{-- Kolom Input Teks Konten --}}
            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Judul Kampanye (Memenuhi Baris Grid) --}}
                <div class="sm:col-span-2">
                    <x-form.input label="Nama / Judul Program Donasi" name="form.title"
                        placeholder="Contoh: Pengadaan Komputer Laboratorium Siswa Juara" />
                </div>

                {{-- 🆕 INPUT BARU: Sifat / Tipe Program --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Sifat Program Donasi</label>
                    <select x-model="programType"
                        class="w-full text-xs rounded-lg border-slate-200 focus:border-[#10A8E5] focus:ring-[#10A8E5]/20 bg-white p-2">
                        <option value="one_time">Per Proyek</option>
                        <option value="recurring_open">Berkelanjutan</option>
                    </select>
                </div>

                {{-- 🆕 INPUT BARU: Pilihan Kategori --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kategori Donasi</label>
                    <select wire:model="form.donation_category_id"
                        class="w-full text-xs rounded-lg border-slate-200 focus:border-[#10A8E5] focus:ring-[#10A8E5]/20 bg-white p-2">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($this->categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- ⚡ AREA DINAMIS: Hanya muncul jika tipe program adalah 'one_time' --}}
                <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1 border-t border-dashed border-slate-100"
                    x-show="programType === 'one_time'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0">

                    <x-form.currency label="Target Pendanaan (IDR)" name="form.target_amount" placeholder="0" />

                    <x-form.input label="Batas Akhir Penggalangan" name="form.end_date" type="date" />
                </div>

            </div>
        </div>

        {{-- Narasi Editor Trix --}}
        <x-form.trix label="Deskripsi Narasi & Urgensi Program" name="form.description"
            placeholder="Tuliskan latar belakang mengapa program donasi ini sangat mendesak untuk segera diwujudkan..." />

        {{-- Baris Pengaturan Status Sakelar (Featured & Status Keaktifan) --}}
        <div class="flex  gap-x-6 gap-y-2 ">
            <x-form.checkbox label="Mendesak" name="form.is_featured" />

            {{-- 🆕 INPUT BARU: Status Aktif Kampanye --}}
            <x-form.checkbox label="Program {{ $form->is_active ? 'Dibuka' : 'Ditutup' }}" name="form.is_active" />
        </div>

        {{-- Tombol Submit Form --}}
        <x-form.modal-submit-button />
    </form>
</div>
