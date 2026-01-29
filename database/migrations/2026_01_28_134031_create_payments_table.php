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
        Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('application_id')->nullable()->constrained('enrollment_applications')->nullOnDelete();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->decimal('amount', 10, 2);
        $table->enum('payment_status', ['pending', 'completed', 'failed'])->default('pending');
        $table->dateTime('payment_date')->useCurrent();
        $table->string('transaction_id')->nullable();
        $table->string('proof')->nullable(); // File path
        $table->text('notes')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
