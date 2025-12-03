@extends('layouts.app')

@section('content')
<style>
    @media print {
        .btn, .no-print {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .card-header {
            background: none !important;
            border-bottom: 2px solid #333 !important;
        }
        .card-body {
            padding: 0 !important;
        }
        body {
            margin: 0 !important;
            padding: 20px !important;
        }
        .container {
            max-width: none !important;
            width: 100% !important;
        }
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Invoice #{{ $invoice->invoice_number }}</h4>
                    <div>
                        <button onclick="window.print()" class="btn btn-info no-print">
                            <i class="fas fa-print"></i> Print
                        </button>
                        <a href="{{ route('invoices.pdf', $invoice) }}" class="btn btn-success no-print" target="_blank">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                        @can('edit invoices')
                        <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning no-print">Edit</a>
                        @endcan
                        <a href="{{ route('invoices.index') }}" class="btn btn-secondary no-print">Back to List</a>
                    </div>
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
                                            <td>{{ $payment->reference ?? 'N/A' }}</td>
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
        </div>
    </div>
</div>
@endsection
