@extends('layouts.app')

@section('content')
<div class="payments-page">
    <div class="payments-container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-credit-card me-2"></i>Payments</h4>
                @can('create payments')
                <a href="{{ route('payments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Payment
                </a>
                @endcan
            </div>
            <div class="card-body">
                @if($payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                <tr>
                                    <td>
                                        <div class="payment-invoice">
                                            <strong>{{ $payment->invoice->invoice_number ?? '-' }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $payment->invoice->customer->name ?? '-' }}</td>
                                    <td>{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : '-' }}</td>
                                    <td>
                                        <strong>{{ $payment->invoice->currency->symbol ?? '$' }}{{ number_format($payment->amount, 2) }}</strong>
                                    </td>
                                    <td>{{ ucfirst($payment->payment_method) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('view payments')
                                            <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endcan
                                            @can('edit payments')
                                            <a href="{{ route('payments.edit', $payment) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endcan
                                            @can('delete payments')
                                            <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $payments->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                        <h5>No payments found</h5>
                        <p class="text-muted">Get started by adding your first payment</p>
                        @can('create payments')
                        <a href="{{ route('payments.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add your first payment
                        </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
