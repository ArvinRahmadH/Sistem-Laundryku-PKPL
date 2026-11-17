@extends('layouts.main')

@section('title', 'Laporan Bulanan')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1"><i class="fas fa-chart-bar me-2"></i>Laporan Rekap Bulanan</h2>
                <p class="text-muted mb-0 small">
                    Periode: <strong>{{ $months[$month] }} {{ $year }}</strong>
                </p>
            </div>
            <a href="{{ route('reports.monthly.export', ['month' => $month, 'year' => $year]) }}" 
               class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf me-2"></i>Export PDF
            </a>
        </div>
    </div>
</div>

<!-- Filter Bulan & Tahun -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('reports.monthly') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small">Bulan</label>
                        <select class="form-control" name="month">
                            @foreach($months as $key => $name)
                                <option value="{{ $key }}" {{ $month == $key ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Tahun</label>
                        <select class="form-control" name="year">
                            @foreach($availableYears as $y)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i>Tampilkan Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-1">Total Transaksi</h6>
                        <h2 class="mb-0">{{ $totalTransactions }}</h2>
                        <small>order di bulan ini</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-receipt fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-1">Total Pendapatan</h6>
                        <h2 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                        <small>pendapatan bruto</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-1">Rata-rata / Order</h6>
                        <h2 class="mb-0">Rp {{ number_format($avgPerOrder, 0, ',', '.') }}</h2>
                        <small>per transaksi</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-line fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Breakdown Per Layanan & Per Status -->
<div class="row mb-4">
    <!-- Breakdown per Layanan -->
    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-tshirt me-2"></i>Breakdown per Layanan</h6>
            </div>
            <div class="card-body">
                @if($byService->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Layanan</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $serviceLabels = [
                                        'kiloan' => 'Kiloan',
                                        'dry_cleaning' => 'Dry Cleaning',
                                        'antar_jemput' => 'Antar Jemput',
                                        'setrika_premium' => 'Setrika Premium'
                                    ];
                                @endphp
                                @foreach($byService as $service)
                                    <tr>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $serviceLabels[$service->service_type] ?? $service->service_type }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <strong>{{ $service->count }}</strong> order
                                        </td>
                                        <td class="text-end">
                                            <strong>Rp {{ number_format($service->revenue, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th>TOTAL</th>
                                    <th class="text-center">{{ $totalTransactions }} order</th>
                                    <th class="text-end">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">Belum ada data layanan di bulan ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Breakdown per Status -->
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-tasks me-2"></i>Breakdown per Status</h6>
            </div>
            <div class="card-body">
                @if($byStatus->count() > 0)
                    @php
                        $statusLabels = [
                            'pending' => ['label' => 'Pending', 'color' => 'warning', 'icon' => 'clock'],
                            'in_progress' => ['label' => 'In Progress', 'color' => 'info', 'icon' => 'spinner'],
                            'done' => ['label' => 'Done', 'color' => 'success', 'icon' => 'check-circle'],
                            'delivered' => ['label' => 'Delivered', 'color' => 'primary', 'icon' => 'truck']
                        ];
                    @endphp
                    @foreach($byStatus as $status)
                        @php
                            $info = $statusLabels[$status->status] ?? [
                                'label' => $status->status, 
                                'color' => 'secondary', 
                                'icon' => 'circle'
                            ];
                        @endphp
                        <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded" 
                             style="background: #f8f9fa;">
                            <div>
                                <i class="fas fa-{{ $info['icon'] }} text-{{ $info['color'] }} me-2"></i>
                                <strong>{{ $info['label'] }}</strong>
                            </div>
                            <span class="badge bg-{{ $info['color'] }} fs-6">
                                {{ $status->count }} order
                            </span>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">Belum ada data status di bulan ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Top 5 Customer -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-trophy me-2"></i>Top 5 Customer (Bulan Ini)</h6>
            </div>
            <div class="card-body">
                @if($topCustomers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="10%">Rank</th>
                                    <th>Nama Customer</th>
                                    <th>Tier</th>
                                    <th class="text-center">Total Transaksi</th>
                                    <th class="text-end">Total Belanja</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topCustomers as $index => $tc)
                                    <tr>
                                        <td>
                                            @if($index == 0)
                                                <span class="badge bg-warning text-dark fs-6">
                                                    🥇 #{{ $index + 1 }}
                                                </span>
                                            @elseif($index == 1)
                                                <span class="badge bg-secondary fs-6">
                                                    🥈 #{{ $index + 1 }}
                                                </span>
                                            @elseif($index == 2)
                                                <span class="badge" style="background: #cd7f32; color: white;">
                                                    🥉 #{{ $index + 1 }}
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark fs-6">
                                                    #{{ $index + 1 }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $tc->customer->name ?? 'Unknown' }}</strong>
                                            @if($tc->customer && $tc->customer->phone)
                                                <br><small class="text-muted">{{ $tc->customer->phone }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($tc->customer)
                                                {!! $tc->customer->tier_badge !!}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <strong>{{ $tc->total_transactions }}</strong> order
                                        </td>
                                        <td class="text-end">
                                            <strong class="text-success">
                                                Rp {{ number_format($tc->total_spent, 0, ',', '.') }}
                                            </strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Belum ada customer yang bertransaksi di bulan ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection