@extends('layouts.main')

@section('title', 'Orders List')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="fas fa-list me-2"></i>Orders List</h2>
            <a href="/orders/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Order Baru
            </a>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="/orders" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="orderBy">
                            <option value="created_at" {{ request('orderBy') == 'created_at' ? 'selected' : '' }}>Date</option>
                            <option value="customer_name" {{ request('orderBy') == 'customer_name' ? 'selected' : '' }}>Customer</option>
                            <option value="price" {{ request('orderBy') == 'price' ? 'selected' : '' }}>Price</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="sortBy">
                            <option value="desc" {{ request('sortBy') == 'desc' ? 'selected' : '' }}>Descending</option>
                            <option value="asc" {{ request('sortBy') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="limit">
                            <option value="10" {{ request('limit', 10) == 10 ? 'selected' : '' }}>10 per page</option>
                            <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25 per page</option>
                            <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50 per page</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Phone</th>
                                    <th>Service</th>
                                    <th>Status</th>
                                    <th>Weight</th>
                                    <th>Price</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td>{{ $order->phone ?? '-' }}</td>
                                        <td>
                                            @php
                                                $services = [
                                                    'kiloan' => 'Kiloan',
                                                    'dry_cleaning' => 'Dry Cleaning',
                                                    'antar_jemput' => 'Antar Jemput',
                                                    'setrika_premium' => 'Setrika Premium'
                                                ];
                                            @endphp
                                            <span class="badge bg-info">{{ $services[$order->service_type] ?? $order->service_type }}</span>
                                        </td>
                                        <td>
                                            <span class="status-badge {{ $order->status }}">
                                                @php
                                                    $statuses = [
                                                        'pending' => 'Pending',
                                                        'in_progress' => 'In Progress',
                                                        'done' => 'Done',
                                                        'delivered' => 'Delivered'
                                                    ];
                                                @endphp
                                                {{ $statuses[$order->status] ?? $order->status }}
                                            </span>
                                        </td>
                                        <td>{{ $order->weight }} kg</td>
                                        <td>Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="/orders/{{ $order->id }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="/orders/{{ $order->id }}/edit" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal{{ $order->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $order->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus order <strong>{{ $order->customer_name }}</strong>?</p>
                                                    <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan!</small></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form action="/orders/{{ $order->id }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} entries
                        </div>
                        <nav>
                            {{ $orders->links() }}
                        </nav>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada order yang ditemukan</p>
                        <a href="/orders/create" class="btn btn-primary">Buat Order Baru</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection