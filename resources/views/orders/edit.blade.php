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

                    {{-- Dropdown Customer --}}
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">
                            <i class="fas fa-user-tag me-1"></i>Pilih Customer (Member)
                        </label>
                        <select class="form-control" id="customer_id" name="customer_id">
                            <option value="">-- Non Member --</option>
                            @foreach($customers as $cust)
                                <option value="{{ $cust->id }}" 
                                        data-tier="{{ $cust->tier }}"
                                        data-discount="{{ $cust->discount_percent }}"
                                        {{ old('customer_id', $order->customer_id) == $cust->id ? 'selected' : '' }}>
                                    {{ $cust->name }} - {{ ucfirst($cust->tier) }} ({{ $cust->discount_percent }}% off)
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Untuk tambah customer baru, buka menu 
                            <a href="{{ route('customers.index') }}" target="_blank">Customer</a>
                        </small>
                    </div>

                    {{-- Info Diskon --}}
                    <div id="discount-info" class="alert alert-success {{ ($order->discount_percent ?? 0) > 0 ? '' : 'd-none' }}">
                        <i class="fas fa-tag me-1"></i>
                        <strong>Member Diskon:</strong> <span id="discount-text">{{ $order->discount_percent ?? 0 }}%</span>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Nama Customer <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" 
                                       value="{{ $order->customer_name }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ $order->phone }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control" id="address" name="address" rows="2">{{ $order->address }}</textarea>
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
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="weight" class="form-label">Berat (kg) <span class="text-danger">*</span></label>
                                <input type="number" step="0.1" class="form-control" id="weight" name="weight" 
                                       min="0" value="{{ $order->weight }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga /kg (Rp)</label>
                                <input type="number" class="form-control bg-light" id="price" name="price" 
                                       min="0" value="10000" readonly>
                                <small class="text-muted">Harga tetap Rp 10.000/kg</small>
                            </div>
                        </div>
                    </div>

                    {{-- Preview Perhitungan --}}
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h6 class="mb-3"><i class="fas fa-calculator me-1"></i>Preview Perhitungan</h6>
                            <table class="table table-sm mb-0">
                                <tr>
                                    <td>Subtotal <small class="text-muted">(<span id="preview-weight">{{ $order->weight }}</span> kg × Rp 10.000)</small></td>
                                    <td class="text-end" id="preview-subtotal">
                                        Rp {{ number_format($order->subtotal ?? ($order->weight * 10000), 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Diskon (<span id="preview-discount-percent">{{ $order->discount_percent ?? 0 }}</span>%)</td>
                                    <td class="text-end text-danger" id="preview-discount-amount">
                                        - Rp {{ number_format($order->discount_amount ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr class="fw-bold border-top">
                                    <td>TOTAL</td>
                                    <td class="text-end text-primary fs-5" id="preview-total">
                                        Rp {{ number_format($order->total_price ?? ($order->weight * 10000), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </table>
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
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="file" class="form-label">Upload File Baru</label>
                                <input type="file" class="form-control" id="file" name="file" accept=".jpg,.jpeg,.png,.pdf">
                                <small class="text-muted">Max 5MB</small>
                                @if($order->file_path)
                                    <div class="mt-2">
                                        <small>File saat ini: </small>
                                        <a href="{{ asset('storage/' . $order->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye me-1"></i>Lihat File
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ $order->notes }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/orders" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-2"></i>Update Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const customerSelect = document.getElementById('customer_id');
    const weightInput = document.getElementById('weight');
    const priceInput = document.getElementById('price');
    const discountInfo = document.getElementById('discount-info');
    const discountText = document.getElementById('discount-text');
    
    const PRICE_PER_KG = 10000;
    let currentDiscountPercent = {{ $order->discount_percent ?? 0 }};

    priceInput.value = PRICE_PER_KG;

    function formatRupiah(angka) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(angka));
    }

    function updateCalculation() {
        const weight = parseFloat(weightInput?.value) || 0;
        const subtotal = weight * PRICE_PER_KG;
        const discountAmount = subtotal * (currentDiscountPercent / 100);
        const total = subtotal - discountAmount;
        
        document.getElementById('preview-weight').textContent = weight;
        document.getElementById('preview-subtotal').textContent = formatRupiah(subtotal);
        document.getElementById('preview-discount-percent').textContent = currentDiscountPercent;
        document.getElementById('preview-discount-amount').textContent = '- ' + formatRupiah(discountAmount);
        document.getElementById('preview-total').textContent = formatRupiah(total);
    }

    customerSelect?.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        
        if (this.value) {
            const discount = parseFloat(selected.dataset.discount) || 0;
            currentDiscountPercent = discount;
            
            if (discount > 0) {
                discountInfo.classList.remove('d-none');
                discountText.textContent = discount + '%';
            } else {
                discountInfo.classList.add('d-none');
            }
        } else {
            currentDiscountPercent = 0;
            discountInfo.classList.add('d-none');
        }
        
        updateCalculation();
    });

    weightInput?.addEventListener('input', updateCalculation);
});
</script>
@endpush
@endsection