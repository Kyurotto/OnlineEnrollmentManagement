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
        Schema::table('users', function (Blueprint $table) {
        $table->string('username')->unique()->after('id');
        $table->string('first_name')->nullable()->after('password');
        $table->string('middle_name')->nullable()->after('first_name');
        $table->string('last_name')->nullable()->after('middle_name');
        $table->enum('role', ['student', 'admin', 'registrar', 'cashier'])->default('student')->after('email');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
