@extends('template')

@section('title', 'Tambah Menu')

@section('content')
    <div class="container py-4">
        <h2 class="fw-bold mb-4">Tambah Menu Baru</h2>

        {{-- Notifikasi sukses --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Form Tambah Menu --}}
        <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nama Menu</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Cappuccino" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror"
                    required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Harga</label>
                <input type="text" id="price_display" class="form-control" placeholder="Contoh: 25.000"
                    autocomplete="off">
                <input type="hidden" id="price" name="price" value="{{ old('price') }}">
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stok</label>
                <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}"
                    class="form-control @error('stock') is-invalid @enderror" min="0">
                @error('stock')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea id="description" name="description" rows="3"
                    class="form-control @error('description') is-invalid @enderror" placeholder="Tuliskan deskripsi singkat menu...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Gambar (opsional)</label>
                <input type="file" id="image" name="image"
                    class="form-control @error('image') is-invalid @enderror" accept="image/*">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1"
                    {{ old('is_available', 1) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_available">Tersedia</label>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Simpan Menu
            </button>
            <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    @push('scripts')
        <script>
            const display = document.getElementById('price_display');
            const original = document.getElementById('price');

            // Format angka ke format Rupiah
            function formatRupiah(angka) {
                return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            display.addEventListener('input', function(e) {
                // buang semua selain angka
                let value = this.value.replace(/[^0-9]/g, '');

                // tampilkan dengan format ribuan
                this.value = value ? 'Rp ' + formatRupiah(value) : '';

                // simpan angka original ke hidden input
                original.value = value;
            });
        </script>
    @endpush

@endsection
