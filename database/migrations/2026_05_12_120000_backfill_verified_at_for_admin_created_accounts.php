<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Admin-created accounts (tour guides and admins) don't go through the OTP
 * registration flow. Before we started stamping `email_verified_at` at
 * creation time, those rows were saved with NULL, which blocked login.
 * Backfill any surviving rows so existing admins and tour guides can sign in.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->whereIn('role', ['admin', 'tour_guide'])
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => now()]);
    }

    public function down(): void
    {
        // Intentionally a no-op: we cannot reliably identify which rows were
        // backfilled without tracking extra state, and reverting this would
        // lock real users out of their accounts.
    }
};
