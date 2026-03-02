<x-layouts.app title="Invoices">

    <style>
        .modal {
            z-index: 1055 !important;
        }

        .modal-backdrop {
            z-index: 1050 !important;
        }

        /* Hide number spinners */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        /* Super Compact Input Style */
        .form-control-xs {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            min-height: 28px;
        }

        .form-label-xs {
            font-size: 0.65rem;
            margin-bottom: 0.1rem;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
        }
    </style>

    <div class="d-flex flex-column flex-md-row justify-content-between mb-4 gap-3">

        <form action="{{ route('invoice') }}" method="GET" class="d-flex gap-2 w-80 w-md-auto">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0 text-secondary">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>

                <input type="text" name="search" class="form-control border-start-0"
                    placeholder="Search..." value="{{ request('search') }}">

                @if (request('search'))
                    <a href="{{ route('invoice') }}"
                        class="btn btn-white border-top border-bottom border-end text-secondary" style="z-index: 4;">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>

        <button class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm" data-bs-toggle="modal"
            data-bs-target="#createInvoiceModal">
            <i class="fa-solid fa-plus" style="font-size: 0.85em;"></i>
            <span style="font-size: 0.95rem; font-weight: 500;">New Invoice</span>
        </button>

    </div>

    <div id="invoice-list-container" class="card border-0 shadow-sm rounded-4 overflow-hidden">

        @if ($invoices->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 ps-4 text-secondary text-uppercase fw-bold small">Invoice #</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small">Name</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small">Address</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small">Payment Option</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold small">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr style="cursor: pointer;" onclick="viewInvoice({{ $invoice->id }})">
                                <td class="ps-4 fw-bold text-primary">
                                    INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td><span class="fw-medium">{{ $invoice->name }}</span></td>
                                <td class="text-muted small">{{ Str::limit($invoice->business_address, 30) }}</td>
                                <td>
                                    <span
                                        class="badge bg-light text-secondary border border-secondary-subtle rounded-pill px-3">
                                        {{ $invoice->payment_method }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $invoice->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-3 border-top">
                {{ $invoices->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <h5 class="fw-bold text-muted">No existing invoices</h5>
                <p class="text-muted">Your database is currently empty.</p>
            </div>
        @endif
    </div>

    <div class="modal fade" id="createInvoiceModal" aria-hidden="true" x-data="invoiceForm()">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">

                <div class="modal-header border-bottom py-2 bg-light">
                    <h6 class="modal-title fw-bold text-dark">
                        <i class="fa-solid fa-file-invoice me-2 text-primary"></i>New Invoice
                    </h6>
                    <button type="button" class="btn-close small" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('invoice.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-3">

                        <div class="row g-1 mb-2">
                            <div class="col-md-4">
                                <label class="form-label-xs">Client Name</label>
                                <input type="text" name="name" class="form-control form-control-xs" required
                                    placeholder="Name">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label-xs">TIN</label>
                                <input type="text" name="TIN" class="form-control form-control-xs"
                                    placeholder="TIN">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label-xs">Address</label>
                                <input type="text" name="business_address" class="form-control form-control-xs"
                                    placeholder="Address">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label-xs">Payment Method</label>
                                <select name="payment_method" class="form-select form-select-sm"
                                    style="font-size: 0.75rem; padding-top: 2px; padding-bottom: 2px;">
                                    <option value="Cash">Cash</option>
                                    <option value="Check">Check</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="GCash">GCash</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label-xs">Ref No.</label>
                                <input type="number" name="reference_number" class="form-control form-control-xs"
                                    placeholder="Optional">
                            </div>
                        </div>

                        <hr class="text-secondary opacity-25 my-2">

                        <div class="d-flex justify-content-between align-items-end mb-1">
                            <h6 class="text-uppercase fw-bold text-secondary small mb-0" style="font-size: 0.7rem;">
                                Items</h6>
                            <div class="text-end">
                                <span class="text-muted small me-2" style="font-size: 0.7rem;">Items Total:</span>
                                <span class="fw-bold text-primary" style="font-size: 0.9rem;"
                                    x-text="formatMoney(getItemsTotal())"></span>
                            </div>
                        </div>

                        <div class="table-responsive mb-2 border rounded">
                            <table class="table table-sm table-borderless align-middle mb-0">
                                <thead class="bg-light border-bottom">
                                    <tr>
                                        <th class="ps-2 text-secondary text-uppercase fw-bold"
                                            style="width: 25%; font-size: 0.65rem;">Item Name</th>
                                        <th class="text-secondary text-uppercase fw-bold"
                                            style="width: 35%; font-size: 0.65rem;">Description</th>
                                        <th class="text-center text-secondary text-uppercase fw-bold"
                                            style="width: 15%; font-size: 0.65rem;">Qty</th>
                                        <th class="text-end text-secondary text-uppercase fw-bold"
                                            style="width: 20%; font-size: 0.65rem;">Amount</th>
                                        <th class="text-end pe-2" style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr class="border-bottom">
                                            <td class="ps-2 py-1">
                                                <input type="text" :name="`items[${index}][item_name]`"
                                                    x-model="item.item_name" class="form-control form-control-xs"
                                                    placeholder="Name" required>
                                            </td>
                                            <td class="py-1">
                                                <input type="text" :name="`items[${index}][description]`"
                                                    x-model="item.description" class="form-control form-control-xs"
                                                    placeholder="Desc">
                                            </td>
                                            <td class="py-1">
                                                <input type="number" :name="`items[${index}][quantity]`"
                                                    x-model="item.quantity"
                                                    class="form-control form-control-xs text-center" placeholder="1"
                                                    required>
                                            </td>
                                            <td class="py-1">
                                                <input type="number" :name="`items[${index}][amount]`"
                                                    x-model="item.amount"
                                                    class="form-control form-control-xs text-end fw-bold"
                                                    placeholder="0.00" step="0.01" required>
                                            </td>
                                            <td class="text-end pe-2 py-1">
                                                <button type="button" class="btn btn-sm text-danger p-0"
                                                    @click="removeItem(index)" x-show="items.length > 1">
                                                    <i class="fa-solid fa-trash-can" style="font-size: 0.8rem;"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center mb-2">
                            <button type="button" class="btn btn-sm btn-light text-primary border fw-bold w-100 py-0"
                                style="font-size: 0.7rem;" @click="addItem()">
                                <i class="fa-solid fa-plus me-1"></i> Add Row
                            </button>
                        </div>

                        <div class="bg-light p-2 rounded border">
                            <div class="row g-1">
                                <input type="hidden" name="total_amount" :value="getItemsTotal()">

                                <div class="col-md-3">
                                    <label class="form-label-xs">Vatable Sales</label>
                                    <input type="number" name="vatable_sales"
                                        class="form-control form-control-xs text-end" placeholder="0.00"
                                        step="0.01">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-xs">VAT Amount</label>
                                    <input type="number" name="vat"
                                        class="form-control form-control-xs text-end" placeholder="0.00"
                                        step="0.01">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-xs">Zero Rated</label>
                                    <input type="number" name="zero_rated_sales"
                                        class="form-control form-control-xs text-end" placeholder="0.00"
                                        step="0.01">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-xs">VAT Exempt</label>
                                    <input type="number" name="vat_exempt_sales"
                                        class="form-control form-control-xs text-end" placeholder="0.00"
                                        step="0.01">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer bg-light border-top p-2">
                        <button type="button" class="btn btn-sm btn-light border"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary px-4 fw-bold">Save Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewInvoiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-bottom py-2 bg-light">
                    <h6 class="modal-title fw-bold text-dark">
                        <i class="fa-solid fa-eye me-2 text-primary"></i>Invoice Details
                    </h6>
                    <div class="d-flex gap-2 ms-auto me-2">

                        <button type="button" id="btnEditInvoice" class="btn btn-sm btn-outline-warning border-0" title="Edit Invoice">
        <i class="fa-solid fa-pen"></i>
    </button>
    
    <button type="button" id="btnDeleteInvoice" class="btn btn-sm btn-outline-danger border-0" title="Delete Invoice">
        <i class="fa-solid fa-trash"></i>
    </button>

                        <a href="#" id="btnDownloadExcel" class="btn btn-sm btn-outline-success border-0"
                            title="Download Excel" target="_blank">
                            <i class="fa-solid fa-file-excel"></i>
                        </a>

                        <button type="button" class="btn btn-sm btn-outline-primary border-0" title="Print Invoice">
                            <i class="fa-solid fa-print"></i>
                        </button>
                    </div>
                    <button type="button" class="btn-close small" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="invoiceDetailsContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
                        <span class="ms-2 text-muted small">Fetching data...</span>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top p-2">
                    
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editInvoiceModal" aria-hidden="true" x-data="editInvoiceForm()" @load-items.window="if ($event.detail.id == document.getElementById('editInvoiceFormElement').action.split('/').pop()) loadData($event.detail)">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header border-bottom py-2 bg-light">
                <h6 class="modal-title fw-bold text-dark">
                    <i class="fa-solid fa-pen me-2 text-warning"></i>Edit Invoice
                </h6>
                <button type="button" class="btn-close small" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="editInvoiceFormElement" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-3">
                    <div class="row g-1 mb-2">
                        <div class="col-md-4">
                            <label class="form-label-xs">Client Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control form-control-xs" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-xs">TIN</label>
                            <input type="text" name="TIN" id="edit_TIN" class="form-control form-control-xs">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label-xs">Address</label>
                            <input type="text" name="business_address" id="edit_business_address" class="form-control form-control-xs">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-xs">Payment Method</label>
                            <select name="payment_method" id="edit_payment_method" class="form-select form-select-sm" style="font-size: 0.75rem; padding-top: 2px; padding-bottom: 2px;">
                                <option value="Cash">Cash</option>
                                <option value="Check">Check</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="GCash">GCash</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-xs">Ref No.</label>
                            <input type="number" name="reference_number" id="edit_reference_number" class="form-control form-control-xs">
                        </div>
                    </div>

                    <hr class="text-secondary opacity-25 my-2">

                    <div class="d-flex justify-content-between align-items-end mb-1">
                        <h6 class="text-uppercase fw-bold text-secondary small mb-0" style="font-size: 0.7rem;">Items</h6>
                        <div class="text-end">
                            <span class="text-muted small me-2" style="font-size: 0.7rem;">Items Total:</span>
                            <span class="fw-bold text-primary" style="font-size: 0.9rem;" x-text="formatMoney(getItemsTotal())"></span>
                        </div>
                    </div>

                    <div class="table-responsive mb-2 border rounded">
                        <table class="table table-sm table-borderless align-middle mb-0">
                            <thead class="bg-light border-bottom">
                                <tr>
                                    <th class="ps-2 text-secondary text-uppercase fw-bold" style="width: 25%; font-size: 0.65rem;">Item Name</th>
                                    <th class="text-secondary text-uppercase fw-bold" style="width: 35%; font-size: 0.65rem;">Description</th>
                                    <th class="text-center text-secondary text-uppercase fw-bold" style="width: 15%; font-size: 0.65rem;">Qty</th>
                                    <th class="text-end text-secondary text-uppercase fw-bold" style="width: 20%; font-size: 0.65rem;">Amount</th>
                                    <th class="text-end pe-2" style="width: 5%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="border-bottom">
                                        <td class="ps-2 py-1">
                                            <input type="hidden" :name="`items[${index}][id]`" x-model="item.id">
                                            <input type="text" :name="`items[${index}][item_name]`" x-model="item.item_name" class="form-control form-control-xs" required>
                                        </td>
                                        <td class="py-1">
                                            <input type="text" :name="`items[${index}][description]`" x-model="item.description" class="form-control form-control-xs">
                                        </td>
                                        <td class="py-1">
                                            <input type="number" :name="`items[${index}][quantity]`" x-model="item.quantity" class="form-control form-control-xs text-center" required>
                                        </td>
                                        <td class="py-1">
                                            <input type="number" :name="`items[${index}][amount]`" x-model="item.amount" class="form-control form-control-xs text-end fw-bold" step="0.01" required>
                                        </td>
                                        <td class="text-end pe-2 py-1">
                                            <button type="button" class="btn btn-sm text-danger p-0" @click="removeItem(index)" x-show="items.length > 1">
                                                <i class="fa-solid fa-trash-can" style="font-size: 0.8rem;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center mb-2">
                        <button type="button" class="btn btn-sm btn-light text-primary border fw-bold w-100 py-0" style="font-size: 0.7rem;" @click="addItem()">
                            <i class="fa-solid fa-plus me-1"></i> Add Row
                        </button>
                    </div>

                    <div class="bg-light p-2 rounded border">
                        <div class="row g-1">
                            <input type="hidden" name="total_amount" :value="getItemsTotal()">
                            <div class="col-md-3">
                                <label class="form-label-xs">Vatable Sales</label>
                                <input type="number" name="vatable_sales" id="edit_vatable_sales" class="form-control form-control-xs text-end" step="0.01">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label-xs">VAT Amount</label>
                                <input type="number" name="vat" id="edit_vat" class="form-control form-control-xs text-end" step="0.01">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label-xs">Zero Rated</label>
                                <input type="number" name="zero_rated_sales" id="edit_zero_rated_sales" class="form-control form-control-xs text-end" step="0.01">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label-xs">VAT Exempt</label>
                                <input type="number" name="vat_exempt_sales" id="edit_vat_exempt_sales" class="form-control form-control-xs text-end" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top p-2">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-warning px-4 fw-bold">Update Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <script>
        function invoiceForm() {
            return {
                items: [{
                    item_name: '',
                    description: '',
                    quantity: 1,
                    amount: ''
                }],

                addItem() {
                    this.items.push({
                        item_name: '',
                        description: '',
                        quantity: 1,
                        amount: ''
                    });
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                },

                // Calculates the sum of the "Amount" column only
                getItemsTotal() {
                    return this.items.reduce((sum, item) => {
                        return sum + (parseFloat(item.amount) || 0);
                    }, 0);
                },

                formatMoney(value) {
                    return '₱' + parseFloat(value).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            }
        }

        function viewInvoice(id) {
    const viewModal = new bootstrap.Modal(document.getElementById('viewInvoiceModal'));
    viewModal.show();

    const downloadBtn = document.getElementById('btnDownloadExcel');
    if (downloadBtn) {
        downloadBtn.href = `/invoice/${id}/export`;
    }

    const content = document.getElementById('invoiceDetailsContent');
    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
            <span class="ms-2 text-muted small">Fetching data...</span>
        </div>
    `;

    fetch(`/invoice/${id}`)
        .then(response => response.json())
        .then(data => {
            const amt = data.amount || {};
            const total = parseFloat(amt.total_amount || 0);

            const dateObj = new Date(data.created_at);
            const formattedDate = !isNaN(dateObj) ?
                dateObj.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit'
                }).replace(/\//g, '-') :
                'N/A';

            content.innerHTML = `
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label-xs text-secondary text-uppercase d-block">Client Name</label>
                        <p class="fw-bold mb-0 text-primary">${data.name}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-xs text-secondary text-uppercase d-block">Address</label>
                        <p class="small mb-0 text-dark">${data.business_address || 'N/A'}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-xs text-secondary text-uppercase d-block">Date</label>
                        <p class="mb-0 font-monospace text-dark">${formattedDate}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label-xs text-secondary text-uppercase d-block">TIN</label>
                        <p class="mb-0 font-monospace text-dark">${data.TIN || 'N/A'}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-xs text-secondary text-uppercase d-block">Reference No.</label>
                        <p class="mb-0 font-monospace text-dark">${data.reference_number || 'N/A'}</p>
                    </div>
                    <div class="col-md-4"></div>
                </div>

                <div class="table-responsive border rounded-top">
                    <table class="table table-sm table-striped mb-0 small">
                        <thead class="bg-light">
                            <tr>
                                <th>Item Name</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.items.map(item => `
                                <tr>
                                    <td>
                                        <div class="fw-medium">${item.item_name}</div>
                                        <div class="text-muted" style="font-size:0.75em">${item.description || ''}</div>
                                    </td>
                                    <td class="text-center">${item.quantity}</td>
                                    <td class="text-end fw-bold">₱${parseFloat(item.amount).toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
                
                <div class="bg-light border border-top-0 rounded-bottom p-2">
                     <div class="row">
                        <div class="col-12 col-md-6 ms-auto">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small text-muted">Vatable Sales:</span>
                                <span class="small fw-bold">₱${parseFloat(amt.vatable_sales || 0).toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small text-muted">VAT Amount:</span>
                                <span class="small fw-bold">₱${parseFloat(amt.vat || 0).toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                            </div>
                            <div class="border-top my-1"></div>
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold text-dark small">TOTAL AMOUNT:</span>
                                <span class="fw-bold text-primary">₱${total.toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                            </div>
                        </div>
                     </div>
                </div>
            `;

            // --- MOVED INSIDE THE .then() BLOCK ---
            const btnEdit = document.getElementById('btnEditInvoice');
            if (btnEdit) {
                btnEdit.onclick = () => {
                    viewModal.hide();
                    
                    document.getElementById('editInvoiceFormElement').action = `/invoice/${data.id}`;
                    
                    document.getElementById('edit_name').value = data.name;
                    document.getElementById('edit_TIN').value = data.TIN || '';
                    document.getElementById('edit_business_address').value = data.business_address || '';
                    document.getElementById('edit_payment_method').value = data.payment_method;
                    document.getElementById('edit_reference_number').value = data.reference_number || '';
                    
                    const amtData = data.amount || {};
                    document.getElementById('edit_vatable_sales').value = amtData.vatable_sales || '';
                    document.getElementById('edit_vat').value = amtData.vat || '';
                    document.getElementById('edit_zero_rated_sales').value = amtData.zero_rated_sales || '';
                    document.getElementById('edit_vat_exempt_sales').value = amtData.vat_exempt_sales || '';

                    const editModalElement = document.getElementById('editInvoiceModal');
                    editModalElement.dispatchEvent(new CustomEvent('load-items', { detail: data }));
                    
                    const editModal = new bootstrap.Modal(editModalElement);
                    editModal.show();
                };
            }
            // ----------------------------------------
        })
        .catch(err => {
            const content = document.getElementById('invoiceDetailsContent');
            content.innerHTML = `<div class="text-danger text-center">Error loading invoice details.</div>`;
            console.error(err);
        });
}


//search bar
        document.addEventListener('DOMContentLoaded', function() {
            let debounceTimer;
            const searchInput = document.querySelector('input[name="search"]');
            const resultContainer = document.getElementById('invoice-list-container');

            // Listen for typing events
            searchInput.addEventListener('input', function() {
                // Clear the previous timer (this resets the 0.5s countdown)
                clearTimeout(debounceTimer);

                // Start a new timer
                debounceTimer = setTimeout(() => {
                    performSearch(this.value);
                }, 500); // 500ms delay
            });

            function performSearch(query) {
                // Add a visual indicator (opacity) that it's loading
                resultContainer.style.opacity = '0.5';
                resultContainer.style.transition = 'opacity 0.2s';

                // Construct the URL with the search term
                const url = "{{ route('invoice') }}?search=" + encodeURIComponent(query);

                // Update the Browser URL (so if they refresh, the search stays)
                window.history.pushState(null, '', url);

                // Fetch the new data
                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        // Parse the returned HTML to find the new table
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newContent = doc.getElementById('invoice-list-container').innerHTML;

                        // Replace the old table with the new one
                        resultContainer.innerHTML = newContent;
                        
                        // Restore opacity
                        resultContainer.style.opacity = '1';
                    })
                    .catch(error => {
                        console.error('Error fetching search results:', error);
                        resultContainer.style.opacity = '1';
                    });
            }
        });

        // AlpineJS component for the Edit Modal
function editInvoiceForm() {
    return {
        items: [],
        
        loadData(invoiceData) {
            // Load items from database into Alpine state
            this.items = invoiceData.items.map(item => ({
                id: item.id,
                item_name: item.item_name,
                description: item.description || '',
                quantity: item.quantity,
                amount: item.amount
            }));
            
            // If somehow there are no items, add an empty row
            if (this.items.length === 0) this.addItem();
        },
        
        addItem() {
            this.items.push({ id: '', item_name: '', description: '', quantity: 1, amount: '' });
        },
        removeItem(index) {
            this.items.splice(index, 1);
        },
        getItemsTotal() {
            return this.items.reduce((sum, item) => sum + (parseFloat(item.amount) || 0), 0);
        },
        formatMoney(value) {
            return '₱' + parseFloat(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }
}
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</x-layouts.app>
