<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

   protected $table = 'invoice';

    protected $fillable = [
        'name',
        'TIN',
        'business_address',
        'payment_method',
        'reference_number',
    ];

    // FIX 1: Correct syntax for items
    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

    // FIX 2: Add the missing relationship for totals
    public function amount()
    {
        return $this->hasOne(InvoiceAmount::class, 'invoice_id');
    }
}
