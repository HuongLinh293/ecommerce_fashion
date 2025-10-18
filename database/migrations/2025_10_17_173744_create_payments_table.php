<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->string('transaction_id')->unique();
                if (Schema::hasTable('orders')) {
                    $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
                } else {
                    $table->unsignedBigInteger('order_id')->nullable();
                }
                $table->decimal('amount', 15, 2);
                $table->enum('method', ['cod', 'momo', 'vnpay'])->default('cod');
                $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};