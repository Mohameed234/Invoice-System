@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Invoice System Dashboard</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Customers</h5>
                                    <h3 class="card-text">{{ \App\Models\Customer::count() }}</h3>
                                    @can('view customers')
                                    <a href="{{ route('customers.index') }}" class="btn btn-light btn-sm">View All</a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Products</h5>
                                    <h3 class="card-text">{{ \App\Models\Product::count() }}</h3>
                                    @can('view products')
                                    <a href="{{ route('products.index') }}" class="btn btn-light btn-sm">View All</a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Invoices</h5>
                                    <h3 class="card-text">{{ \App\Models\Invoice::count() }}</h3>
                                    @can('view invoices')
                                    <a href="{{ route('invoices.index') }}" class="btn btn-light btn-sm">View All</a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Payments</h5>
                                    <h3 class="card-text">{{ \App\Models\Payment::count() }}</h3>
                                    @can('view payments')
                                    <a href="{{ route('payments.index') }}" class="btn btn-light btn-sm">View All</a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        @can('create customers')
                                        <a href="{{ route('customers.create') }}" class="btn btn-outline-primary">Add New Customer</a>
                                        @endcan
                                        @can('create products')
                                        <a href="{{ route('products.create') }}" class="btn btn-outline-success">Add New Product</a>
                                        @endcan
                                        @can('create invoices')
                                        <a href="{{ route('invoices.create') }}" class="btn btn-outline-warning">Create New Invoice</a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Recent Invoices</h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $recentInvoices = \App\Models\Invoice::with('customer')->orderBy('created_at', 'desc')->limit(5)->get();
                                    @endphp
                                    @if($recentInvoices->count() > 0)
                                        <div class="list-group list-group-flush">
                                            @foreach($recentInvoices as $invoice)
                                            <a href="{{ route('invoices.show', $invoice) }}" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1">{{ $invoice->invoice_number }}</h6>
                                                    <small>{{ $invoice->created_at->format('M d, Y') }}</small>
                                                </div>
                                                <p class="mb-1">{{ $invoice->customer->name }}</p>
                                                <small class="text-muted">{{ $invoice->currency->symbol }}{{ number_format($invoice->total, 2) }}</small>
                                            </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted">No invoices yet.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
