<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Hapus tabel users di pgsql_portal DULU jika sudah ada dari eksekusi sebelumnya
        Schema::connection('pgsql_portal')->dropIfExists('users');

        // 2. Buat tabel users di koneksi remote pgsql_portal
        Schema::connection('pgsql_portal')->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('badge_id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->string('role')->default('EMPLOYEE');
            $table->timestamps();
        });

        // 3. Tabel Invitations (Daftar Peserta Undangan di DB Utama)
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->string('badge_id', 50)->unique();
            $table->timestamps();

            $table->index('badge_id');
        });

        // 4. Tabel Confirmations (Konfirmasi RSVP Pra-Event di DB Utama)
        Schema::create('confirmations', function (Blueprint $table) {
            $table->id();
            $table->string('badge_id', 50)->unique();
            $table->boolean('is_attending')->default(true);
            $table->timestamp('confirmed_at')->useCurrent();
            $table->timestamps();

            $table->index('badge_id');
        });

        // 5. Tabel Attendances (Presensi Check-in Hari H di DB Utama)
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('badge_id', 50)->unique();
            $table->timestamp('check_in_at')->useCurrent();
            $table->timestamps();

            $table->index('badge_id');
        });

        // 6. Tabel Prizes (Stok & Jenis Hadiah Doorprize di DB Utama)
        Schema::create('prizes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->integer('stock')->default(0);
            $table->string('image', 255)->nullable();
            $table->timestamps();
        });

        // 7. Tabel Lucky Spins (Pemenang Undian di DB Utama)
        Schema::create('lucky_spins', function (Blueprint $table) {
            $table->id();
            $table->string('badge_id', 50)->unique();
            $table->foreignId('prize_id')->constrained('prizes')->cascadeOnDelete();
            $table->timestamp('won_at')->useCurrent();
            $table->timestamps();

            $table->index('badge_id');
        });
    }

    public function down(): void
    {
        Schema::connection('pgsql_portal')->dropIfExists('users');
        Schema::dropIfExists('lucky_spins');
        Schema::dropIfExists('prizes');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('confirmations');
        Schema::dropIfExists('invitations');
    }
};