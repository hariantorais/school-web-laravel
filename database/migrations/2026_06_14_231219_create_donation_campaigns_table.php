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
        Schema::create('donation_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->longText('description');
            $table->decimal('target_amount', 12, 2); // Contoh: 999.999.999,00
            $table->decimal('current_amount', 12, 2)->default(0); // Total dana terkumpul sementara
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->timestamp('end_date')->nullable(); // Batas waktu pencarian dana
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_campaigns');
    }
};
