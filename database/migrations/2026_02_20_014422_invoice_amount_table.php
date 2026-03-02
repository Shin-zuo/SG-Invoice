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
        Schema::create('invoice_amount', function(Blueprint $table){
            $table->id();

            $table->foreignId('invoice_id')
                  ->constrained('invoice')
                  ->onDelete('cascade');

            $table->decimal('vatable_sales', 10, 2);
            $table->decimal('vat', 10, 2);
            $table->decimal('zero_rated_sales', 10, 2);
            $table->decimal('vat_exempt_sales', 10, 2);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_amount');
    }
};
