<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mountains', function (Blueprint $table) {
            $table->string('safety_status', 32)->default('open')->after('status');
            $table->text('safety_note')->nullable()->after('safety_status');
        });
    }

    public function down(): void
    {
        Schema::table('mountains', function (Blueprint $table) {
            $table->dropColumn(['safety_status', 'safety_note']);
        });
    }
};
