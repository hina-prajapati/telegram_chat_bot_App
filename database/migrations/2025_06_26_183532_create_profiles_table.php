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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('telegram_user_id')->unique();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('marital_status')->nullable();
            $table->date('dob')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('mother_tongue')->nullable();
            $table->string('religion')->nullable();
            $table->string('caste')->nullable();
            $table->string('education_level')->nullable();
            $table->string('education_field')->nullable();
            $table->string('working_sector')->nullable();
            $table->string('profession')->nullable();
            $table->string('phone')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('diet')->nullable();
            $table->string('smoking')->nullable();
            $table->string('drinking')->nullable();
            $table->string('height')->nullable();
            $table->string('body_type')->nullable();
            $table->string('skin_tone')->nullable();
            $table->string('gender')->nullable();
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
