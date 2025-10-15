@extends('template')
@section('title', 'Tambah Pelanggan')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4"><i class="bi bi-person-plus me-2"></i> Tambah Pelanggan</h3>

    <form action="{{ route('admin.customers.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Depan</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Belakang</label>
                <input type="text" name="last_name" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" name="phone" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
