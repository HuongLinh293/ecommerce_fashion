<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('payments') && !Schema::hasColumn('payments', 'amount')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->decimal('amount', 15, 2)->default(0)->after('transaction_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('payments') && Schema::hasColumn('payments', 'amount')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn('amount');
            });
        }
    }
};
