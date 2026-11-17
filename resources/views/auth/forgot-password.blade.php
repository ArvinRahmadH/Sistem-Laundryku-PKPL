@extends('layouts.main')

@section('title', 'Lupa Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0"><i class="fas fa-key me-2"></i>Lupa Password</h4>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-4">
                    Masukkan email yang terdaftar untuk mereset password Anda.
                </p>

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-1"></i>Email
                        </label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="contoh@email.com"
                               required autofocus>
                        @error('email')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-arrow-right me-2"></i>Lanjut Reset Password
                        </button>
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection