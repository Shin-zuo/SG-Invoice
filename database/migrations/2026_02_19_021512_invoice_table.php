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
        Schema::create('invoice', function(Blueprint $table){
            $table->id();
            $table->string('name');
            // Changed to string to allow dashes and leading zeros
            $table->string('TIN');
            $table->string('business_address');
            $table->timestamps();
            $table->string('payment_method');
            $table->integer('reference_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};
