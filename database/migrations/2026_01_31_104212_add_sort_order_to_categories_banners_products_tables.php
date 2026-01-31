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
        Schema::table('categories', function (Blueprint $table): void {
            $table->unsignedInteger('sort_order')->default(99);
        });
        Schema::table('banners', function (Blueprint $table): void {
            $table->unsignedInteger('sort_order')->default(99);
        });
        Schema::table('products', function (Blueprint $table): void {
            $table->unsignedInteger('sort_order')->default(99);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table): void {
            $table->dropColumn('sort_order');
        });
        Schema::table('banners', function (Blueprint $table): void {
            $table->dropColumn('sort_order');
        });
        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn('sort_order');
        });
    }
};
