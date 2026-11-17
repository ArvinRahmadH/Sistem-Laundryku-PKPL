@extends('layouts.main')

@section('title', 'Reset Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="fas fa-lock me-2"></i>Reset Password</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-envelope me-1"></i>
                    <strong>Email:</strong> {{ $email }}
                </div>

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-1"></i>Password Baru
                        </label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Minimal 6 karakter"
                               required autofocus>
                        @error('password')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                        <small class="text-muted">Minimal 6 karakter</small>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock me-1"></i>Konfirmasi Password
                        </label>
                        <input type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Ulangi password baru"
                               required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Simpan Password Baru
                        </button>
                        <a href="{{ route('password.request') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Ganti Email
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection