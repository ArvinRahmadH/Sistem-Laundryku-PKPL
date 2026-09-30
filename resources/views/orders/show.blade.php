@extends('layouts.main')

@section('title', 'Order Detail')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i>Order Detail #{{ $order->id }}</h4>
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
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Customer Information</h6>
                        <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
                        <p><strong>Telepon:</strong> {{ $order->phone ?? '-' }}</p>
                        <p><strong>Alamat:</strong> {{ $order->address ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Order Information</h6>
                        <p><strong>Tanggal:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Terakhir Update:</strong> {{ $order->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted">Order Details</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Jenis Layanan</th>
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
                                </tr>
                                <tr>
                                    <th>Berat</th>
                                    <td>{{ $order->weight }} kg</td>
                                </tr>
                                <tr>
                                    <th>Harga</th>
                                    <td>Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Catatan</th>
                                    <td>{{ $order->notes ?? '-' }}</td>
                                </tr>
                                @if($order->file_path)
                                    <tr>
                                        <th>File</th>
                                        <td>
                                            <a href="{{ asset('storage/' . $order->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye me-1"></i>Lihat File
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="/orders" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                    <div>
                        <!-- TAMBAHKAN TOMBOL INVOICE INI -->
                        <a href="/orders/{{ $order->id }}/invoice/download" class="btn btn-success" target="_blank">
                            <i class="fas fa-file-invoice me-2"></i>Download Invoice
                        </a>
                        <a href="/orders/{{ $order->id }}/edit" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <button type="button" class="btn btn-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-2"></i>Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
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
@endsection