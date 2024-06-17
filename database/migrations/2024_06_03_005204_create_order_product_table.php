<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->unsignedBigInteger('store_id');

            $table->integer('quantity')->default(1);
            $table->json('variations')->nullable();
            $table->decimal('price', 10, 2);
//            $table->timestamps();


            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');

            $table->unique(['order_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_product');
    }
};
