@extends('layouts.app')

@section('content')
<div class="customers-page">
    <div class="customers-container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-user me-2"></i>Customer Details</h4>
                <div>
                    @can('edit customers')
                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    @endcan
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="info-section">
                            <h6><i class="fas fa-address-card me-2"></i>Contact Information</h6>
                            <div class="info-item">
                                <i class="fas fa-user me-2"></i>
                                <strong>Name:</strong> {{ $customer->name }}
                            </div>
                            <div class="info-item">
                                <i class="fas fa-envelope me-2"></i>
                                <strong>Email:</strong> {{ $customer->email }}
                            </div>
                            @if($customer->phone)
                                <div class="info-item">
                                    <i class="fas fa-phone me-2"></i>
                                    <strong>Phone:</strong> {{ $customer->phone }}
                                </div>
                            @endif
                            @if($customer->company_name)
                                <div class="info-item">
                                    <i class="fas fa-building me-2"></i>
                                    <strong>Company:</strong> {{ $customer->company_name }}
                                </div>
                            @endif
                            @if($customer->tax_number)
                                <div class="info-item">
                                    <i class="fas fa-receipt me-2"></i>
                                    <strong>Tax Number:</strong> {{ $customer->tax_number }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-section">
                            <h6><i class="fas fa-map-marker-alt me-2"></i>Address Information</h6>
                            @if($customer->address)
                                <div class="info-item">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    <strong>Address:</strong><br>{{ $customer->address }}
                                </div>
                            @endif
                            @if($customer->city)
                                <div class="info-item">
                                    <i class="fas fa-city me-2"></i>
                                    <strong>City:</strong> {{ $customer->city }}
                                </div>
                            @endif
                            @if($customer->state)
                                <div class="info-item">
                                    <i class="fas fa-map me-2"></i>
                                    <strong>State/Province:</strong> {{ $customer->state }}
                                </div>
                            @endif
                            @if($customer->postal_code)
                                <div class="info-item">
                                    <i class="fas fa-mail-bulk me-2"></i>
                                    <strong>Postal Code:</strong> {{ $customer->postal_code }}
                                </div>
                            @endif
                            @if($customer->country)
                                <div class="info-item">
                                    <i class="fas fa-globe me-2"></i>
                                    <strong>Country:</strong> {{ $customer->country }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($customer->notes)
                    <div class="mb-4">
                        <div class="info-section">
                            <h6><i class="fas fa-sticky-note me-2"></i>Notes</h6>
                            <div class="info-item">
                                <i class="fas fa-sticky-note me-2"></i>
                                {{ $customer->notes }}
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mb-4">
                    <span class="badge bg-{{ $customer->is_active ? 'success' : 'danger' }} fs-6">
                        <i class="fas fa-{{ $customer->is_active ? 'check-circle' : 'times-circle' }} me-2"></i>
                        {{ $customer->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <hr>

                <h5><i class="fas fa-file-invoice me-2"></i>Customer Invoices</h5>
                @php
                    $invoices = $customer->invoices()->with(['currency'])->orderBy('created_at', 'desc')->get();
                @endphp

                @if($invoices->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Date</th>
                                    <th>Due Date</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th>Payment Status</th>
                                    <th>Actions</th>
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
                                            @if($invoice->balance > 0)
                                                @can('create payments')
                                                <a href="{{ route('payments.create') }}" class="btn btn-sm btn-success">
                                                    <i class="fas fa-credit-card"></i>
                                                </a>
                                                @endcan
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="stat-card stat-card-customers">
                                <div class="stat-card-icon">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                <div class="stat-card-content">
                                    <div class="stat-card-number">{{ $invoices->count() }}</div>
                                    <div class="stat-card-label">Total Invoices</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card stat-card-revenue">
                                <div class="stat-card-icon">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div class="stat-card-content">
                                    <div class="stat-card-number">${{ number_format($invoices->sum('total'), 2) }}</div>
                                    <div class="stat-card-label">Total Amount</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card stat-card-paid">
                                <div class="stat-card-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-card-content">
                                    <div class="stat-card-number">${{ number_format($invoices->sum('paid_amount'), 2) }}</div>
                                    <div class="stat-card-label">Total Paid</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card stat-card-overdue">
                                <div class="stat-card-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="stat-card-content">
                                    <div class="stat-card-number">${{ number_format($invoices->sum('balance'), 2) }}</div>
                                    <div class="stat-card-label">Outstanding</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                        <h6>No invoices found for this customer</h6>
                        <p class="text-muted">Create the first invoice for this customer</p>
                        @can('create invoices')
                        <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create Invoice
                        </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
