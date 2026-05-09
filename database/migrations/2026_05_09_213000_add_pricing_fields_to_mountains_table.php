<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mountains', function (Blueprint $table) {
            $table->decimal('registration_fee_per_person', 10, 2)->default(0)->after('trail_gear_list');
            $table->decimal('environmental_fee_per_person', 10, 2)->default(0)->after('registration_fee_per_person');
            $table->decimal('local_fee_per_person', 10, 2)->default(0)->after('environmental_fee_per_person');
            $table->decimal('guide_fee_per_person', 10, 2)->default(0)->after('local_fee_per_person');
            $table->decimal('guide_fee_per_group', 10, 2)->default(0)->after('guide_fee_per_person');
            $table->string('pricing_source_note', 255)->nullable()->after('guide_fee_per_group');
            $table->date('pricing_last_verified_on')->nullable()->after('pricing_source_note');
        });

        DB::table('mountains')->where('slug', 'batulao')->update([
            'registration_fee_per_person' => 0,
            'environmental_fee_per_person' => 0,
            'local_fee_per_person' => 60,
            'guide_fee_per_person' => 500,
            'guide_fee_per_group' => 0,
            'pricing_source_note' => 'Batulao public guides (2024-2026): guide around PHP 500/person + toll gates around PHP 30/gate.',
            'pricing_last_verified_on' => '2026-05-09',
        ]);

        DB::table('mountains')->where('slug', 'pico')->update([
            'registration_fee_per_person' => 0,
            'environmental_fee_per_person' => 200,
            'local_fee_per_person' => 0,
            'guide_fee_per_person' => 0,
            'guide_fee_per_group' => 500,
            'pricing_source_note' => 'Pico references (2023-2026): eco/entrance around PHP 200/person + guide around PHP 500/group.',
            'pricing_last_verified_on' => '2026-05-09',
        ]);

        DB::table('mountains')->where('slug', 'talamitam')->update([
            'registration_fee_per_person' => 50,
            'environmental_fee_per_person' => 0,
            'local_fee_per_person' => 0,
            'guide_fee_per_person' => 0,
            'guide_fee_per_group' => 500,
            'pricing_source_note' => 'Talamitam references (2024-2026): registration around PHP 40-50/person + guide around PHP 500/group.',
            'pricing_last_verified_on' => '2026-05-09',
        ]);
    }

    public function down(): void
    {
        Schema::table('mountains', function (Blueprint $table) {
            $table->dropColumn([
                'registration_fee_per_person',
                'environmental_fee_per_person',
                'local_fee_per_person',
                'guide_fee_per_person',
                'guide_fee_per_group',
                'pricing_source_note',
                'pricing_last_verified_on',
            ]);
        });
    }
};
