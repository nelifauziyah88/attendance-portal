<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DROP INDEX IF EXISTS idx_users_badge_id');
        DB::statement('DROP INDEX IF EXISTS idx_lucky_spin_attendance');
    }

    public function down(): void
    {
        DB::statement('CREATE INDEX IF NOT EXISTS idx_users_badge_id ON users (badge_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_lucky_spin_attendance ON lucky_spin (attendance_id)');
    }
};
