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
        Schema::table('user_details', function (Blueprint $table) {
            $table->dropColumn('age');
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->dropColumn('street');
            $table->dropColumn('city');
            $table->dropColumn('state');
            $table->integer('age')->nullable();
        });
    }
};
