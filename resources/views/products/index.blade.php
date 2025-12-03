@extends('layouts.app')

@section('content')
<div class="products-page">
    <div class="products-container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-box me-2"></i>Products</h4>
                @can('create products')
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Product
                </a>
                @endcan
            </div>
            <div class="card-body">
                @if($products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Price</th>
                                    <th>Tax Rate</th>
                                    <th>SKU</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>
                                        <div class="product-name">
                                            <strong>{{ $product->name }}</strong>
                                            @if($product->description)
                                                <br><small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($product->category)
                                            <span class="badge bg-secondary">{{ $product->category->name }}</span>
                                        @else
                                            <span class="text-muted">No category</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $product->type === 'service' ? 'info' : 'success' }}">
                                            {{ ucfirst($product->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>${{ number_format($product->unit_price, 2) }}</strong>
                                    </td>
                                    <td>{{ $product->tax_rate }}%</td>
                                    <td>{{ $product->sku ?? 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('view products')
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endcan
                                            @can('edit products')
                                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endcan
                                            @can('delete products')
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
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
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                        <h5>No products found</h5>
                        <p class="text-muted">Get started by adding your first product</p>
                        @can('create products')
                        <a href="{{ route('products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add your first product
                        </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
