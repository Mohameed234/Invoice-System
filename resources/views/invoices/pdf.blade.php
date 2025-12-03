<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .card-header {
            background-color: #f8f9fa;
            padding: 20px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .card-body {
            padding: 20px;
        }

        .row {
            display: flex;
            margin: 0 -10px;
        }

        .col-md-6 {
            flex: 0 0 50%;
            padding: 0 10px;
        }

        .text-end {
            text-align: right;
        }

        .mb-4 {
            margin-bottom: 20px;
        }

        .mt-4 {
            margin-top: 20px;
        }

        h6 {
            margin: 0 0 10px 0;
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        p {
            margin: 5px 0;
            line-height: 1.5;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #f8f9fa;
        }

        .table th,
        .table td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
            vertical-align: top;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        .table-borderless {
            border: none;
        }

        .table-borderless td {
            border: none;
            padding: 8px 0;
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: #6c757d;
        }

        .badge {
            display: inline-block;
            padding: 3px 6px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            border-radius: 4px;
            color: white;
            /* margin: 15px; */
        }

        .bg-success { background-color: #28a745; }
        .bg-warning { background-color: #ffc107; color: #212529; }
        .bg-danger { background-color: #dc3545; }
        .bg-secondary { background-color: #6c757d; }
        .bg-info { background-color: #17a2b8; }
        .bg-dark { background-color: #343a40; }

        .table-sm th,
        .table-sm td {
            padding: 8px;
            font-size: 11px;
        }

        .py-4 {
            padding: 20px 0;
        }

        .text-center {
            text-align: center;
        }

        .justify-content-end {
            justify-content: flex-end;
        }

        .col-md-4 {
            flex: 0 0 33.333333%;
            padding: 0 10px;
        }

        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card-header">
            <h4>Invoice #{{ $invoice->invoice_number }}</h4>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6>Customer Information</h6>
                    <p><strong>{{ $invoice->customer->name }}</strong></p>
                    <p>{{ $invoice->customer->email }}</p>
                    @if($invoice->customer->phone)
                        <p>{{ $invoice->customer->phone }}</p>
                    @endif
                    @if($invoice->customer->address)
                        <p>{{ $invoice->customer->address }}</p>
                    @endif
                </div>
                <div class="col-md-6 text-end">
                    <h6>Invoice Details</h6>
                    <p><strong>Invoice Date:</strong> {{ $invoice->invoice_date->format('M d, Y') }}</p>
                    <p><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</p>
                    <p><strong>Status:</strong>
                        @php
                            $statusColors = [
                                'draft' => 'secondary',
                                'sent' => 'info',
                                'paid' => 'success',
                                'overdue' => 'danger',
                                'cancelled' => 'dark'
                            ];
                            $statusColor = $statusColors[$invoice->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $statusColor }}">{{ ucfirst($invoice->status) }}</span>
                    </p>
                </div>
            </div>

            @if($invoice->items->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Description</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-center">Tax Rate</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->product->name }}</strong>
                                    @if($item->product->sku)
                                        <br><small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                    @endif
                                </td>
                                <td>{{ $item->description ?? $item->product->description }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">{{ $invoice->currency->symbol }}{{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-center">{{ $item->tax_rate }}%</td>
                                <td class="text-end">{{ $invoice->currency->symbol }}{{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end">
                    <div class="col-md-4">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Subtotal:</strong></td>
                                <td class="text-end">{{ $invoice->currency->symbol }}{{ number_format($invoice->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tax:</strong></td>
                                <td class="text-end">{{ $invoice->currency->symbol }}{{ number_format($invoice->tax_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total:</strong></td>
                                <td class="text-end"><strong>{{ $invoice->currency->symbol }}{{ number_format($invoice->total, 2) }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            @else
                <div class="text-center py-4">
                    <h5>No items found for this invoice</h5>
                </div>
            @endif

            @if($invoice->notes)
                <div class="mt-4">
                    <h6>Notes</h6>
                    <p>{{ $invoice->notes }}</p>
                </div>
            @endif

            @if($invoice->payments->count() > 0)
                <div class="mt-4">
                    <h6>Payment History</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Reference</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td>{{ $invoice->currency->symbol }}{{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ ucfirst($payment->payment_method) }}</td>
                                    <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
