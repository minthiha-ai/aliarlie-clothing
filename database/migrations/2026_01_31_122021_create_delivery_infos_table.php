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
        Schema::create('delivery_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_region_id')->nullable();
            $table->foreignId('township_id')->nullable();
            $table->decimal('delivery_fees', 10, 2)->default(0);
            $table->timestamps();

            $table->unique('township_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_infos');
    }
};
