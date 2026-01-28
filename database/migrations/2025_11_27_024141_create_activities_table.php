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
            $table->foreignId('parent_id')->nullable()->constrained('activities')->onDelete('cascade');
            $table->integer('sequence')->default(1);
            $table->enum('activity_type', ['VISIT', 'CALL']);
            $table->date('visit_date')->nullable();
            $table->enum('status_type', ['CLOSING', 'PENDING', 'FAILED']);
            $table->string('volume_20', 5)->nullable();
            $table->string('volume_40', 5)->nullable();
            $table->enum('other_volume', ['AIR FREIGHT', 'RAIL FREIGHT', 'ROAD FREIGHT', 'EMKL', 'LCL', 'OTHER BUSINESS'])->nullable();
            $table->string('profit')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('shipper_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->index(['user_id']);
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
