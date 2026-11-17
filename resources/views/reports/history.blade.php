@extends('layouts.main')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="fas fa-history me-2"></i>Riwayat Transaksi</h2>
            <a href="{{ route('orders.list') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Order
            </a>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-1">Total Transaksi</h6>
                        <h2 class="mb-0">{{ $totalTransactions }}</h2>
                        <small>transaksi selesai</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-receipt fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-1">Total Pendapatan</h6>
                        <h2 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                        <small>dari transaksi selesai</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filter & Pencarian</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('reports.history') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="start_date" 
                               value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="end_date" 
                               value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Status</label>
                        <select class="form-control" name="status">
                            <option value="">Semua Status</option>
                            <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Cari</label>
                        <input type="text" class="form-control" name="search" 
                               placeholder="Nama / ID..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 me-1">
                            <i class="fas fa-search me-1"></i>Cari
                        </button>
                        <a href="{{ route('reports.history') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Riwayat -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Transaksi Selesai</h6>
                <span class="badge bg-primary">{{ $orders->total() }} transaksi</span>
            </div>
            <div class="card-body">
                @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Layanan</th>
                                    <th>Berat</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td><strong>#{{ $order->id }}</strong></td>
                                        <td>
                                            {{ $order->customer_name }}
                                            @if($order->phone)
                                                <br><small class="text-muted">{{ $order->phone }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $services = [
                                                    'kiloan' => 'Kiloan',
                                                    'dry_cleaning' => 'Dry Cleaning',
                                                    'antar_jemput' => 'Antar Jemput',
                                                    'setrika_premium' => 'Setrika Premium'
                                                ];
                                            @endphp
                                            <span class="badge bg-info">
                                                {{ $services[$order->service_type] ?? $order->service_type }}
                                            </span>
                                        </td>
                                        <td>{{ $order->weight }} kg</td>
                                        <td><strong>Rp {{ number_format($order->price, 0, ',', '.') }}</strong></td>
                                        <td>
                                            <span class="status-badge {{ $order->status }}">
                                                @php
                                                    $statuses = [
                                                        'done' => 'Done',
                                                        'delivered' => 'Delivered'
                                                    ];
                                                @endphp
                                                {{ $statuses[$order->status] ?? $order->status }}
                                            </span>
                                        </td>
                                        <td>{{ $order->updated_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('orders.show', $order->id) }}" 
                                                   class="btn btn-sm btn-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('orders.invoice', $order->id) }}" 
                                                   class="btn btn-sm btn-success" title="Invoice">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <small class="text-muted">
                                Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} 
                                dari {{ $orders->total() }} transaksi
                            </small>
                        </div>
                        <nav>
                            {{ $orders->appends(request()->query())->links() }}
                        </nav>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada transaksi selesai</h5>
                        <p class="text-muted">Riwayat transaksi akan muncul setelah ada order dengan status <strong>done</strong> atau <strong>delivered</strong>.</p>
                        <a href="{{ route('reports.history') }}" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i>Lihat Semua Order
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection