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
    Schema::create('enrollment_applications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->json('course_ids'); // Storing as JSON array
        $table->string('year_level')->nullable();
        $table->text('notes')->nullable();
        $table->json('files')->nullable(); // JSON for file paths
        $table->json('parent_info')->nullable();
        $table->json('student_info')->nullable();
        $table->enum('status', ['submitted', 'approved', 'rejected', 'withdrawn'])->default('submitted');
        $table->dateTime('processed_at')->nullable();
        $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamps(); // covers submitted_at (created_at)
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollment_applications');
    }
};
