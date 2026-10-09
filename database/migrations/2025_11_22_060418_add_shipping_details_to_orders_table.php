<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('total_weight')->default(0)->after('total_price'); // Berat (gram)
            $table->decimal('shipping_cost', 12, 2)->default(0)->after('total_weight'); // Biaya Ongkir
            $table->string('shipping_courier')->nullable()->after('shipping_cost'); // jne, pos, tiki
            $table->string('shipping_service')->nullable()->after('shipping_courier'); // OKE, REG, YES
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['total_weight', 'shipping_cost', 'shipping_courier', 'shipping_service']);
        });
    }
};
