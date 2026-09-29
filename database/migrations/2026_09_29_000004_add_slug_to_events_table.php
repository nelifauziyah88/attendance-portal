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
        Schema::table('events', function (Blueprint $table) {
            $table->string('slug', 50)->nullable()->unique();
        });

        DB::table('events')->whereNull('slug')->orderBy('id')->get(['id', 'name'])
            ->each(fn (object $event) => DB::table('events')->where('id', $event->id)->update([
                'slug' => Str::limit(Str::slug($event->name), 40, '').'-'.$event->id,
            ]));

        Schema::table('events', function (Blueprint $table) {
            $table->string('slug', 50)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
