<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hiker_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hike_booking_id')->nullable()->constrained('hike_bookings')->nullOnDelete();
            $table->foreignId('mountain_id')->nullable()->constrained('mountains')->nullOnDelete();
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->float('accuracy_m')->nullable();
            $table->float('altitude_m')->nullable();
            $table->float('speed_mps')->nullable();
            $table->timestamp('recorded_at')->index();
            $table->timestamps();

            $table->index(['user_id', 'recorded_at']);
            $table->index(['mountain_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hiker_locations');
    }
};
