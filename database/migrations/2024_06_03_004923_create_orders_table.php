<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('order_total', 10, 2);
            $table->enum('status', ['pending', 'processing', 'ready_for_shipment', 'shipped','delivered', 'cancelled'])->default('pending');

            $table->string('payment_method')->default('on delivery');
$table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('city');
            $table->string('phone')->nullable();
            $table->string('shipping_address');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
