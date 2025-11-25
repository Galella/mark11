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
        Schema::create('containers', function (Blueprint $table) {
            $table->id();
            $table->string('number', 11)->unique(); // ISO 6346 container number (4 letters + 7 digits)
            $table->string('type', 4); // e.g., '22G1', '45G1', '22R1', etc.
            $table->string('size_type'); // e.g., '20GP', '40GP', '20HC', '40HC', '20OT', '40OT'
            $table->string('category'); // 'import', 'export', 'transhipment'
            $table->string('status'); // 'full', 'empty'
            $table->string('iso_code', 4); // ISO 6346 equipment category identifier
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('active_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('container_id')->constrained('containers')->onDelete('cascade');
            $table->foreignId('terminal_id')->constrained('terminals')->onDelete('cascade');
            $table->string('current_location')->nullable(); // specific yard position
            $table->string('handling_status'); // 'in-yard', 'in-gate', 'in-rail', etc.
            $table->foreignId('last_transaction_id')->nullable(); // reference to last transaction
            $table->string('last_transaction_type'); // 'GATE_IN', 'GATE_OUT', 'RAIL_IN', 'RAIL_OUT'
            $table->timestamp('in_time'); // when container entered terminal
            $table->timestamp('out_time')->nullable(); // when container left terminal (if applicable)
            $table->timestamp('dwell_time_start')->nullable(); // for dwell time calculation
            $table->timestamps();

            $table->unique(['container_id', 'terminal_id']); // prevents same container at multiple terminals
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('active_inventory');
        Schema::dropIfExists('containers');
    }
};