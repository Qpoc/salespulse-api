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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->string('product_variant_id')->unique();
            $table->string('product_id');
            $table->unsignedBigInteger('variant_label_id');
            $table->decimal('price', 10, 2);
            $table->timestamps();
            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('variant_label_id')->references('id')->on('variant_labels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
