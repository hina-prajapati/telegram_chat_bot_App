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
        Schema::create('telegram_user_states', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('telegram_user_id'); // No foreign key constraint

            // $table->foreignId('telegram_user_id')->constrained('telegram_users')->onDelete('cascade');
        $table->string('current_step')->nullable();
        $table->json('answers')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_user_states');
    }
};
