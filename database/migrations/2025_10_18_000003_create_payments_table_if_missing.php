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
                // if orders table exists, add FK; otherwise keep nullable unsignedBigInteger
                if (Schema::hasTable('orders')) {
                    $table->foreignId('order_id')->nullable()->constrained()->cascadeOnDelete();
                } else {
                    $table->unsignedBigInteger('order_id')->nullable();
                }
                $table->string('transaction_id')->nullable();
                $table->bigInteger('amount')->default(0);
                $table->string('method')->nullable();
                $table->enum('status', ['pending','completed','failed','refunded'])->default('pending');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('payments')) {
            Schema::dropIfExists('payments');
        }
    }
};
