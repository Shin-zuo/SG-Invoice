<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceAmount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        // 1. Card Data: Total Count
        $totalInvoices = Invoice::count();

        // 2. Card Data: Grand Total Amount
        $grandTotal = InvoiceAmount::sum('total_amount');

        // 3. Get available years for the dropdown (from created_at)
        // PostgreSQL uses EXTRACT(YEAR FROM ...)
        $availableYears = Invoice::selectRaw('EXTRACT(YEAR FROM created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('pages.analytics', compact('totalInvoices', 'grandTotal', 'availableYears'));
    }

    public function getChartData(Request $request)
    {
        $filter = $request->get('filter', 'monthly'); // 'monthly' or 'yearly'
        $year = $request->get('year', date('Y'));

        $query = Invoice::query()
            ->join('invoice_amount', 'invoice.id', '=', 'invoice_amount.invoice_id');

        if ($filter === 'yearly') {
            // Group by Year
            $data = $query->selectRaw("
                    CAST(EXTRACT(YEAR FROM invoice.created_at) AS INTEGER) as label, 
                    SUM(invoice_amount.total_amount) as total
                ")
                ->groupBy('label')
                ->orderBy('label', 'asc')
                ->get();
        } else {
            // Group by Month for a specific Year
            $data = $query->selectRaw("
                    TO_CHAR(invoice.created_at, 'Month') as label, 
                    EXTRACT(MONTH FROM invoice.created_at) as month_num,
                    SUM(invoice_amount.total_amount) as total
                ")
                ->whereRaw("EXTRACT(YEAR FROM invoice.created_at) = ?", [$year])
                ->groupBy('label', 'month_num')
                ->orderBy('month_num', 'asc')
                ->get();
        }

        return response()->json([
            'labels' => $data->pluck('label')->map(fn($l) => trim($l)), // Trim removes extra padding from Postgres
            'totals' => $data->pluck('total'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
