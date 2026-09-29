<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id()->generatedAs()->always();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('location', 200);
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->integer('capacity');
            $table->timestampTz('created_at')->nullable()->useCurrent();
            $table->timestampTz('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
