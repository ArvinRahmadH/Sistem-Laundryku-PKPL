<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoiceNumber }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .company-info h1 {
            color: #007bff;
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .company-info p {
            color: #666;
            font-size: 12px;
            line-height: 1.6;
        }
        
        .invoice-title {
            text-align: right;
        }
        
        .invoice-title h2 {
            color: #333;
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .invoice-title .invoice-number {
            font-size: 14px;
            color: #666;
        }
        
        .invoice-title .invoice-date {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }
        
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .detail-box {
            width: 48%;
        }
        
        .detail-box h3 {
            font-size: 12px;
            color: #007bff;
            text-transform: uppercase;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        
        .detail-box p {
            line-height: 1.8;
            color: #555;
        }
        
        .detail-box p strong {
            color: #333;
            display: inline-block;
            width: 100px;
        }
        
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .invoice-table thead {
            background: #007bff;
            color: #fff;
        }
        
        .invoice-table th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        .invoice-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            color: #555;
        }
        
        .invoice-table tbody tr:hover {
            background: #f9f9f9;
        }
        
        .invoice-table .text-right {
            text-align: right;
        }
        
        .invoice-summary {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        
        .summary-table {
            width: 300px;
        }
        
        .summary-table tr td {
            padding: 8px 0;
        }
        
        .summary-table tr.total td {
            border-top: 2px solid #007bff;
            padding-top: 15px;
            font-size: 16px;
            font-weight: bold;
            color: #007bff;
        }
        
        .summary-table .label {
            color: #666;
        }
        
        .summary-table .value {
            text-align: right;
            color: #333;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-badge.pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-badge.in_progress {
            background: #cce5ff;
            color: #004085;
        }
        
        .status-badge.done {
            background: #d4edda;
            color: #155724;
        }
        
        .status-badge.delivered {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .invoice-footer {
            border-top: 1px solid #eee;
            padding-top: 20px;
            margin-top: 30px;
        }
        
        .footer-notes {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .footer-notes h4 {
            font-size: 12px;
            color: #007bff;
            margin-bottom: 8px;
        }
        
        .footer-notes p {
            font-size: 11px;
            color: #666;
            line-height: 1.6;
        }
        
        .footer-signature {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }
        
        .signature-box {
            text-align: center;
            width: 200px;
        }
        
        .signature-box .line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
        }
        
        .signature-box p {
            font-size: 11px;
            color: #666;
        }
        
        .thank-you {
            text-align: center;
            margin-top: 30px;
            padding: 15px;
            background: #007bff;
            color: #fff;
            border-radius: 5px;
            font-size: 14px;
        }
        
        /* ====== BUTTON STYLES ====== */
        .action-buttons {
            text-align: center;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px dashed #ddd;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            margin: 5px 6px;
            transition: all 0.2s ease;
            min-width: 170px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            line-height: 1;
            font-family: inherit;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            text-decoration: none;
        }
        
        .btn:active {
            transform: translateY(0);
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .btn-icon {
            font-size: 15px;
            line-height: 1;
        }
        
        .btn-download {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: #fff;
        }
        
        .btn-download:hover {
            background: linear-gradient(135deg, #218838 0%, #1aa179 100%);
            color: #fff;
        }
        
        .btn-print {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: #fff;
        }
        
        .btn-print:hover {
            background: linear-gradient(135deg, #0069d9 0%, #004494 100%);
            color: #fff;
        }
        
        .btn-back {
            background: #fff;
            color: #6c757d;
            border: 1.5px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .btn-back:hover {
            background: #f8f9fa;
            color: #495057;
            border-color: #adb5bd;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        /* Print styles */
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            
            .invoice-container {
                box-shadow: none;
                padding: 20px;
                max-width: 100%;
            }
            
            .no-print {
                display: none !important;
            }
            
            .invoice-table tbody tr:hover {
                background: transparent;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <h1>🧺 LaundryKu</h1>
                <p>
                    Jl. Contoh No. 123, Kota Anda<br>
                    Telp: (021) 1234-5678<br>
                    Email: info@laundryku.com
                </p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <div class="invoice-number">No: {{ $invoiceNumber }}</div>
                <div class="invoice-date">Tanggal: {{ $order->created_at->format('d/m/Y') }}</div>
            </div>
        </div>

        <!-- Details -->
        <div class="invoice-details">
            <div class="detail-box">
                <h3>Ditagihkan Kepada:</h3>
                <p>
                    <strong>Nama:</strong> {{ $order->customer_name }}<br>
                    <strong>Telepon:</strong> {{ $order->phone ?? '-' }}<br>
                    <strong>Alamat:</strong> {{ $order->address ?? '-' }}
                </p>
            </div>
            <div class="detail-box">
                <h3>Informasi Order:</h3>
                <p>
                    <strong>Order ID:</strong> #{{ $order->id }}<br>
                    <strong>Tanggal:</strong> {{ $order->created_at->format('d/m/Y H:i') }}<br>
                    <strong>Status:</strong> 
                    <span class="status-badge {{ $order->status }}">
                        {{ $statuses[$order->status] ?? $order->status }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Deskripsi Layanan</th>
                    <th>Berat</th>
                    <th>Harga</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        <strong>{{ $services[$order->service_type] ?? $order->service_type }}</strong>
                        @if($order->notes)
                            <br><small style="color: #888;">Catatan: {{ $order->notes }}</small>
                        @endif
                    </td>
                    <td>{{ $order->weight }} kg</td>
                    <td>Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Summary -->
        <div class="invoice-summary">
            <table class="summary-table">
                <tr>
                    <td class="label">Subtotal</td>
                    <td class="value">Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label">Diskon</td>
                    <td class="value">Rp 0</td>
                </tr>
                <tr>
                    <td class="label">Pajak (0%)</td>
                    <td class="value">Rp 0</td>
                </tr>
                <tr class="total">
                    <td>Total</td>
                    <td class="value">Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <div class="footer-notes">
                <h4>Catatan:</h4>
                <p>
                    1. Invoice ini adalah bukti transaksi yang sah.<br>
                    2. Harap simpan invoice ini sebagai bukti pengambilan laundry.<br>
                    3. Barang yang tidak diambil dalam 30 hari di luar tanggung jawab kami.<br>
                    4. Terima kasih telah menggunakan layanan kami.
                </p>
            </div>

            <div class="footer-signature">
                <div class="signature-box">
                    <p>Penerima,</p>
                    <div class="line"></div>
                    <p>{{ $order->customer_name }}</p>
                </div>
                <div class="signature-box">
                    <p>Hormat Kami,</p>
                    <div class="line"></div>
                    <p>Admin LaundryKu</p>
                </div>
            </div>
        </div>

        <div class="thank-you">
            Terima kasih atas kepercayaan Anda! 🧺
        </div>

        <!-- Action Buttons (No Print) -->
        <div class="action-buttons no-print">
            <a href="/orders/{{ $order->id }}/invoice" class="btn btn-download">
                <span class="btn-icon">⬇</span>
                <span>Download PDF</span>
            </a>
            <a href="/orders/{{ $order->id }}" class="btn btn-back">
                <span class="btn-icon">←</span>
                <span>Kembali</span>
            </a>
        </div>
    </div>
</body>
</html>