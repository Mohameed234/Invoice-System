@extends('layouts.app')

@section('content')
<div class="customers-page form-page">
    <div class="customers-container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-user-plus me-2"></i>Add New Customer</h4>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Customers
                </a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('customers.store') }}">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">
                                <i class="fas fa-user me-2"></i>Name *
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="type" class="form-label">
                                <i class="fas fa-tag me-2"></i>Type *
                            </label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="individual" {{ old('type') === 'individual' ? 'selected' : '' }}>Individual</option>
                                <option value="company" {{ old('type') === 'company' ? 'selected' : '' }}>Company</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Email
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone me-2"></i>Phone
                            </label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="form-label">
                            <i class="fas fa-map-marker-alt me-2"></i>Address
                        </label>
                        <textarea class="form-control @error('address') is-invalid @enderror"
                                  id="address" name="address" rows="3">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="tax_number" class="form-label">
                            <i class="fas fa-receipt me-2"></i>Tax Number (VAT)
                        </label>
                        <input type="text" class="form-control @error('tax_number') is-invalid @enderror"
                               id="tax_number" name="tax_number" value="{{ old('tax_number') }}">
                        @error('tax_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note me-2"></i>Notes
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between pt-3">
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
