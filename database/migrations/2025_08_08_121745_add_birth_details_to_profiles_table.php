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
        Schema::table('profiles', function (Blueprint $table) {
            $table->time('birth_time')->nullable()->after('dob');
            $table->string('birth_place')->nullable()->after('birth_time');
            $table->string('native_place')->nullable()->after('birth_place');
             $table->enum('terms_and_conditions', ['accepted', 'rejected'])->nullable()->after('native_place');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
              $table->dropColumn(['birth_time', 'birth_place', 'native_place', 'terms_and_conditions']);
        });
    }
};
