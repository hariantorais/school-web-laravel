<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();

            // Informasi Dasar
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();

            // Visi & Misi
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->text('motto')->nullable();
            $table->string('tagline')->nullable();

            // Deskripsi & Sejarah
            $table->text('description')->nullable();
            $table->longText('history')->nullable();

            // Kontak
            $table->text('address')->nullable();
            $table->string('email')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('whatsapp_2')->nullable();

            // Media Sosial
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->string('twitter')->nullable();
            $table->string('tiktok')->nullable();

            // Website
            $table->string('website')->nullable();

            // Meta SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            // Pengaturan
            $table->string('timezone')->default('Asia/Jakarta');
            $table->string('locale')->default('id');
            $table->boolean('is_active')->default(true);

            // Informasi Tambahan
            $table->year('established_year')->nullable();
            $table->string('accreditation')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
