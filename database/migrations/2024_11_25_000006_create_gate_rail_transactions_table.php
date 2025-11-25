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
        // Gate In/Out Transactions
        Schema::create('gate_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('terminal_id')->constrained('terminals');
            $table->foreignId('container_id')->constrained('containers');
            $table->string('transaction_type'); // 'GATE_IN' or 'GATE_OUT'
            $table->string('truck_number');
            $table->string('driver_name');
            $table->string('driver_license')->nullable();
            $table->string('seal_number')->nullable();
            $table->string('status'); // 'completed', 'pending', 'cancelled'
            $table->timestamp('transaction_time');
            $table->foreignId('created_by')->constrained('users');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // Rail In/Out Transactions
        Schema::create('rail_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('terminal_id')->constrained('terminals');
            $table->foreignId('container_id')->constrained('containers');
            $table->foreignId('rail_schedule_id')->constrained('rail_schedules');
            $table->foreignId('wagon_id')->nullable()->constrained('wagons'); // nullable because unload might not assign to specific wagon
            $table->string('transaction_type'); // 'RAIL_IN' or 'RAIL_OUT'
            $table->string('operation_type'); // 'LOR' (Load on Rail) or 'UFR' (Unload from Rail)
            $table->string('wagon_position')->nullable(); // position of container on wagon
            $table->boolean('is_handover'); // true if this is a handover between terminals
            $table->foreignId('handover_terminal_id')->nullable()->constrained('terminals'); // destination terminal for handover
            $table->string('status'); // 'completed', 'pending', 'cancelled'
            $table->timestamp('transaction_time');
            $table->foreignId('created_by')->constrained('users');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rail_transactions');
        Schema::dropIfExists('gate_transactions');
    }
};