@extends('layouts.app')

@section('content')
<div class="customers-page">
    <div class="customers-container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-users me-2"></i>Customers</h4>
                @can('create customers')
                <a href="{{ route('customers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Customer
                </a>
                @endcan
            </div>
            <div class="card-body">
                @if($customers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $customer)
                                <tr>
                                    <td>
                                        <div class="customer-name">
                                            <strong>{{ $customer->name }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $customer->email ?? 'N/A' }}</td>
                                    <td>{{ $customer->phone ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $customer->type === 'company' ? 'primary' : 'secondary' }}">
                                            {{ ucfirst($customer->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $customer->is_active ? 'success' : 'danger' }}">
                                            {{ $customer->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('view customers')
                                            <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endcan
                                            @can('edit customers')
                                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endcan
                                            @can('delete customers')
                                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline">
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
                        {{ $customers->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5>No customers found</h5>
                        <p class="text-muted">Get started by adding your first customer</p>
                        @can('create customers')
                        <a href="{{ route('customers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add your first customer
                        </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
