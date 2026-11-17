@extends('layouts.main')

@section('title', 'Detail Customer')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="fas fa-user me-2"></i>Detail Customer #{{ $customer->id }}</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Nama</th>
                        <td>{{ $customer->name }}</td>
                    </tr>
                    <tr>
                        <th>Telepon</th>
                        <td>{{ $customer->phone }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $customer->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $customer->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tier Member</th>
                        <td>{!! $customer->tier_badge !!}</td>
                    </tr>
                    <tr>
                        <th>Diskon</th>
                        <td><span class="badge bg-success">{{ $customer->discount_percent }}%</span></td>
                    </tr>
                    <tr>
                        <th>Total Order</th>
                        <td>{{ $customer->orders->count() }} order</td>
                    </tr>
                    <tr>
                        <th>Terdaftar Sejak</th>
                        <td>{{ $customer->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection