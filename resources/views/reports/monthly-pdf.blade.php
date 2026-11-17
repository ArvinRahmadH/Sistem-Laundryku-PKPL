<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Bulanan {{ $months[$month] }} {{ $year }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            padding: 20px;
        }
        
        .header {
            border-bottom: 3px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .header-table { width: 100%; }
        .header-table td { vertical-align: top; }
        
        .company-name {
            color: #007bff;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .company-info {
            color: #666;
            font-size: 10px;
            line-height: 1.6;
        }
        
        .report-title {
            text-align: right;
        }
        
        .report-title h2 {
            color: #333;
            font-size: 20px;
            margin-bottom: 5px;
        }
        
        .report-period {
            font-size: 12px;
            color: #007bff;
            font-weight: bold;
        }
        
        .summary-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        
        .summary-table td {
            width: 33.33%;
            padding: 12px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            text-align: center;
        }
        
        .summary-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #007bff;
        }
        
        .section-title {
            font-size: 13px;
            color: #007bff;
            font-weight: bold;
            margin-bottom: 10px;
            margin-top: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .data-table thead {
            background: #007bff;
            color: #fff;
        }
        
        .data-table th {
            padding: 8px 10px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        
        .data-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
            font-size: 10px;
        }
        
        .data-table tfoot {
            background: #f8f9fa;
            font-weight: bold;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
        
        .no-data {
            text-align: center;
            color: #999;
            font-style: italic;
            padding: 15px;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 9px;
            background: #e9ecef;
            color: #333;
            border-radius: 3px;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td style="width: 60%;">
                    <div class="company-name">LaundryKu</div>
                    <div class="company-info">
                        Jl. Contoh No. 123, Kota Anda<br>
                        Telp: (021) 1234-5678<br>
                        Email: info@laundryku.com
                    </div>
                </td>
                <td style="width: 40%;" class="report-title">
                    <h2>LAPORAN BULANAN</h2>
                    <div class="report-period">
                        Periode: {{ $months[$month] }} {{ $year }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- SUMMARY -->
    <table class="summary-table">
        <tr>
            <td>
                <div class="summary-label">Total Transaksi</div>
                <div class="summary-value">{{ $totalTransactions }}</div>
            </td>
            <td>
                <div class="summary-label">Total Pendapatan</div>
                <div class="summary-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-label">Rata-rata / Order</div>
                <div class="summary-value">Rp {{ number_format($avgPerOrder, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <!-- BREAKDOWN PER LAYANAN -->
    <div class="section-title">📊 Breakdown per Layanan</div>
    @if($byService->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-right">Pendapatan</th>
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
                        <td>{{ $serviceLabels[$service->service_type] ?? $service->service_type }}</td>
                        <td class="text-center">{{ $service->count }}</td>
                        <td class="text-right">Rp {{ number_format($service->revenue, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>TOTAL</td>
                    <td class="text-center">{{ $totalTransactions }}</td>
                    <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    @else
        <div class="no-data">Belum ada data layanan di bulan ini</div>
    @endif

    <!-- BREAKDOWN PER STATUS -->
    <div class="section-title">📋 Breakdown per Status</div>
    @if($byStatus->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th class="text-center">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $statusLabels = [
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'done' => 'Done',
                        'delivered' => 'Delivered'
                    ];
                @endphp
                @foreach($byStatus as $status)
                    <tr>
                        <td>{{ $statusLabels[$status->status] ?? $status->status }}</td>
                        <td class="text-center">{{ $status->count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">Belum ada data status di bulan ini</div>
    @endif

    <!-- TOP 5 CUSTOMER -->
    <div class="section-title">🏆 Top 5 Customer</div>
    @if($topCustomers->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 10%;">Rank</th>
                    <th>Nama Customer</th>
                    <th>Tier</th>
                    <th class="text-center">Transaksi</th>
                    <th class="text-right">Total Belanja</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topCustomers as $index => $tc)
                    <tr>
                        <td>#{{ $index + 1 }}</td>
                        <td>{{ $tc->customer->name ?? 'Unknown' }}</td>
                        <td>
                            @if($tc->customer)
                                {{ ucfirst($tc->customer->tier) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">{{ $tc->total_transactions }}</td>
                        <td class="text-right">Rp {{ number_format($tc->total_spent, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">Belum ada customer yang bertransaksi di bulan ini</div>
    @endif

    <!-- FOOTER -->
    <div class="footer">
        Laporan ini digenerate otomatis oleh sistem LaundryKu<br>
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>