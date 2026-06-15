<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Livewire\Forms\DonationForm;
use App\Services\DonationService;
use Livewire\Attributes\{On};

new class extends Component {
    use WithFileUploads;

    // Daftarkan Form Object sebagai satu-satunya state utama form
    public DonationForm $form;

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

            // Kirim data ke browser. Jika value masih dirasa lambat terisi,
            // setDonation pastikan sudah mengisi properti form->description secara eksplisit
            $this->dispatch('fill-trix-form_description', content: $this->form->description);
        }
    }
    /**
     * Proses Penyimpanan Data: Menyerahkan eksekusi penuh ke Form Object
     */
    public function save()
    {
        $this->form->validate();
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

    <form wire:submit.prevent="save" class="space-y-5">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">

            <x-form.image-upload label="Banner Kampanye" name="form.image" :modelId="$this->form->id"
                modelClass="App\Models\Donation" />

            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">

                <div class="sm:col-span-2 lg:col-span-1">
                    <x-form.input label="Nama / Judul Program Donasi" name="form.title"
                        placeholder="Contoh: Pengadaan Komputer Laboratorium Siswa Juara" />
                </div>

                <x-form.currency label="Target Pendanaan (IDR)" name="form.target_amount" placeholder="0" />

                <x-form.input label="Batas Akhir Penggalangan (Opsional)" name="form.end_date" type="date" />

            </div>
        </div>

        <x-form.trix label="Deskripsi Narasi & Urgensi Program" name="form.description"
            placeholder="Tuliskan latar belakang mengapa program donasi ini sangat mendesak untuk segera diwujudkan..." />

        <x-form.checkbox label="Tampilkan program ini di baris rekomendasi utama (Featured Program)"
            name="form.is_featured" />

        <x-form.modal-submit-button />
    </form>
</div>
