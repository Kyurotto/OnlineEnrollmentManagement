<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // e.g., 'payment_approved', 'payment_rejected', 'application_approved', 'application_rejected', 'clearance_approved', 'clearance_revoked'
            $table->string('target_type'); // e.g., 'Payment', 'Enrollment', 'Enrollment'
            $table->unsignedBigInteger('target_id'); // The ID of the target (payment_id, enrollment_id, etc.)
            $table->text('description')->nullable(); // Additional details
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};