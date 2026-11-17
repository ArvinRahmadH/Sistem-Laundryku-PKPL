@extends('layouts.main')

@section('title', 'Daftar Customer')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="fas fa-users me-2"></i>Daftar Customer</h2>
            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Customer
            </a>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('customers.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Cari nama / telepon / email..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" name="tier">
                            <option value="">Semua Tier</option>
                            <option value="regular" {{ request('tier') == 'regular' ? 'selected' : '' }}>Regular</option>
                            <option value="silver" {{ request('tier') == 'silver' ? 'selected' : '' }}>Silver</option>
                            <option value="gold" {{ request('tier') == 'gold' ? 'selected' : '' }}>Gold</option>
                            <option value="platinum" {{ request('tier') == 'platinum' ? 'selected' : '' }}>Platinum</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i>Cari
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-redo me-1"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Customer -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($customers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Tier</th>
                                    <th>Diskon</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $customer)
                                    <tr>
                                        <td>#{{ $customer->id }}</td>
                                        <td><strong>{{ $customer->name }}</strong></td>
                                        <td>{{ $customer->phone }}</td>
                                        <td>{{ $customer->email ?? '-' }}</td>
                                        <td>{!! $customer->tier_badge !!}</td>
                                        <td>
                                            <span class="badge bg-success">
                                                {{ $customer->discount_percent }}%
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('customers.show', $customer->id) }}" 
                                                   class="btn btn-sm btn-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('customers.edit', $customer->id) }}" 
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal{{ $customer->id }}"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal{{ $customer->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Yakin ingin menghapus customer <strong>{{ $customer->name }}</strong>?</p>
                                                            <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan!</small></p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $customers->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada customer</h5>
                        <p class="text-muted">Mulai tambahkan customer pertama Anda.</p>
                        <a href="{{ route('customers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tambah Customer
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection