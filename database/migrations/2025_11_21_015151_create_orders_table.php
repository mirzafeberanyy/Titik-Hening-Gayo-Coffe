<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Order Utama
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Contoh: ORD-20240101-001
            
            // Data User (Bisa Guest / Login)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('shipping_address');
            
            // Keuangan
            $table->decimal('total_price', 15, 2);
            $table->string('payment_status')->default('pending'); // pending, paid, failed
            $table->string('order_status')->default('new'); // new, processing, shipping, completed, cancelled
            
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel Rincian Item
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            // Jika pakai varian, ini akan terisi. Jika tidak, null.
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete(); 
            
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2); // Harga saat transaksi terjadi
            $table->decimal('total_price', 15, 2); // qty * unit_price
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};