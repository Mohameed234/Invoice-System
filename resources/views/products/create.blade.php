@extends('layouts.app')

@section('content')
<div class="products-page form-page">
    <div class="products-container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-box-open me-2"></i>Add New Product</h4>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Products
                </a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('products.store') }}">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label for="name" class="form-label">
                                <i class="fas fa-box me-2"></i>Product Name *
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="sku" class="form-label">
                                <i class="fas fa-barcode me-2"></i>SKU
                            </label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                   id="sku" name="sku" value="{{ old('sku') }}">
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="category_id" class="form-label">
                                <i class="fas fa-tags me-2"></i>Category
                            </label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                <option value="">Select Category</option>
                                @foreach(\App\Models\Category::where('is_active', true)->orderBy('name')->get() as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="type" class="form-label">
                                <i class="fas fa-tag me-2"></i>Type *
                            </label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="product" {{ old('type') == 'product' ? 'selected' : '' }}>Product</option>
                                <option value="service" {{ old('type') == 'service' ? 'selected' : '' }}>Service</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="unit_price" class="form-label">
                                <i class="fas fa-dollar-sign me-2"></i>Unit Price *
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('unit_price') is-invalid @enderror"
                                       id="unit_price" name="unit_price" value="{{ old('unit_price') }}"
                                       step="0.01" min="0" required>
                            </div>
                            @error('unit_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tax_rate" class="form-label">
                                <i class="fas fa-percentage me-2"></i>Tax Rate (%)
                            </label>
                            <input type="number" class="form-control @error('tax_rate') is-invalid @enderror"
                                   id="tax_rate" name="tax_rate" value="{{ old('tax_rate', 0) }}"
                                   step="0.01" min="0" max="100">
                            @error('tax_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">
                            <i class="fas fa-align-left me-2"></i>Description
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="cost_price" class="form-label">
                                <i class="fas fa-coins me-2"></i>Cost Price
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('cost_price') is-invalid @enderror"
                                       id="cost_price" name="cost_price" value="{{ old('cost_price') }}"
                                       step="0.01" min="0">
                            </div>
                            @error('cost_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="stock_quantity" class="form-label">
                                <i class="fas fa-warehouse me-2"></i>Stock Quantity
                            </label>
                            <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                   id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}"
                                   min="0" step="1">
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <i class="fas fa-check-circle me-2"></i>Active
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between pt-3">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
