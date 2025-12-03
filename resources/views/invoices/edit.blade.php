@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Invoice #{{ $invoice->invoice_number }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('invoices.update', $invoice) }}" id="invoice-form">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="customer_id" class="form-label">Customer *</label>
                                <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                                    <option value="">Select Customer</option>
                                    @foreach(\App\Models\Customer::orderBy('name')->get() as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id', $invoice->customer_id) == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="invoice_date" class="form-label">Invoice Date *</label>
                                <input type="date" class="form-control @error('invoice_date') is-invalid @enderror"
                                       id="invoice_date" name="invoice_date" value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" required>
                                @error('invoice_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="due_date" class="form-label">Due Date *</label>
                                <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                       id="due_date" name="due_date" value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}" required>
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="currency_id" class="form-label">Currency *</label>
                                <select class="form-select @error('currency_id') is-invalid @enderror" id="currency_id" name="currency_id" required>
                                    @foreach(\App\Models\Currency::where('is_active', true)->get() as $currency)
                                        <option value="{{ $currency->id }}" {{ old('currency_id', $invoice->currency_id) == $currency->id ? 'selected' : '' }}>
                                            {{ $currency->code }} ({{ $currency->symbol }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('currency_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="draft" {{ old('status', $invoice->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="sent" {{ old('status', $invoice->status) == 'sent' ? 'selected' : '' }}>Sent</option>
                                    <option value="partially_paid" {{ old('status', $invoice->status) == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                                    <option value="paid" {{ old('status', $invoice->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="overdue" {{ old('status', $invoice->status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                    <option value="cancelled" {{ old('status', $invoice->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $invoice->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <h5>Invoice Items</h5>
                        <div id="invoice-items">
                            @foreach($invoice->items as $index => $item)
                            <div class="invoice-item row mb-3" data-item="{{ $index }}">
                                <div class="col-md-4">
                                    <label class="form-label">Product/Service *</label>
                                    <select class="form-select product-select" name="items[{{ $index }}][product_id]" required>
                                        <option value="">Select Product/Service</option>
                                        @foreach(\App\Models\Product::orderBy('name')->get() as $product)
                                            <option value="{{ $product->id }}"
                                                    data-price="{{ $product->unit_price }}"
                                                    data-tax="{{ $product->tax_rate }}"
                                                    {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }} - ${{ number_format($product->unit_price, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Quantity *</label>
                                    <input type="number" class="form-control quantity-input" name="items[{{ $index }}][quantity]"
                                           value="{{ $item->quantity }}" min="1" step="1" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Unit Price *</label>
                                    <input type="number" class="form-control price-input" name="items[{{ $index }}][unit_price]"
                                           value="{{ $item->unit_price }}" step="0.01" min="0" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Tax Rate (%)</label>
                                    <input type="number" class="form-control tax-input" name="items[{{ $index }}][tax_rate]"
                                           value="{{ $item->tax_rate }}" step="0.01" min="0">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Total</label>
                                    <input type="text" class="form-control item-total" readonly value="${{ number_format($item->total, 2) }}">
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-secondary" id="add-item">Add Item</button>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-md-4">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Subtotal:</strong></td>
                                        <td class="text-end" id="subtotal">${{ number_format($invoice->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tax:</strong></td>
                                        <td class="text-end" id="total-tax">${{ number_format($invoice->tax_amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total:</strong></td>
                                        <td class="text-end" id="grand-total">${{ number_format($invoice->total, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Invoice</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemCount = {{ $invoice->items->count() }};

    // Add new item
    document.getElementById('add-item').addEventListener('click', function() {
        const template = document.querySelector('.invoice-item').cloneNode(true);
        template.dataset.item = itemCount;

        // Update names
        template.querySelectorAll('select, input').forEach(input => {
            input.name = input.name.replace(/\[\d+\]/, `[${itemCount}]`);
            if (input.type !== 'hidden') {
                input.value = '';
            }
        });

        // Reset product select
        const productSelect = template.querySelector('.product-select');
        productSelect.selectedIndex = 0;

        document.getElementById('invoice-items').appendChild(template);
        itemCount++;

        // Add event listeners to new item
        addItemEventListeners(template);
    });

    // Add event listeners to all existing items
    document.querySelectorAll('.invoice-item').forEach(item => {
        addItemEventListeners(item);
    });

    function addItemEventListeners(item) {
        const productSelect = item.querySelector('.product-select');
        const quantityInput = item.querySelector('.quantity-input');
        const priceInput = item.querySelector('.price-input');
        const taxInput = item.querySelector('.tax-input');

        // Product selection
        productSelect.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            if (option.dataset.price) {
                priceInput.value = option.dataset.price;
                taxInput.value = option.dataset.tax || 0;
                calculateItemTotal(item);
            }
        });

        // Quantity, price, tax changes
        [quantityInput, priceInput, taxInput].forEach(input => {
            input.addEventListener('input', () => calculateItemTotal(item));
        });
    }

    function calculateItemTotal(item) {
        const quantity = parseFloat(item.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(item.querySelector('.price-input').value) || 0;
        const taxRate = parseFloat(item.querySelector('.tax-input').value) || 0;

        const subtotal = quantity * price;
        const tax = subtotal * (taxRate / 100);
        const total = subtotal + tax;

        item.querySelector('.item-total').value = '$' + total.toFixed(2);
        calculateTotals();
    }

    function calculateTotals() {
        let subtotal = 0;
        let totalTax = 0;

        document.querySelectorAll('.invoice-item').forEach(item => {
            const quantity = parseFloat(item.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(item.querySelector('.price-input').value) || 0;
            const taxRate = parseFloat(item.querySelector('.tax-input').value) || 0;

            const itemSubtotal = quantity * price;
            const itemTax = itemSubtotal * (taxRate / 100);

            subtotal += itemSubtotal;
            totalTax += itemTax;
        });

        const grandTotal = subtotal + totalTax;

        document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('total-tax').textContent = '$' + totalTax.toFixed(2);
        document.getElementById('grand-total').textContent = '$' + grandTotal.toFixed(2);
    }
});
</script>
@endsection
