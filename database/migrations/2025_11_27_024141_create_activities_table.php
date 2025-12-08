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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->enum('concept_type', ['NEW SHIPPER', 'FOLLOW UP']);
            $table->enum('activity_type', ['VISIT', 'CALL']);
            $table->date('visit_date')->nullable();
            $table->enum('status', ['CLOSING', 'PENDING', 'FAILED'])->nullable();
            $table->string('status_detail')->nullable();
            $table->text('prospect')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('shipper_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->index(['user_id', 'report_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
