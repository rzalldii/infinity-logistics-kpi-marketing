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
        Schema::create('shippers', function (Blueprint $table) {
            $table->id();
            $table->string('shipper_name');
            $table->enum('shipper_type', ['DIRECT SHIPPER', 'FORWARDING', 'TRADING', 'EMKL']);
            $table->string('shipper_city');
            $table->string('shipper_address')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('email_address')->nullable();
            $table->string('export')->nullable();
            $table->string('import')->nullable();
            $table->string('domestic')->nullable();
            $table->string('commodity');
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
        Schema::dropIfExists('shippers');
    }
};
