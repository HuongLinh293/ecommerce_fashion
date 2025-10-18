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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('price');
            $table->bigInteger('original_price')->nullable();
            $table->string('image')->nullable();
            $table->string('category')->nullable();
            $table->integer('discount')->default(0);
            $table->string('type')->nullable(); // Dùng type để thay thế subcategory
            $table->json('colors')->nullable();
            $table->json('sizes')->nullable();
            $table->text('description')->nullable();
            $table->string('material')->nullable();
            $table->json('gallery')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};