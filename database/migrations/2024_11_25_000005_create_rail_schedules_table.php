<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rail_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('train_id')->constrained('trains')->onDelete('cascade');
            $table->string('schedule_code')->unique(); // unique trip identifier
            $table->foreignId('origin_terminal_id')->constrained('terminals');
            $table->foreignId('destination_terminal_id')->constrained('terminals');
            $table->dateTime('departure_time');
            $table->dateTime('arrival_time');
            $table->string('status'); // 'scheduled', 'departed', 'in-transit', 'arrived', 'delayed'
            $table->integer('expected_teus'); // expected TEUs for this trip
            $table->integer('loaded_teus')->default(0); // actual loaded TEUs
            $table->json('special_instructions')->nullable(); // any special handling instructions
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rail_schedules');
    }
};