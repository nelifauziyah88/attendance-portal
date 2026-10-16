<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prizes', function (Blueprint $table) {
            $table->integer('current_stock')->default(0)->after('stock');
        });

        Schema::table('lucky_spins', function (Blueprint $table) {
            $table->string('winner')->nullable()->after('prize_id');
        });
    }

    public function down(): void
    {
        Schema::table('prizes', function (Blueprint $table) {
            $table->dropColumn('current_stock');
        });

        Schema::table('lucky_spins', function (Blueprint $table) {
            $table->dropColumn('winner');
        });
    }
};