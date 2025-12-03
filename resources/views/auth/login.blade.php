@extends('layouts.guest')

@section('content')
<div class="login-bg" style="min-height:100vh;background:#f5f6fa;display:flex;align-items:center;justify-content:center;">
    <div class="login-card shadow-lg rounded-4 overflow-hidden" style="max-width:480px;width:100%;background:#fff;">
        <div class="w-100" style="background:#111;padding:2.5rem 2.5rem 2rem 2.5rem;display:flex;flex-direction:column;align-items:center;justify-content:center;">
            <div class="mb-4 text-center">
                <i class="fas fa-file-invoice-dollar fa-4x" style="color:#00a3ff;"></i>
                <h2 class="mt-3 mb-0" style="color:#fff;font-weight:700;letter-spacing:1px;font-size:2rem;">FINVOICE</h2>
                <div class="mt-1" style="color:#bdbdbd;font-size:1.1rem;">Welcome Back!</div>
                <div class="mb-0" style="color:#bdbdbd;font-size:1rem;">Sign in to continue to Finvoice.</div>
            </div>
            <form method="POST" action="{{ route('login') }}" class="w-100" style="max-width:300px;">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold" style="font-size:1.05rem;color:#fff;">Email address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-envelope text-primary"></i></span>
                        <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus style="border-radius:0 8px 8px 0; font-size:1.05rem;">
                        @error('email')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label fw-bold" style="font-size:1.05rem;color:#fff;">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-lock text-primary"></i></span>
                        <input id="password" type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" style="border-radius:0 8px 8px 0; font-size:1.05rem;">
                        @error('password')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="mb-2 form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember" style="font-size:0.98rem;color:#fff;">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2" style="font-size:1.08rem;border-radius:8px;">Sign In</button>
                <div class="mt-2 text-center">
                    @if (Route::has('password.request'))
                        <a class="text-decoration-none text-primary" href="{{ route('password.request') }}" style="font-size:0.98rem;">Forgot Your Password?</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
