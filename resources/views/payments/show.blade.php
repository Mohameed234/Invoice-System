@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Payment Details</h4>
                    <div>
                        @can('edit payments')
                        <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning">Edit</a>
                        @endcan
                        <a href="{{ route('payments.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Invoice</h6>
                        <p>
                            @if($payment->invoice)
                                <a href="{{ route('invoices.show', $payment->invoice) }}">
                                    #{{ $payment->invoice->invoice_number }} - {{ $payment->invoice->customer->name }}
                                </a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <h6>Payment Date</h6>
                        <p>{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : '-' }}</p>
                    </div>
                    <div class="mb-3">
                        <h6>Amount</h6>
                        <p>{{ $payment->invoice->currency->symbol ?? '$' }}{{ number_format($payment->amount, 2) }}</p>
                    </div>
                    <div class="mb-3">
                        <h6>Payment Method</h6>
                        <p>{{ ucfirst($payment->payment_method) }}</p>
                    </div>
                    <div class="mb-3">
                        <h6>Reference</h6>
                        <p>{{ $payment->reference ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <h6>Status</h6>
                        <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : 'warning' }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
