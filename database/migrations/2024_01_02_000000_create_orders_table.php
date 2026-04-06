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
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('product_name');
            $table->decimal('quantity', 5, 2);
            $table->decimal('price_per_unit', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->string('payment_method'); // cash, transfer, qris, etc
            $table->string('status')->default('pending'); // pending, selesai, cancel
            $table->text('notes')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email');
            $table->string('delivery_address');
            $table->timestamp('order_time')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('status');
            $table->index('created_at');
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
