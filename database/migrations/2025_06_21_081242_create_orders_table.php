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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('reference_id')->unique();
            $table->foreignId('customer_id')->constrained('users');
            $table->string('status')->default(1)->comment('1 = pending, 2 = processing, 3 = shipped, 4 = delivered, 5 = cancelled');
            $table->decimal('total_price', 10, 2)->nullable();
            $table->timestamp('order_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
