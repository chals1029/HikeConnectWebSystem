<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mountain_reviews', function (Blueprint $table) {
            $table->foreignId('hike_booking_id')
                ->nullable()
                ->unique()
                ->after('mountain_id')
                ->constrained('hike_bookings')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('mountain_reviews', function (Blueprint $table) {
            $table->dropConstrainedForeignId('hike_booking_id');
        });
    }
};
