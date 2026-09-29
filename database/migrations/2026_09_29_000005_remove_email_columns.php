<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn(['code', 'sent_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->dropColumn('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email', 150)->nullable()->unique();
        });

        Schema::table('invitations', function (Blueprint $table) {
            $table->string('code', 32)->nullable()->unique();
            $table->timestampTz('sent_at')->nullable();
        });
    }
};
