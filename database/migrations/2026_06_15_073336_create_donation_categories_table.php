<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donation_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50); // Pendidikan, Mendesak, dll
            $table->string('slug', 60)->unique(); // pendidikan, mendesak
            $table->string('icon', 50)->nullable(); // nama icon heroicons: 'academic-cap'
            $table->text('description')->nullable();
            $table->string('color', 20)->default('#6B7280'); // hex buat badge: #EF4444
            $table->unsignedInteger('sort_order')->default(0); // urutan di filter
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_categories');
    }
};
