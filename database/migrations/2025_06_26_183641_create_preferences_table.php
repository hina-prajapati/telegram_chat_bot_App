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
        Schema::create('preferences', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('telegram_user_id')->unique();
            $table->string('partner_marital_status')->nullable();
            $table->string('partner_caste')->nullable();
            $table->integer('partner_min_age')->nullable();
            $table->integer('partner_max_age')->nullable();
            $table->string('partner_min_height')->nullable();
            $table->string('partner_max_height')->nullable();
            $table->string('partner_gender')->nullable();
            $table->string('partner_language')->nullable();
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preferences');
    }
};
