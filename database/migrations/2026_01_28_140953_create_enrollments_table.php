<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            // Link to the user who submitted
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Course Info
            $table->string('course_code');
            $table->string('year_level');

            // Personal Info
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('birth_date');
            $table->integer('age');
            $table->string('gender');
            $table->string('religion')->nullable();
            $table->string('birthplace')->nullable();
            $table->string('email');
            $table->string('contact');

            // Address
            $table->string('address_full'); // We will combine house/street/city for simplicity

            // Parent Info
            $table->string('father_name')->nullable();
            $table->string('mother_maiden_name')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_contact')->nullable();

            // Admin Status
            $table->string('status')->default('Pending'); // Pending, Approved, Rejected
            $table->boolean('is_processed')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
