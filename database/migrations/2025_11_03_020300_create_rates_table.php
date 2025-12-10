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
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->string('pol');
            $table->string('pod');
            $table->enum('container_type', ['GP', 'RF','OT']);
            $table->string('container_20', 10)->nullable();
            $table->string('container_40', 10)->nullable();
            $table->string('liner');
            $table->string('free_time')->nullable();
            $table->date('valid_date');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};
