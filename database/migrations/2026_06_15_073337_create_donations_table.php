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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('slug', 180)->unique();
            $table->text('description');
            $table->string('image_path')->nullable();

            $table->decimal('target_amount', 14, 2)->default(0.00);
            $table->decimal('current_amount', 14, 2)->default(0.00);

            $table->boolean('is_active')->default(true)->index();
            $table->date('end_date')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
