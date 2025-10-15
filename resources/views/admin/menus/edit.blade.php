@extends('template')

@section('title', 'Edit Menu')

@section('content')
    <div class="container py-4">
        <h2 class="fw-bold mb-4">Edit Menu: {{ $menu->name }}</h2>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nama Menu</label>
                <input type="text" id="name" name="name" value="{{ old('name', $menu->name) }}"
                    class="form-control @error('name') is-invalid @enderror" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $menu->category_id) == $cat->id ? 'selected' : '' }}>
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
                <input type="number" id="price" name="price" step="0.01" value="{{ old('price', $menu->price) }}"
                    class="form-control @error('price') is-invalid @enderror" required>
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stok</label>
                <input type="number" id="stock" name="stock" value="{{ old('stock', $menu->stock) }}"
                    class="form-control @error('stock') is-invalid @enderror">
                @error('stock')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea id="description" name="description" rows="3"
                    class="form-control @error('description') is-invalid @enderror">{{ old('description', $menu->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Gambar</label>
                @if ($menu->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" width="120"
                            class="rounded shadow-sm">
                    </div>
                @endif
                <input type="file" id="image" name="image"
                    class="form-control @error('image') is-invalid @enderror">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1"
                    {{ old('is_available', $menu->is_available) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_available">Tersedia</label>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="bi bi-save"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
