<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            // Add new combined field for Purok/Block/Lot/Village
            if (!Schema::hasColumn('enrollments', 'prk_blk_lot_vill')) {
                $table->string('prk_blk_lot_vill')->nullable()->after('address_full');
            }

            // Note: Keeping house_no and street columns for backward compatibility
            // but they are no longer used in new forms
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            if (Schema::hasColumn('enrollments', 'prk_blk_lot_vill')) {
                $table->dropColumn('prk_blk_lot_vill');
            }
        });
    }
};
