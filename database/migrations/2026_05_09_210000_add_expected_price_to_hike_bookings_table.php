<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hike_bookings', function (Blueprint $table) {
            $table->decimal('expected_price', 10, 2)->nullable()->after('checked_out_at');
        });
    }

    public function down(): void
    {
        Schema::table('hike_bookings', function (Blueprint $table) {
            $table->dropColumn('expected_price');
        });
    }
};
