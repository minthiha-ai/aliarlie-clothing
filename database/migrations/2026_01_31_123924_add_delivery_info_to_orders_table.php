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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('state_region_id')->nullable()->after('customer_id')->constrained()->nullOnDelete();
            $table->foreignId('township_id')->nullable()->after('state_region_id')->constrained()->nullOnDelete();
            $table->decimal('delivery_fees', 10, 2)->default(0)->after('township_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['state_region_id']);
            $table->dropForeign(['township_id']);
            $table->dropColumn('delivery_fees');
        });
    }
};
