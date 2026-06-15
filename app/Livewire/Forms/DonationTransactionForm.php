<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Services\DonationTransactionService;
use Illuminate\Support\Str;
use App\Models\DonationTransaction;

class DonationTransactionForm extends Form
{
    public ?int $id = null;
    public string $donation_id = '';
    public string $donor_name = 'Hamba Allah';
    public string $donor_email = '';
    public string $donor_phone = '';
    public mixed $amount = 0;
    public string $payment_method = 'cash'; // Default untuk entri admin
    public string $notes = '';

    /**
     * Aturan validasi input kas manual admin
     */
    public function rules(): array
    {
        return [
            'donation_id'    => ['required', 'exists:donations,id'],
            'donor_name'     => ['required', 'string', 'min:3', 'max:150'],
            'donor_email'    => ['nullable', 'email', 'max:100'],
            'donor_phone'    => ['nullable', 'max:100'],
            'amount'         => ['required', 'numeric', 'min:10000'],
            'payment_method' => ['required', 'string'],
            'notes'          => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Memproses penyimpanan transaksi manual via Service Layer
     */
    public function store(): array
    {
        $service = app(DonationTransactionService::class);

        $referenceCode = date('Ymd') . '-' . Str::upper(Str::random(5));

        $payload = [
            'donation_id'    => (int) $this->donation_id,
            'donor_name'     => strip_tags(trim($this->donor_name)),
            'donor_email'    => $this->donor_email ?: null,
            'donor_phone'    => $this->donor_phone ?: null,
            'amount'         => (float) $this->amount,
            'payment_method' => $this->payment_method,
            'payment_status' => 'success', // Karena ditulis admin, status langsung sah (success)
            'reference_code' => $referenceCode,
            'notes'          => $this->notes,
        ];

        // Daftarkan rekaman ke database melalui perluasan method di Service Layer
        $service->createManualTransaction($payload);

        $this->clear();

        return [
            'type'    => 'success',
            'message' => 'Setoran donasi manual berhasil dibukukan ke dalam sistem!'
        ];
    }

    public function setTransaction(DonationTransaction $transaction): void
    {
        $this->id = $transaction->id;
        $this->donation_id = (string) $transaction->donation_id;
        $this->donor_name = $transaction->donor_name;
        $this->donor_email = $transaction->donor_email ?? '';
        $this->donor_phone = $transaction->donor_phone ?? '';
        $this->amount = $transaction->amount;
        $this->payment_method = $transaction->payment_method ?? 'manual';
        $this->notes = $transaction->notes ?? '';
    }

    public function clear(): void
    {
        $this->reset();
    }
}
