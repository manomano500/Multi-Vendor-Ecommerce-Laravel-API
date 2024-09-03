<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
//                $table->unsignedBigInteger('store_id');
            $table->enum('status', ['pending','in_stock', 'cancelled', ])->default('pending');

            $table->integer('quantity')->default(1);
            $table->json('variations')->nullable();
            $table->decimal('price', 10, 3);
//            $table->timestamps();
$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');


//            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');

//            $table->unique(['order_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_product');
    }
};
