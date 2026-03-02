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
        Schema::table('invoice_amount', function (Blueprint $table) {
            // We add the 'total_amount' column after the last column
            // nullable() ensures existing data doesn't break
            $table->decimal('total_amount', 10, 2)->nullable()->after('vat_exempt_sales');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('invoice_amount', function (Blueprint $table) {
            $table->dropColumn('total_amount');
        });
    }
};
