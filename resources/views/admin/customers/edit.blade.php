@extends('template')
@section('title', 'Edit Pelanggan')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2"></i> Edit Pelanggan</h3>

    <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Depan</label>
                <input type="text" name="first_name" value="{{ $customer->first_name }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Belakang</label>
                <input type="text" name="last_name" value="{{ $customer->last_name }}" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ $customer->email }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" name="phone" value="{{ $customer->phone }}" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Password (kosongkan jika tidak diubah)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Status Akun</label>
                <select name="is_active" class="form-select">
                    <option value="1" {{ $customer->is_active ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !$customer->is_active ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
