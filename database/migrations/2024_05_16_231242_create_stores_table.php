<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->unsignedBigInteger('user_id')->unique();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('address');

            $table->string('image')->nullable();
            $table->string('phone', 20)->unique()->nullable();
            $table->string('email')->unique()->nullable();
            // E.164 format max length is 15, but keeping 20 for flexibility
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
