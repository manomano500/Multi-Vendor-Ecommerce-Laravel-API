<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_attribute_id')->constrained()->onDelete('cascade');
            $table->foreignId('value_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->integer('quantity')->default(0);

            $table->unique(['product_attribute_id', 'value_id']);        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attribute_values');
    }
};
