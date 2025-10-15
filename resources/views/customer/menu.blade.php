@extends('template')

@section('title', 'Daftar Menu')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4 text-center">
        ☕ Selamat Datang di Cafe Urban
    </h3>

    {{-- Search --}}
    <form method="GET" action="{{ route('customer.menu.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari customer.menu...">
            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
        </div>
    </form>

    {{-- Filter Kategori --}}
    <div class="d-flex flex-wrap gap-2 mb-4 justify-content-center">
        <a href="{{ route('customer.menu.index') }}"
           class="btn btn-sm {{ request('category') ? 'btn-outline-secondary' : 'btn-secondary' }}">
           Semua
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('customer.menu.index', ['category' => $cat->id]) }}"
               class="btn btn-sm {{ request('category') == $cat->id ? 'btn-secondary' : 'btn-outline-secondary' }}">
               {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <h5 class="text-center fw-bold mb-3 text-purple">DAFTAR MENU</h5>

    {{-- Daftar Menu --}}
    <div class="row">
        @forelse ($menus as $menu)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    @if ($menu->image)
                        <img src="{{ asset('storage/'.$menu->image) }}" class="card-img-top" alt="{{ $menu->name }}" style="height: 160px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 160px;">
                            <i class="bi bi-image text-muted fs-1"></i>
                        </div>
                    @endif

                    <div class="card-body text-center">
                        <h6 class="card-title fw-bold">{{ $menu->name }}</h6>
                        <p class="mb-2 text-muted small">{{ $menu->category->name ?? '' }}</p>
                        <p class="mb-3 fw-bold text-dark">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                        <form action="{{ route('customer.cart.add', $menu->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm text-white w-100" style="background-color: #6f42c1;">
                                <i class="bi bi-cart-plus me-1"></i> Masukkan Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-4">
                <p class="text-muted mb-0">Belum ada menu tersedia.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $menus->withQueryString()->links() }}
    </div>
</div>
@endsection
