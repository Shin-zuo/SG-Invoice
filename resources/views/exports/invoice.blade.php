<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    
    <style>
        .header-title {
            font-weight: bold;
            font-size: 14pt;
        }

        .label {
            font-weight: bold;
        }

        .border-all {
            border: 1px solid #000000;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* FIX: Added this missing class so the TIN aligns left */
        .text-left {
            text-align: left;
        }

        .red-text {
            color: #FF0000;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td colspan="4" class="header-title">SEGOVIA DEVELOPMENT CORPORATION</td>

            <td colspan="2" class="header-title text-right">INVOICE</td>
        </tr>
        <tr>
            <td colspan="3">VAT REG TIN: 000-498-334-00000</td>
            <td></td>
            <td class="text-right label">INV. NO</td>
            <td class="text-right red-text">{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td colspan="4">2nd Floor Heart Building, 7461 Bagtikan Street,</td>
            
            <td class="text-right label">Date:</td>
            <td class="text-right">{{ $invoice->created_at->format('M d, Y') }}</td>
        </tr>
        <tr>
            <td colspan="4">San Antonio Village, City of Makati, Philippines 1203</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="4">Tel Nos.: (632) 8895-61-91; 8895-0693 • Fax No.: 8899-6172</td>
            <td></td>
            <td></td>
        </tr>

        <tr>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td class="label border-all">SOLD TO</td>
            
        </tr>
        <tr>
            <td class="label border-all">Registered Name:</td>
            <td colspan="5" class="border-all">{{ $invoice->name }}</td>
        </tr>
        <tr>
            <td class="label border-all">TIN:</td>
            <td colspan="5" class="border-all text-left" style="text-align: left;">{{ $invoice->TIN }}</td>
        </tr>
        <tr>
            <td class="label border-all">ADDRESS:</td>
            <td colspan="5" class="border-all">{{ $invoice->business_address }}</td>
        </tr>
        <tr>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td colspan="3" class="border-all text-center label">ITEMS / DESCRIPTION</td>
            <td class="border-all text-center label">QTY.</td>
            <td class="border-all text-center label">UNIT PRICE</td>
            <td class="border-all text-center label">AMOUNT</td>
        </tr>

        @foreach ($invoice->items as $item)
            <tr>
<td colspan="3" class="border-all">{{ $item->item_name }} - {{ $item->description }}</td>
                <td class="border-all text-left" style="text-align: left;">{{ $item->quantity }}</td>
                <td class="border-all text-left" style="text-align: left;">
                    {{ number_format($item->quantity > 0 ? $item->amount / $item->quantity : 0, 2) }}</td>
                <td class="border-all text-left" style="text-align: left;">{{ number_format($item->amount, 2) }}</td>
            </tr>
        @endforeach

        @for ($i = 0; $i < 8 - count($invoice->items); $i++)
            <tr>
                <td class="border-all"></td>
                <td class="border-all"></td>
                <td class="border-all"></td>
                <td class="border-all"></td>
                <td class="border-all"></td>
            </tr>
        @endfor

        <tr>
            <td colspan="3" rowspan="5" style="vertical-align: top;">
                <br><br><b>BY:</b><br><br>
                __________________________<br>
                Cashier / Authorized Representative
            </td>
            <td class="text-right border-all label">Vatable Sales</td>
            <td colspan="2" class="text-right border-all">
                {{ number_format($invoice->amount->vatable_sales ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td class="text-right border-all label">VAT Amount</td>
            <td colspan="2" class="text-right border-all">{{ number_format($invoice->amount->vat ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td class="text-right border-all label">Zero-Rated Sales</td>
            <td colspan="2" class="text-right border-all">
                {{ number_format($invoice->amount->zero_rated_sales ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td class="text-right border-all label">VAT-Exempt Sales</td>
            <td colspan="2" class="text-right border-all">
                {{ number_format($invoice->amount->vat_exempt_sales ?? 0, 2) }}</td>
        </tr>   
        <tr>
            <td class="text-right border-all label">TOTAL AMOUNT DUE</td>
            <td colspan="2" class="text-right border-all" style="font-weight: bold;">
                {{ number_format($invoice->amount->total_amount ?? 0, 2) }}</td>
        </tr>
    </table>
</body>

</html>