<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->unsignedInteger('quantity')->default(0);
            $table->foreignId('category_id');
            $table->json('images')->nullable(); // JSON column for images

            $table->foreignId('store_id');

            $table->double('price');

            $table->enum('status', ['active', 'out_of_stock'])->default('active');

            $table->softDeletes();
            $table->timestamps();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

        });



    }


    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
