@extends('layouts.main')

@section('title', 'Tambah Customer')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i>Tambah Customer Baru</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Customer <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="2">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tier" class="form-label">Tier Member <span class="text-danger">*</span></label>
                        <select class="form-control @error('tier') is-invalid @enderror" 
                                id="tier" name="tier" required>
                            <option value="regular" {{ old('tier') == 'regular' ? 'selected' : '' }}>
                                Regular (Diskon 0%)
                            </option>
                            <option value="silver" {{ old('tier') == 'silver' ? 'selected' : '' }}>
                                Silver (Diskon 5%)
                            </option>
                            <option value="gold" {{ old('tier') == 'gold' ? 'selected' : '' }}>
                                Gold (Diskon 10%)
                            </option>
                            <option value="platinum" {{ old('tier') == 'platinum' ? 'selected' : '' }}>
                                Platinum (Diskon 15%)
                            </option>
                        </select>
                        @error('tier')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection