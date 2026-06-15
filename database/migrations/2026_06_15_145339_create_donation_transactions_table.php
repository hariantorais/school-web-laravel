<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan Pembuatan Tabel Riwayat Donasi Masuk
     */
    public function up(): void
    {
        Schema::create('donation_transactions', function (Blueprint $table) {
            $table->id();

            /**
             * 1. RELASI KE TABEL DONATIONS (FOREIGN KEY)
             * Menggunakan cascadeOnDelete() agar jika data induk dihapus PERMANEN (forceDelete),
             * semua riwayat transksinya otomatis ikut terhapus dari database demi integritas data.
             */
            $table->foreignId('donation_id')
                ->constrained('donations')
                ->cascadeOnDelete();

            // 2. DATA IDENTITAS DONATUR
            $table->string('donor_name', 100);
            $table->string('donor_email', 100)->index();
            $table->string('donor_phone', 20)->nullable();

            // 3. NOMINAL & RECORD FINANSIAL
            // Disamakan tipenya dengan target_amount & current_amount di tabel induk (decimal 14,2)
            $table->decimal('amount', 14, 2)->default(0.00);

            $table->string('payment_method', 50)->nullable();

            // Kode unik transaksi (Misal untuk integrasi Midtrans / Gateway: INV-202606xxxx)
            $table->string('reference_code', 50)->unique();
            $table->foreignId('verified_by')->nullable()->constrained('users');

            // 4. STATUS & VALIDASI PAYMENT
            // Menggunakan enum dengan indeks agar proses kueri pencarian status berjalan secepat kilat
            $table->enum('status', ['pending', 'success', 'failed', 'expired'])
                ->default('pending')
                ->index();

            // Pesan doa atau dukungan dari donatur (opsional)
            $table->text('notes')->nullable();

            // Sifatnya opsional, jika transaksi juga membutuhkan fitur soft delete tersendiri
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Membatalkan Perubahan Tabel
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_transactions');
    }
};
