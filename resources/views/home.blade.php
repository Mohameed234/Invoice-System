@extends('layouts.app')

@section('content')
<div class="dashboard">
    <!-- Welcome Section -->
    <div class="welcome-section mb-4">
        <h1 class="welcome-title">
            <i class="fas fa-sun me-3"></i>
            Welcome back, {{ Auth::user()->name }}!
        </h1>
        <p class="welcome-subtitle">Here's what's happening with your business today.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card stat-card-customers">
                <div class="stat-card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-card-content">
                    <h3 class="stat-card-number">{{ number_format($stats['total_customers']) }}</h3>
                    <p class="stat-card-label">Total Customers</p>
                                </div>
                            </div>
                        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card stat-card-products">
                <div class="stat-card-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-card-content">
                    <h3 class="stat-card-number">{{ number_format($stats['total_products']) }}</h3>
                    <p class="stat-card-label">Total Products</p>
                                </div>
                            </div>
                        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card stat-card-invoices">
                <div class="stat-card-icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div class="stat-card-content">
                    <h3 class="stat-card-number">{{ number_format($stats['total_invoices']) }}</h3>
                    <p class="stat-card-label">Total Invoices</p>
                                </div>
                            </div>
                        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card stat-card-revenue">
                <div class="stat-card-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-card-content">
                    <h3 class="stat-card-number">${{ number_format($stats['total_revenue'], 2) }}</h3>
                    <p class="stat-card-label">Total Revenue</p>
                                </div>
                            </div>
                        </div>
                    </div>

    <!-- Invoice Status Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="status-card status-card-pending">
                <div class="status-card-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="status-card-content">
                    <h4 class="status-card-number">{{ number_format($stats['pending_invoices']) }}</h4>
                    <p class="status-card-label">Pending Invoices</p>
                                </div>
                            </div>
                        </div>

        <div class="col-xl-4 col-md-6 mb-3">
            <div class="status-card status-card-paid">
                <div class="status-card-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="status-card-content">
                    <h4 class="status-card-number">{{ number_format($stats['paid_invoices']) }}</h4>
                    <p class="status-card-label">Paid Invoices</p>
                                </div>
                            </div>
                        </div>

        <div class="col-xl-4 col-md-6 mb-3">
            <div class="status-card status-card-overdue">
                <div class="status-card-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="status-card-content">
                    <h4 class="status-card-number">{{ number_format($stats['overdue_invoices']) }}</h4>
                    <p class="status-card-label">Overdue Invoices</p>
                                </div>
                            </div>
                        </div>
                    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-xl-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-invoice me-2"></i>
                        Recent Invoices
                    </h5>
                </div>
                <div class="card-body">
                    @if($recent_invoices->count() > 0)
                        <div class="recent-list">
                            @foreach($recent_invoices as $invoice)
                                <div class="recent-item">
                                    <div class="recent-item-icon">
                                        <i class="fas fa-file-invoice"></i>
                                    </div>
                                    <div class="recent-item-content">
                                        <h6 class="recent-item-title">
                                            Invoice #{{ $invoice->invoice_number }}
                                        </h6>
                                        <p class="recent-item-subtitle">
                                            {{ $invoice->customer->name ?? 'N/A' }}
                                        </p>
                                        <span class="recent-item-meta">
                                            {{ $invoice->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <div class="recent-item-status">
                                        <span class="badge badge-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($invoice->status) }}
                                                    </span>
                                    </div>
                                </div>
                                            @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent invoices</p>
                                </div>
                            @endif
                        </div>
                    </div>
        </div>

        <div class="col-xl-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-credit-card me-2"></i>
                        Recent Payments
                    </h5>
                </div>
                <div class="card-body">
                    @if($recent_payments->count() > 0)
                        <div class="recent-list">
                            @foreach($recent_payments as $payment)
                                <div class="recent-item">
                                    <div class="recent-item-icon">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <div class="recent-item-content">
                                        <h6 class="recent-item-title">
                                            ${{ number_format($payment->amount, 2) }}
                                        </h6>
                                        <p class="recent-item-subtitle">
                                            Invoice #{{ $payment->invoice->invoice_number ?? 'N/A' }}
                                        </p>
                                        <span class="recent-item-meta">
                                            {{ $payment->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <div class="recent-item-status">
                                        <span class="badge badge-success">
                                            Paid
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent payments</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
