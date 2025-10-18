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
                // only add the foreign key if orders table exists to avoid FK errors during local dev
                if (Schema::hasTable('orders')) {
                    $table->foreignId('order_id')->constrained()->cascadeOnDelete();
                } else {
                    $table->unsignedBigInteger('order_id')->nullable();
                }
                $table->string('method'); // ví dụ: cod, vnpay, momo
                $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
                $table->string('transaction_id')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};