<?php

namespace App\Exports;

use App\Models\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoiceExport implements FromView, WithStyles, ShouldAutoSize
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $invoice = Invoice::with('items', 'amount')->findOrFail($this->id);
        
        // Calculate unit prices for the view since they aren't in DB
        foreach($invoice->items as $item) {
            $item->unit_price = $item->quantity > 0 ? $item->amount / $item->quantity : 0;
        }

        return view('exports.invoice', [
            'invoice' => $invoice
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Set specific column widths to match the receipt image
        $sheet->getColumnDimension('A')->setWidth(10); // QTY
        $sheet->getColumnDimension('B')->setWidth(10); // Unit
        $sheet->getColumnDimension('C')->setWidth(50); // Description
        $sheet->getColumnDimension('D')->setWidth(15); // Unit Price
        $sheet->getColumnDimension('E')->setWidth(15); // Amount

        return [
            // Bold the Company Name
            1 => ['font' => ['bold' => true, 'size' => 14]],
            // Center align the table headers
            11 => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
        ];
    }
}