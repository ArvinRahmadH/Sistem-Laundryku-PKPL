<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoiceNumber }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            padding: 20px;
        }
        
        /* Header pakai table karena DomPDF tidak support flexbox */
        .header-table {
            width: 100%;
            border-bottom: 3px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .header-table td {
            vertical-align: top;
        }
        
        .company-name {
            color: #007bff;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .company-info {
            color: #666;
            font-size: 10px;
            line-height: 1.6;
        }
        
        .invoice-title {
            text-align: right;
        }
        
        .invoice-title h2 {
            color: #333;
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .invoice-number {
            font-size: 11px;
            color: #666;
        }
        
        .invoice-date {
            font-size: 10px;
            color: #888;
            margin-top: 3px;
        }
        
        /* Details table */
        .details-table {
            width: 100%;
            margin-bottom: 20px;
        }
        
        .details-table td {
            vertical-align: top;
            width: 50%;
            padding-right: 15px;
        }
        
        .detail-title {
            font-size: 11px;
            color: #007bff;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 8px;
            border-bottom: 1px solid #eee;
            padding-bottom: 3px;
        }
        
        .detail-content {
            line-height: 1.8;
            color: #555;
            font-size: 10px;
        }
        
        .detail-content strong {
            color: #333;
        }
        
        /* Items table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .items-table thead {
            background: #007bff;
            color: #fff;
        }
        
        .items-table th {
            padding: 8px 10px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            color: #555;
            font-size: 10px;
        }
        
        .items-table .text-right {
            text-align: right;
        }
        
        /* Summary table */
        .summary-wrapper {
            width: 100%;
            margin-bottom: 20px;
        }
        
        .summary-table {
            width: 280px;
            margin-left: auto;
            border-collapse: collapse;
        }
        
        .summary-table td {
            padding: 6px 0;
            font-size: 10px;
        }
        
        .summary-table .label {
            color: #666;
            text-align: left;
        }
        
        .summary-table .value {
            text-align: right;
            color: #333;
        }
        
        .summary-table .total-row td {
            border-top: 2px solid #007bff;
            padding-top: 10px;
            font-size: 13px;
            font-weight: bold;
            color: #007bff;
        }
        
        /* Status badge */
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            background: #e9ecef;
            color: #333;
            border-radius: 3px;
        }
        
        /* Notes box */
        .notes-box {
            background: #f8f9fa;
            padding: 10px 12px;
            margin-bottom: 20px;
            border-left: 3px solid #007bff;
        }
        
        .notes-title {
            font-size: 10px;
            color: #007bff;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .notes-content {
            font-size: 9px;
            color: #666;
            line-height: 1.6;
        }
        
        /* Signature */
        .signature-table {
            width: 100%;
            margin-top: 30px;
            margin-bottom: 20px;
        }
        
        .signature-table td {
            text-align: center;
            width: 50%;
            vertical-align: top;
            padding-top: 10px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            width: 150px;
            margin: 50px auto 5px auto;
            padding-top: 5px;
            font-size: 10px;
            color: #666;
        }
        
        /* Thank you */
        .thank-you {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background: #007bff;
            color: #fff;
            font-size: 12px;
            border-radius: 3px;
        }
        
        .footer-text {
            text-align: center;
            font-size: 9px;
            color: #999;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
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
            <td style="width: 40%;" class="invoice-title">
                <h2>INVOICE</h2>
                <div class="invoice-number">No: {{ $invoiceNumber }}</div>
                <div class="invoice-date">Tanggal: {{ $order->created_at->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    <!-- DETAILS -->
    <table class="details-table">
        <tr>
            <td>
                <div class="detail-title">Ditagihkan Kepada:</div>
                <div class="detail-content">
                    <strong>Nama:</strong> {{ $order->customer_name }}<br>
                    <strong>Telepon:</strong> {{ $order->phone ?? '-' }}<br>
                    <strong>Alamat:</strong> {{ $order->address ?? '-' }}
                </div>
            </td>
            <td>
                <div class="detail-title">Informasi Order:</div>
                <div class="detail-content">
                    <strong>Order ID:</strong> #{{ $order->id }}<br>
                    <strong>Tanggal:</strong> {{ $order->created_at->format('d/m/Y H:i') }}<br>
                    <strong>Status:</strong> 
                    <span class="status-badge">{{ $statuses[$order->status] ?? $order->status }}</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- ITEMS -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 45%;">Deskripsi Layanan</th>
                <th style="width: 15%;">Berat</th>
                <th style="width: 15%;">Harga</th>
                <th style="width: 20%;" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>
                    <strong>{{ $services[$order->service_type] ?? $order->service_type }}</strong>
                    @if($order->notes)
                        <br><span style="color: #888; font-size: 9px;">Catatan: {{ $order->notes }}</span>
                    @endif
                </td>
                <td>{{ $order->weight }} kg</td>
                <td>Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($order->price, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- SUMMARY -->
    <div class="summary-wrapper">
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
            <tr class="total-row">
                <td>Total</td>
                <td class="value">Rp {{ number_format($order->price, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- NOTES -->
    <div class="notes-box">
        <div class="notes-title">Catatan:</div>
        <div class="notes-content">
            1. Invoice ini adalah bukti transaksi yang sah.<br>
            2. Harap simpan invoice ini sebagai bukti pengambilan laundry.<br>
            3. Barang yang tidak diambil dalam 30 hari di luar tanggung jawab kami.<br>
            4. Terima kasih telah menggunakan layanan kami.
        </div>
    </div>

    <!-- SIGNATURE -->
    <table class="signature-table">
        <tr>
            <td>
                <div>Penerima,</div>
                <div class="signature-line">{{ $order->customer_name }}</div>
            </td>
            <td>
                <div>Hormat Kami,</div>
                <div class="signature-line">Admin LaundryKu</div>
            </td>
        </tr>
    </table>

    <!-- THANK YOU -->
    <div class="thank-you">
        Terima kasih atas kepercayaan Anda!
    </div>
    <div class="footer-text">
        Invoice ini digenerate otomatis oleh sistem LaundryKu
    </div>

</body>
</html>