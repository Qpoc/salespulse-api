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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('reference_id');
            $table->string('product_variant_id');
            $table->decimal('price', 10, 2);
            $table->unsignedBigInteger('quantity');
            $table->foreign('reference_id')->references('reference_id')->on('orders');
            $table->foreign('product_variant_id')->references('product_variant_id')->on('product_variants');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
