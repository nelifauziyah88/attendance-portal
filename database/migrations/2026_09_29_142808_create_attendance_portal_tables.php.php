<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
        });

        Schema::create('master_attendance', function (Blueprint $table) {
            $table->id();
            $table->string('badge_id', 50)->unique();
            $table->string('name');
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->string('project')->nullable();
            $table->string('company')->nullable();
            $table->boolean('is_manager')->default(false);
            $table->timestamps();
        });

        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->string('badge_id', 50)->unique();
            $table->timestamps();
            $table->foreign('badge_id')->references('badge_id')->on('master_attendance')->cascadeOnDelete();
        });

        Schema::create('confirmations', function (Blueprint $table) {
            $table->id();
            $table->string('badge_id', 50)->unique();
            $table->boolean('is_attending')->default(true);
            $table->timestamp('confirmed_at')->useCurrent();
            $table->timestamps();
            $table->foreign('badge_id')->references('badge_id')->on('master_attendance')->cascadeOnDelete();
        });

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('badge_id', 50)->unique();
            $table->timestamp('check_in_at')->useCurrent();
            $table->timestamps();
            $table->foreign('badge_id')->references('badge_id')->on('master_attendance')->cascadeOnDelete();
        });

        Schema::create('prizes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->integer('stock')->default(0);
            $table->string('image', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('lucky_spins', function (Blueprint $table) {
            $table->id();
            $table->string('badge_id', 50)->unique();
            $table->foreignId('prize_id')->constrained('prizes')->cascadeOnDelete();
            $table->timestamp('won_at')->useCurrent();
            $table->timestamps();
            $table->foreign('badge_id')->references('badge_id')->on('master_attendance')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lucky_spins');
        Schema::dropIfExists('prizes');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('confirmations');
        Schema::dropIfExists('invitations');
        Schema::dropIfExists('master_attendance');
        Schema::dropIfExists('users');
    }
};