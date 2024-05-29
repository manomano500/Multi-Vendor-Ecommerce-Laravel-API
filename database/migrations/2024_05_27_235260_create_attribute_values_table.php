<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('value_id');
            $table->timestamps();

            $table->unique(['attribute_id', 'value_id']);
            $table->foreign('value_id')->references('id')->on('values')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('values');
    }
};
