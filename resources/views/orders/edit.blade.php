@extends('layouts.main')

@section('title', 'Edit Order')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Order #{{ $order->id }}</h4>
            </div>
            <div class="card-body">
                <form action="/orders/{{ $order->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Nama Customer <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ $order->customer_name }}" required>
                                @error('customer_name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ $order->phone }}">
                                @error('phone')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control" id="address" name="address" rows="2">{{ $order->address }}</textarea>
                        @error('address')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="service_type" class="form-label">Jenis Layanan <span class="text-danger">*</span></label>
                                <select class="form-control" id="service_type" name="service_type" required>
                                    <option value="kiloan" {{ $order->service_type == 'kiloan' ? 'selected' : '' }}>Kiloan</option>
                                    <option value="dry_cleaning" {{ $order->service_type == 'dry_cleaning' ? 'selected' : '' }}>Dry Cleaning</option>
                                    <option value="antar_jemput" {{ $order->service_type == 'antar_jemput' ? 'selected' : '' }}>Antar Jemput</option>
                                    <option value="setrika_premium" {{ $order->service_type == 'setrika_premium' ? 'selected' : '' }}>Setrika Premium</option>
                                </select>
                                @error('service_type')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="weight" class="form-label">Berat (kg)</label>
                                <input type="number" step="0.1" class="form-control" id="weight" name="weight" min="0" value="{{ $order->weight }}">
                                @error('weight')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga (Rp)</label>
                                <input type="number" class="form-control" id="price" name="price" min="0" value="{{ $order->price }}">
                                @error('price')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ $order->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="done" {{ $order->status == 'done' ? 'selected' : '' }}>Done</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                </select>
                                @error('status')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="file" class="form-label">Upload File</label>
                                <input type="file" class="form-control" id="file" name="file" accept=".jpg,.jpeg,.png,.pdf">
                                <small class="text-muted">Max 5MB (jpg, jpeg, png, pdf)</small>
                                @if($order->file_path)
                                    <div class="mt-2">
                                        <small>File saat ini: </small>
                                        <a href="{{ asset('storage/' . $order->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye me-1"></i>Lihat File
                                        </a>
                                    </div>
                                @endif
                                @error('file')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ $order->notes }}</textarea>
                        @error('notes')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/orders" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <div>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Update Order
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection