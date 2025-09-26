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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('status'); // 'pending', 'completed', 'failed'
            $table->string('provider')->nullable(); // 'stripe', 'mercadopago', etc.
            $table->string('transaction_id')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
