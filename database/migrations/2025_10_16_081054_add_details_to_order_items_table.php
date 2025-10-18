<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'product_name')) {
                $table->string('product_name')->nullable();
            }
            if (!Schema::hasColumn('order_items', 'product_image')) {
                $table->string('product_image')->nullable();
            }
            if (!Schema::hasColumn('order_items', 'size')) {
                $table->string('size')->nullable();
            }
            if (!Schema::hasColumn('order_items', 'color')) {
                $table->string('color')->nullable();
            }
            if (!Schema::hasColumn('order_items', 'subtotal')) {
                $table->integer('subtotal')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['product_name', 'product_image', 'size', 'color', 'subtotal']);
        });
    }
};