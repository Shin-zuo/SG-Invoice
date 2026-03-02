<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\InvoiceExport;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    // CHANGE THIS LINE: Add "Request $request" inside the parentheses
    public function index(Request $request)
    {
        $query = Invoice::query();

        if ($request->has('search') && $request->search != '') {
            // FIX: Replace spaces with '%' so "Test 2" matches "Test 2", "Test  2", etc.
            $search = str_replace(' ', '%', $request->search);
            
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('payment_method', 'ilike', "%{$search}%")
                  
                  // Cast ID to TEXT to prevent crashes
                  ->orWhereRaw("CAST(id AS TEXT) ILIKE ?", ["%{$search}%"])
                  
                  // Date Search
                  ->orWhereRaw("TO_CHAR(created_at, 'FMMonth FMDD, YYYY') ILIKE ?", ["%{$search}%"]);
            });
        }

        $invoices = $query->latest()->paginate(10);
        $invoices->appends($request->all()); 

        return view('pages.invoice', compact('invoices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Invoice Details
            'name' => 'required|string|max:255',
            'TIN' => 'nullable|string|max:255',
            'business_address' => 'nullable|string|max:255',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|numeric',

            // Items (Array validation)
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.amount' => 'required|numeric|min:0',

            // Amount/Tax Details
            'total_amount' => 'nullable|numeric', // Validates the hidden input
            'vatable_sales' => 'nullable|numeric',
            'vat' => 'nullable|numeric',
            'zero_rated_sales' => 'nullable|numeric',
            'vat_exempt_sales' => 'nullable|numeric',
        ]);

        // Use a Database Transaction to ensure all tables save, or none do.
        DB::transaction(function () use ($validated, $request) {

            // A. Create the Main Invoice
            $invoice = Invoice::create([
                'name' => $validated['name'],
                'TIN' => $validated['TIN'] ?? '',
                'business_address' => $validated['business_address'] ?? '',
                'payment_method' => $validated['payment_method'],
                'reference_number' => $validated['reference_number'],
            ]);

            // B. Save the Items
            foreach ($validated['items'] as $item) {
                $invoice->items()->create([
                    'item_name' => $item['item_name'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'amount' => $item['amount'],
                ]);
            }

            // C. Save the Amounts/Taxes
            // Get total from hidden input, default to 0 if missing
            $total = $request->total_amount ?? 0;

            // FIX: Changed amounts() to amount() (Singular)
            $invoice->amount()->create([
                'vatable_sales' => $validated['vatable_sales'] ?? 0,
                'vat' => $validated['vat'] ?? 0,
                'zero_rated_sales' => $validated['zero_rated_sales'] ?? 0,
                'vat_exempt_sales' => $validated['vat_exempt_sales'] ?? 0,
                'total_amount' => $total,
            ]);
        });

        return redirect()->route('invoice')->with('success', 'Invoice created successfully!');
    }

    /**
     * Display the specified resource for the View Modal
     */
    public function show(string $id)
    {
        // FIX: Changed 'amounts' to 'amount' (Singular)
        $invoice = Invoice::with(['items', 'amount'])->findOrFail($id);

        return response()->json($invoice);
    }

    public function export($id)
    {
        return Excel::download(new InvoiceExport($id), 'invoice_'.$id.'.xlsx');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'TIN' => 'nullable|string|max:255',
            'business_address' => 'nullable|string|max:255',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|numeric',

            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable', // Existing items will have an ID
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.amount' => 'required|numeric|min:0',

            'total_amount' => 'nullable|numeric',
            'vatable_sales' => 'nullable|numeric',
            'vat' => 'nullable|numeric',
            'zero_rated_sales' => 'nullable|numeric',
            'vat_exempt_sales' => 'nullable|numeric',
        ]);

        $invoice = Invoice::findOrFail($id);

        DB::transaction(function () use ($validated, $request, $invoice) {
            // 1. Update Main Invoice
            $invoice->update([
                'name' => $validated['name'],
                'TIN' => $validated['TIN'] ?? '',
                'business_address' => $validated['business_address'] ?? '',
                'payment_method' => $validated['payment_method'],
                'reference_number' => $validated['reference_number'],
            ]);

            // 2. Update Items (Syncing)
            // Get all IDs from the incoming request (ignore nulls which are new items)
            $incomingItemIds = collect($validated['items'])->pluck('id')->filter()->toArray();
            
            // Delete items that were removed in the UI
            $invoice->items()->whereNotIn('id', $incomingItemIds)->delete();

            // Update or Create items
            foreach ($validated['items'] as $itemData) {
                if (isset($itemData['id']) && $itemData['id'] != null) {
                    $invoice->items()->where('id', $itemData['id'])->update([
                        'item_name' => $itemData['item_name'],
                        'description' => $itemData['description'] ?? null,
                        'quantity' => $itemData['quantity'],
                        'amount' => $itemData['amount'],
                    ]);
                } else {
                    $invoice->items()->create([
                        'item_name' => $itemData['item_name'],
                        'description' => $itemData['description'] ?? null,
                        'quantity' => $itemData['quantity'],
                        'amount' => $itemData['amount'],
                    ]);
                }
            }

            // 3. Update Amounts
            $invoice->amount()->update([
                'vatable_sales' => $validated['vatable_sales'] ?? 0,
                'vat' => $validated['vat'] ?? 0,
                'zero_rated_sales' => $validated['zero_rated_sales'] ?? 0,
                'vat_exempt_sales' => $validated['vat_exempt_sales'] ?? 0,
                'total_amount' => $request->total_amount ?? 0,
            ]);
        });

        return redirect()->route('invoice')->with('success', 'Invoice updated successfully!');
    }

}


