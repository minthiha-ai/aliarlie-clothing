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
        Schema::create('townships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_region_id')->constrained()->cascadeOnDelete();
            $table->string('district_code', 50)->nullable();
            $table->string('code', 50);
            $table->string('name');
            $table->string('name_mmr')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['state_region_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('townships');
    }
};
