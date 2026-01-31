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
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->foreignId('state_region_id')->nullable()->after('address')->constrained()->nullOnDelete();
            $table->foreignId('township_id')->nullable()->after('state_region_id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->dropForeign(['state_region_id']);
            $table->dropForeign(['township_id']);
        });
    }
};
