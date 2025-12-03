@extends('layouts.app')

@section('content')
<div class="customers-page form-page">
    <div class="customers-container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-user-edit me-2"></i>Edit Customer</h4>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Customers
                </a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('customers.update', $customer) }}">
                    @csrf
                    @method('PUT')

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">
                                <i class="fas fa-user me-2"></i>Name *
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $customer->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Email *
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $customer->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone me-2"></i>Phone
                            </label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone', $customer->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="company_name" class="form-label">
                                <i class="fas fa-building me-2"></i>Company Name
                            </label>
                            <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                   id="company_name" name="company_name" value="{{ old('company_name', $customer->company_name) }}">
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="form-label">
                            <i class="fas fa-map-marker-alt me-2"></i>Address
                        </label>
                        <textarea class="form-control @error('address') is-invalid @enderror"
                                  id="address" name="address" rows="3">{{ old('address', $customer->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="city" class="form-label">
                                <i class="fas fa-city me-2"></i>City
                            </label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror"
                                   id="city" name="city" value="{{ old('city', $customer->city) }}">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="state" class="form-label">
                                <i class="fas fa-map me-2"></i>State/Province
                            </label>
                            <input type="text" class="form-control @error('state') is-invalid @enderror"
                                   id="state" name="state" value="{{ old('state', $customer->state) }}">
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="postal_code" class="form-label">
                                <i class="fas fa-mail-bulk me-2"></i>Postal Code
                            </label>
                            <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                   id="postal_code" name="postal_code" value="{{ old('postal_code', $customer->postal_code) }}">
                            @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="country" class="form-label">
                                <i class="fas fa-globe me-2"></i>Country
                            </label>
                            <input type="text" class="form-control @error('country') is-invalid @enderror"
                                   id="country" name="country" value="{{ old('country', $customer->country) }}">
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tax_number" class="form-label">
                                <i class="fas fa-receipt me-2"></i>Tax Number
                            </label>
                            <input type="text" class="form-control @error('tax_number') is-invalid @enderror"
                                   id="tax_number" name="tax_number" value="{{ old('tax_number', $customer->tax_number) }}">
                            @error('tax_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note me-2"></i>Notes
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3">{{ old('notes', $customer->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $customer->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <i class="fas fa-check-circle me-2"></i>Active
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between pt-3">
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
