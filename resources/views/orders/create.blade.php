@extends('layouts.main')

@section('title', 'Tambah Order Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-plus me-2"></i>Tambah Order Baru</h4>
            </div>
            <div class="card-body">
                <form action="{{ url('/orders') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Dropdown Customer (HANYA PILIH, TIDAK BISA TAMBAH) --}}
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">
                            <i class="fas fa-user-tag me-1"></i>Pilih Customer (Member)
                            <small class="text-muted">- Opsional</small>
                        </label>
                        <select class="form-control" id="customer_id" name="customer_id">
                            <option value="">-- Non Member / Input Manual --</option>
                            @foreach($customers as $cust)
                                <option value="{{ $cust->id }}" 
                                        data-tier="{{ $cust->tier }}"
                                        data-discount="{{ $cust->discount_percent }}"
                                        data-phone="{{ $cust->phone }}"
                                        data-address="{{ $cust->address }}"
                                        data-name="{{ $cust->name }}"
                                        {{ old('customer_id') == $cust->id ? 'selected' : '' }}>
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
                    <div id="discount-info" class="alert alert-success d-none">
                        <i class="fas fa-tag me-1"></i>
                        <strong>Member Diskon:</strong> <span id="discount-text">0%</span>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Nama Customer <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                                @error('customer_name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                                @error('phone')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                        @error('address')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="service_type" class="form-label">Jenis Layanan <span class="text-danger">*</span></label>
                                <select class="form-control" id="service_type" name="service_type" required>
                                    <option value="">Pilih Jenis Layanan</option>
                                    <option value="kiloan">Kiloan</option>
                                    <option value="dry_cleaning">Dry Cleaning</option>
                                    <option value="antar_jemput">Antar Jemput</option>
                                    <option value="setrika_premium">Setrika Premium</option>
                                </select>
                                @error('service_type')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="weight" class="form-label">Berat (kg) <span class="text-danger">*</span></label>
                                <input type="number" step="0.1" class="form-control" id="weight" name="weight" 
                                       min="0" value="0" required>
                                @error('weight')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga /kg (Rp)</label>
                                <input type="number" class="form-control bg-light" id="price" name="price" 
                                       min="0" value="10000" readonly>
                                <small class="text-muted">Harga tetap Rp 10.000/kg</small>
                                @error('price')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Preview Perhitungan --}}
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h6 class="mb-3"><i class="fas fa-calculator me-1"></i>Preview Perhitungan</h6>
                            <table class="table table-sm mb-0">
                                <tr>
                                    <td>Subtotal <small class="text-muted">(<span id="preview-weight">0</span> kg × Rp 10.000)</small></td>
                                    <td class="text-end" id="preview-subtotal">Rp 0</td>
                                </tr>
                                <tr>
                                    <td>Diskon (<span id="preview-discount-percent">0</span>%)</td>
                                    <td class="text-end text-danger" id="preview-discount-amount">- Rp 0</td>
                                </tr>
                                <tr class="fw-bold border-top">
                                    <td>TOTAL</td>
                                    <td class="text-end text-primary fs-5" id="preview-total">Rp 0</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="done">Done</option>
                                    <option value="delivered">Delivered</option>
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
                                @error('file')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        @error('notes')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/orders" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Order
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
    const customerNameInput = document.getElementById('customer_name');
    const phoneInput = document.getElementById('phone');
    const addressInput = document.getElementById('address');
    
    // Harga tetap per kg
    const PRICE_PER_KG = 10000;
    let currentDiscountPercent = 0;

    // Set harga default
    priceInput.value = PRICE_PER_KG;

    function formatRupiah(angka) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(angka));
    }

    function updateCalculation() {
        const weight = parseFloat(weightInput?.value) || 0;
        const price = PRICE_PER_KG; // Selalu pakai harga tetap
        const subtotal = weight * price;
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
            const phone = selected.dataset.phone || '';
            const address = selected.dataset.address || '';
            const name = selected.dataset.name || '';
            
            currentDiscountPercent = discount;
            
            if (customerNameInput && !customerNameInput.value) customerNameInput.value = name;
            if (phoneInput && !phoneInput.value) phoneInput.value = phone;
            if (addressInput && !addressInput.value) addressInput.value = address;
            
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

    updateCalculation();
});
</script>
@endpush
@endsection