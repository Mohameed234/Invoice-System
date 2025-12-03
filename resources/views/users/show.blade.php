@extends('layouts.app')

@section('content')
<div class="container py-5" style="background:#f5f6fa;min-height:100vh;">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-4 text-center">
                    <div class="mb-4">
                        <div class="mx-auto mb-3" style="width:80px;height:80px;background:#e5e7eb;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-user fa-3x text-primary"></i>
                        </div>
                        <h2 class="fw-bold mb-1" style="font-size:2rem;">{{ $user->name }}</h2>
                        <div class="text-muted mb-2" style="font-size:1.1rem;">{{ $user->roles->pluck('name')->join(', ') ?: 'User' }}</div>
                    </div>
                    <div class="info-section text-start mx-auto" style="max-width:350px;">
                        <div class="info-item d-flex align-items-center mb-3">
                            <i class="fas fa-envelope text-primary me-3"></i>
                            <div><strong>Email:</strong> {{ $user->email }}</div>
                        </div>
                        <div class="info-item d-flex align-items-center mb-3">
                            <i class="fas fa-calendar-alt text-primary me-3"></i>
                            <div><strong>Registered:</strong> {{ $user->created_at->format('F j, Y') }}</div>
                        </div>
                        <div class="info-item d-flex align-items-center mb-3">
                            <i class="fas fa-check-circle text-primary me-3"></i>
                            <div><strong>Email Verified:</strong> {{ $user->email_verified_at ? $user->email_verified_at->format('F j, Y') : 'No' }}</div>
                        </div>
                    </div>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-primary mt-4 px-4">Back to Users</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
