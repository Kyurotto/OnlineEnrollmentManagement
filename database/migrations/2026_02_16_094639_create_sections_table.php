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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('academic_year'); // Stores "2025-2026"
            // Links to the courses table (for Program names like BSIS)
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('section_name');  // Stores the full name like "BSIS-1A"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
