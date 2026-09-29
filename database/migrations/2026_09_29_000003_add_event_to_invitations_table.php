<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->foreignId('event_id')->nullable()->constrained('events')->cascadeOnDelete();
            $table->string('code', 32)->nullable()->unique();
            $table->timestampTz('sent_at')->nullable();
        });

        $this->assignLegacyInvitations();

        Schema::table('invitations', function (Blueprint $table) {
            $table->bigInteger('event_id')->nullable(false)->change();
            $table->string('code', 32)->nullable(false)->change();
            $table->dropUnique('invitations_user_id_key');
            $table->unique(['event_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropUnique(['event_id', 'user_id']);
            $table->unique('user_id', 'invitations_user_id_key');
            $table->dropUnique(['code']);
            $table->dropConstrainedForeignId('event_id');
            $table->dropColumn(['code', 'sent_at']);
        });
    }

    private function assignLegacyInvitations(): void
    {
        $legacyInvitationIds = DB::table('invitations')->whereNull('event_id')->orderBy('id')->pluck('id');

        if ($legacyInvitationIds->isEmpty()) {
            return;
        }

        $eventId = DB::table('events')->insertGetId([
            'name' => 'Acara Seatrium Batam',
            'description' => 'Event contoh untuk undangan yang dibuat sebelum fitur event tersedia.',
            'location' => 'Seatrium Batam',
            'event_date' => '2026-12-01',
            'start_time' => '08:00',
            'end_time' => '12:00',
            'capacity' => (int) config('invitation.capacity'),
        ]);

        foreach ($legacyInvitationIds as $invitationId) {
            DB::table('invitations')->where('id', $invitationId)->update([
                'event_id' => $eventId,
                'code' => Str::random(24),
            ]);
        }
    }
};
