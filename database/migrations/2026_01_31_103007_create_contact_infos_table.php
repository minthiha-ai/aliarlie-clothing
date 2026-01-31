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
        Schema::create('contact_infos', function (Blueprint $table) {
            $table->id();
            $table->string('address_1_title')->nullable();
            $table->text('address_1_text')->nullable();
            $table->string('address_2_title')->nullable();
            $table->text('address_2_text')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('social_facebook')->nullable();
            $table->string('social_pinterest')->nullable();
            $table->string('social_twitter')->nullable();
            $table->string('social_instagram')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_infos');
    }
};
