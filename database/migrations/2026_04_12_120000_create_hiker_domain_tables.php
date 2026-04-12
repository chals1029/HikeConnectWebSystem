<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mountains', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 64)->unique();
            $table->string('name');
            $table->string('short_description', 512)->nullable();
            $table->string('location');
            $table->string('difficulty', 32);
            $table->decimal('rating', 2, 1)->default(5.0);
            $table->string('image_path');
            $table->string('status', 16)->default('open');
            $table->string('elevation_label', 32);
            $table->unsignedSmallInteger('elevation_meters');
            $table->string('duration_label', 32);
            $table->string('trail_type_label', 64);
            $table->string('best_time_label', 64);
            $table->text('full_description');
            $table->string('jumpoff_name');
            $table->string('jumpoff_address');
            $table->string('jumpoff_meeting_time', 32);
            $table->text('jumpoff_notes')->nullable();
            $table->decimal('jumpoff_lat', 10, 7);
            $table->decimal('jumpoff_lng', 10, 7);
            $table->decimal('summit_lat', 10, 7);
            $table->decimal('summit_lng', 10, 7);
            $table->decimal('open_meteo_lat', 10, 7)->nullable();
            $table->decimal('open_meteo_lng', 10, 7)->nullable();
            $table->json('gear')->nullable();
            $table->json('trail_plan')->nullable();
            $table->json('trail_gear_list')->nullable();
            $table->string('emergency_contact', 64)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('tour_guides', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 64)->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('specialty', 128);
            $table->string('phone', 32);
            $table->unsignedTinyInteger('experience_years');
            $table->string('status', 32);
            $table->foreignId('mountain_id')->nullable()->constrained('mountains')->nullOnDelete();
            $table->string('avatar_gradient', 512);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('hike_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mountain_id')->constrained('mountains')->cascadeOnDelete();
            $table->foreignId('tour_guide_id')->constrained('tour_guides')->cascadeOnDelete();
            $table->date('hike_on');
            $table->unsignedTinyInteger('hikers_count');
            $table->text('notes')->nullable();
            $table->string('status', 32)->default('pending');
            $table->unsignedTinyInteger('rating')->nullable();
            $table->text('review_text')->nullable();
            $table->decimal('duration_hours', 5, 1)->nullable();
            $table->timestamps();
        });

        Schema::create('packing_items', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 64)->unique();
            $table->string('category', 64);
            $table->string('label');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('community_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author_name', 100);
            $table->string('author_initials', 8);
            $table->text('body');
            $table->foreignId('mountain_id')->nullable()->constrained('mountains')->nullOnDelete();
            $table->string('avatar_gradient', 512)->nullable();
            $table->timestamps();
        });

        Schema::create('mountain_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reviewer_name', 100);
            $table->unsignedTinyInteger('rating');
            $table->text('body');
            $table->foreignId('mountain_id')->constrained('mountains')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('bio');
        });
        Schema::dropIfExists('mountain_reviews');
        Schema::dropIfExists('community_posts');
        Schema::dropIfExists('packing_items');
        Schema::dropIfExists('hike_bookings');
        Schema::dropIfExists('tour_guides');
        Schema::dropIfExists('mountains');
    }
};
