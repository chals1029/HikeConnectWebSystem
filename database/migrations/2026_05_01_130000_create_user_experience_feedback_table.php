<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_experience_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('score', 16); // bad | okay | great
            $table->boolean('dont_show_again')->default(false);
            $table->string('context', 64)->default('hiker_dashboard_login');
            $table->timestamps();

            $table->index(['score', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_experience_feedback');
    }
};
