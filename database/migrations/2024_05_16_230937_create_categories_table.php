<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->enum('type', ['store', 'product'])->index();  // Added to differentiate category types

            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories');
            $table->index('category_id'); // Add index for foreign key if querying frequently

        });


    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
