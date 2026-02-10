<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    if (!Schema::hasColumn('enrollments', 'course_id')) {
        Schema::table('enrollments', function (Blueprint $table) {
            // Add the missing column
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            // Or if you are using older Laravel versions:
            // $table->unsignedBigInteger('course_id');
            // $table->foreign('course_id')->references('id')->on('courses');
        });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('enrollments', function (Blueprint $table) {
        $table->dropForeign(['course_id']);
        $table->dropColumn('course_id');
    });
}
};
