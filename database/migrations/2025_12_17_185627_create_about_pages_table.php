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
        Schema::create('about_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Tentang Titik Hening');
            $table->string('subtitle')->nullable(); // Misal: "Ketenangan dalam Setiap Tegukan"
            $table->longText('story'); // Cerita Sejarah (Rich Text)
            $table->string('main_image')->nullable(); // Foto Utama/Gedung/Kebun
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->json('stats')->nullable(); // Opsional: Simpan data statistik (misal: {"pelanggan": 1000, "cabang": 2})
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_pages');
    }
};
