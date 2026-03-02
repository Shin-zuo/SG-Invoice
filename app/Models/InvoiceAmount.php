<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceAmount extends Model
{
    use HasFactory; 

    protected $table = 'invoice_amount';

    // FIX: This line is crucial because your migration didn't include $table->timestamps()
    public $timestamps = false;

    protected $fillable = [
        'invoice_id', 
        'vatable_sales', 
        'vat', 
        'zero_rated_sales', 
        'vat_exempt_sales',
        'total_amount',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
