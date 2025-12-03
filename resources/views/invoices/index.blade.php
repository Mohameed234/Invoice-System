@extends('layouts.app')

@section('content')
<div class="invoices-page">
    <div class="invoices-container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-file-invoice me-2"></i>Invoices</h4>
                @can('create invoices')
                <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Invoice
                </a>
                @endcan
            </div>
            <div class="card-body">
                @if($invoices->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped" style="font-size:0.95rem;">
                            <thead>
                                <tr>
                                    <th style="max-width:120px;padding:0.5rem 0.5rem;">Invoice #</th>
                                    <th style="min-width:110px;max-width:150px;padding:0.5rem 0.5rem;">Customer</th>
                                    <th style="min-width:90px;max-width:110px;padding:0.5rem 0.5rem;">Date</th>
                                    <th style="min-width:90px;max-width:110px;padding:0.5rem 0.5rem;">Due Date</th>
                                    <th style="min-width:80px;max-width:100px;padding:0.5rem 0.5rem;">Total</th>
                                    <th style="min-width:80px;max-width:100px;padding:0.5rem 0.5rem;">Paid</th>
                                    <th style="min-width:80px;max-width:100px;padding:0.5rem 0.5rem;">Balance</th>
                                    <th style="min-width:110px;max-width:130px;padding:0.5rem 0.5rem;">Payment Status</th>
                                    <th style="min-width:110px;max-width:130px;padding:0.5rem 0.5rem;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $invoice)
                                <tr>
                                    <td>
                                        <div class="invoice-number">
                                            <strong>{{ $invoice->invoice_number }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $invoice->customer->name }}</td>
                                    <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                                    <td>{{ $invoice->due_date->format('M d, Y') }}</td>
                                    <td>
                                        <strong>{{ $invoice->currency->symbol }}{{ number_format($invoice->total, 2) }}</strong>
                                    </td>
                                    <td>{{ $invoice->currency->symbol }}{{ number_format($invoice->paid_amount, 2) }}</td>
                                    <td>{{ $invoice->currency->symbol }}{{ number_format($invoice->balance, 2) }}</td>
                                    <td>
                                        <span class="{{ $invoice->getPaymentStatusBadgeClass() }}">
                                            {{ $invoice->getPaymentStatusText() }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('view invoices')
                                            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endcan
                                            @can('edit invoices')
                                            <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endcan
                                            @if($invoice->balance > 0)
                                                @can('create payments')
                                                <a href="{{ route('payments.create') }}" class="btn btn-sm btn-success">
                                                    <i class="fas fa-credit-card"></i>
                                                </a>
                                                @endcan
                                            @endif
                                            @can('delete invoices')
                                            <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline">
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
                        {{ $invoices->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                        <h5>No invoices found</h5>
                        <p class="text-muted">Get started by creating your first invoice</p>
                        @can('create invoices')
                        <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create your first invoice
                        </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
