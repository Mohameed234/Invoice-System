@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Product Details</h4>
                    <div>
                        @can('edit products')
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edit</a>
                        @endcan
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Basic Information</h6>
                            <p><strong>Name:</strong> {{ $product->name }}</p>
                            <p><strong>SKU:</strong> {{ $product->sku ?? 'N/A' }}</p>
                            <p><strong>Type:</strong>
                                <span class="badge bg-{{ $product->type === 'service' ? 'info' : 'success' }}">
                                    {{ ucfirst($product->type) }}
                                </span>
                            </p>
                            @if($product->category)
                                <p><strong>Category:</strong> {{ $product->category->name }}</p>
                            @endif
                            <p><strong>Status:</strong>
                                <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Pricing Information</h6>
                            <p><strong>Unit Price:</strong> ${{ number_format($product->unit_price, 2) }}</p>
                            <p><strong>Tax Rate:</strong> {{ $product->tax_rate }}%</p>
                            @if($product->cost_price)
                                <p><strong>Cost Price:</strong> ${{ number_format($product->cost_price, 2) }}</p>
                            @endif
                            @if($product->stock_quantity !== null)
                                <p><strong>Stock Quantity:</strong> {{ $product->stock_quantity }}</p>
                            @endif
                        </div>
                    </div>

                    @if($product->description)
                        <div class="mb-4">
                            <h6>Description</h6>
                            <p>{{ $product->description }}</p>
                        </div>
                    @endif

                    <hr>

                    <h5>Product Usage in Invoices</h5>
                    @php
                        $invoiceItems = $product->invoiceItems()->with(['invoice.customer'])->orderBy('created_at', 'desc')->get();
                    @endphp

                    @if($invoiceItems->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Customer</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoiceItems as $item)
                                    <tr>
                                        <td>{{ $item->invoice->invoice_number }}</td>
                                        <td>{{ $item->invoice->customer->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->unit_price, 2) }}</td>
                                        <td>${{ number_format($item->total, 2) }}</td>
                                        <td>{{ $item->invoice->invoice_date->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6>Total Usage</h6>
                                        <h4>{{ $invoiceItems->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6>Total Revenue</h6>
                                        <h4>${{ number_format($invoiceItems->sum('total'), 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6>Total Quantity Sold</h6>
                                        <h4>{{ $invoiceItems->sum('quantity') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <h6>This product hasn't been used in any invoices yet</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
