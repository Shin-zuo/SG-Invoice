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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();

            // --- THE FOREIGN KEY ---
            // This creates an unsigned big integer column named 'invoice_id'
            // and links it to the 'id' column on the 'invoice' table.
            $table->foreignId('invoice_id')
                  ->constrained('invoice') // We explicitly specify 'invoice' because your table name is singular
                  ->onDelete('cascade');   // If the Invoice is deleted, delete these items too

            // --- ITEM DETAILS ---
            $table->string('item_name');
            $table->text('description')->nullable(); // distinct from name, optional
            $table->integer('quantity');
            
            // Use decimal for money, not float/double
            // (10 digits total, 2 digits after decimal point)
            $table->decimal('amount', 10, 2); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
