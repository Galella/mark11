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
        Schema::create('trains', function (Blueprint $table) {
            $table->id();
            $table->string('train_number')->unique();
            $table->string('name')->nullable();
            $table->string('operator'); // railway operator
            $table->integer('total_wagons');
            $table->integer('max_teus_capacity'); // maximum TEUs based on 2 TEUs per wagon
            $table->string('route_from');
            $table->string('route_to');
            $table->string('status'); // 'active', 'maintenance', 'decommissioned'
            $table->date('commissioning_date')->nullable();
            $table->date('next_maintenance_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('wagons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('train_id')->constrained('trains')->onDelete('cascade');
            $table->string('wagon_number')->unique();
            $table->string('wagon_type'); // 'flatbed', 'tank', 'box', etc.
            $table->integer('teu_capacity')->default(2); // typically 2 TEUs per wagon
            $table->string('status'); // 'available', 'loaded', 'maintenance', 'out-of-service'
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wagons');
        Schema::dropIfExists('trains');
    }
};