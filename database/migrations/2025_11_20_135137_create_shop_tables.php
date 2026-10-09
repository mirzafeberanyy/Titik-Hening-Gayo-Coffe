<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Kategori Produk
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Produk Utama
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('category_id')->constrained()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->decimal('base_price', 12, 2)->default(0); 
            $table->string('main_image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Varian Produk (Berat, Gilingan, Roast)
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name')->nullable(); 
            $table->integer('weight_grams')->default(0);
            $table->string('grind_type')->nullable(); // Beans, Coarse, Fine
            $table->string('roast_level')->nullable(); // Light, Medium, Dark
            $table->string('sku')->unique()->nullable();
            $table->integer('stock')->default(0);
            $table->decimal('price', 12, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};