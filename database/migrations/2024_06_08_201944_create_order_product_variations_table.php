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
        Schema::create('order_product_variations', function (Blueprint $table) {
            $table->id()->autoIncrement();

$table->unsignedBigInteger('order_product_id');
            $table->foreignId('product_variation_id')->constrained()->cascadeOnDelete();


            $table->foreign('order_product_id')->references('id')->on('order_products')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product_variations');
    }
};
