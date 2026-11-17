@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>
            <a href="/orders/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Order Baru
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Pending</h5>
                        <h2 class="mb-0">{{ $stats['pending'] ?? 0 }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">In Progress</h5>
                        <h2 class="mb-0">{{ $stats['in_progress'] ?? 0 }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-spinner fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Done</h5>
                        <h2 class="mb-0">{{ $stats['done'] ?? 0 }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Total</h5>
                        <h2 class="mb-0">{{ $stats['total'] ?? 0 }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-tshirt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Orders</h5>
            </div>
            <div class="card-body">
                @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Service</th>
                                    <th>Status</th>
                                    <th>Weight (kg)</th>
                                    <th>Total</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            {{ $order->customer_name }}
                                            @if($order->customer)
                                                <br><small>{!! $order->customer->tier_badge !!}</small>
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
                                            {{ $services[$order->service_type] ?? $order->service_type }}
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
                                        <td>{{ $order->weight }}</td>
                                        <td>
                                            {{-- ✅ TOTAL PRICE, BUKAN HARGA PER KG --}}
                                            <strong>
                                                Rp {{ number_format($order->total_price ?? ($order->weight * $order->price), 0, ',', '.') }}
                                            </strong>
                                            @if(($order->discount_amount ?? 0) > 0)
                                                <br><small class="text-danger">
                                                    <i class="fas fa-tag"></i> Diskon {{ $order->discount_percent }}%
                                                </small>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="/orders/{{ $order->id }}" class="btn btn-sm btn-info" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/orders/{{ $order->id }}/invoice" class="btn btn-sm btn-success" title="Invoice" target="_blank">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-tshirt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada order yang dibuat</p>
                        <a href="/orders/create" class="btn btn-primary">Buat Order Pertama</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection