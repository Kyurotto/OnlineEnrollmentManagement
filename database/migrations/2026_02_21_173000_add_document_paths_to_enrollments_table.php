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
        Schema::table('enrollments', function (Blueprint $table) {
            $table->string('form_138_path')->nullable();
            $table->string('good_moral_path')->nullable();
            $table->string('psa_path')->nullable();
            $table->string('id_picture_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn(['form_138_path', 'good_moral_path', 'psa_path', 'id_picture_path']);
        });
    }
};
