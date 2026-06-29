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
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->string('maintenance_type'); // preventive, repair, calibration, inspection
            $table->text('description');
            $table->decimal('cost', 12, 2)->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status')->default('scheduled'); // scheduled, in_progress, completed, cancelled
            $table->string('performed_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
