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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'truck', 'chassis', 'trailer', etc.
            $table->string('number'); // license plate or equipment number
            $table->string('owner')->nullable(); // company or individual owner
            $table->string('model')->nullable();
            $table->string('year')->nullable();
            $table->string('status'); // 'active', 'maintenance', 'out-of-service'
            $table->foreignId('terminal_id')->nullable()->constrained('terminals');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['type', 'number']); // ensure unique equipment numbers per type
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};