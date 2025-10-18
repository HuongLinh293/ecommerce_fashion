<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    if (Schema::hasTable('orders') && !Schema::hasColumn('orders','customer_id')) {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable()->after('id');
            if (Schema::hasTable('customers')) {
                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            }
        });
    }
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropForeign(['customer_id']);
        $table->dropColumn('customer_id');
    });
}

};