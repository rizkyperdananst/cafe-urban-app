@extends('template')
@section('title', 'Dashboard Pelanggan')

@section('content')
    <div class="d-flex">
        {{-- Sidebar --}}
        {{-- <aside class="bg-light border-end" style="width: 220px; min-height: 100vh;">
            <div class="p-3">
                <h5 class="fw-bold text-center mb-4">Cafe Urban</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a href="{{ route('menu.index') }}" class="nav-link text-dark">
                            <i class="bi bi-house-door me-2"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="{{ route('menu.index') }}" class="nav-link text-dark">
                            <i class="bi bi-card-list me-2"></i> Daftar Menu
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="{{ route('cart.index') }}" class="nav-link text-dark">
                            <i class="bi bi-cart3 me-2"></i> Keranjang Pesanan
                        </a>
                    </li>
                    <li class="nav-item mb-4">
                        <a href="{{ route('orders.history') }}" class="nav-link text-dark">
                            <i class="bi bi-clock-history me-2"></i> Riwayat Pesanan
                        </a>
                    </li>
                </ul>
            </div>

            <div class="p-3 border-top text-center">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-danger w-100">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </button>
                </form>
            </div>
        </aside> --}}

        {{-- Main Content --}}
        <main class="flex-grow-1 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="fw-bold">☕ Selamat Datang di Cafe Urban</h3>
                <div>
                    <span class="me-3 text-muted">Hai, {{ Auth::user()->name ?? 'Pelanggan' }}</span>
                    {{-- <a href="{{ route('cart.index') }}" class="btn btn-light position-relative"> --}}
                    <a href="#" class="btn btn-light position-relative">
                        <i class="bi bi-cart-fill fs-5"></i>
                        @if (session('cart') && count(session('cart')) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

            {{-- Search Bar --}}
            {{-- <form method="GET" action="{{ route('menu.index') }}" class="mb-4"> --}}
            <form method="GET" action="#" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Cari menu...">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>

            {{-- Filter Kategori --}}
            <div class="d-flex flex-wrap gap-2 mb-4">
                <a href="#"
                    class="btn btn-sm {{ request('category') ? 'btn-outline-secondary' : 'btn-secondary' }}">
                    Semua
                </a>
                @foreach ($categories as $cat)
                    {{-- <a href="{{ route('menu.index', ['category' => $cat->id]) }}" --}}
                    <a href="#"
                        class="btn btn-sm {{ request('category') == $cat->id ? 'btn-secondary' : 'btn-outline-secondary' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            {{-- Judul --}}
            <h4 class="text-center fw-bold mb-3 text-purple">DAFTAR MENU</h4>

            {{-- Daftar Menu --}}
            <div class="row">
                @forelse ($menus as $menu)
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            @if ($menu->image)
                                <img src="{{ asset('storage/' . $menu->image) }}" class="card-img-top"
                                    alt="{{ $menu->name }}" style="height: 160px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                    style="height: 160px;">
                                    <i class="bi bi-image text-muted fs-1"></i>
                                </div>
                            @endif

                            <div class="card-body text-center">
                                <h6 class="card-title fw-bold">{{ $menu->name }}</h6>
                                <p class="mb-2 text-muted">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                                {{-- <form action="{{ route('cart.add', $menu->id) }}" method="POST"> --}}
                                <form action="#" method="POST">
                                    @csrf
                                    <button class="btn btn-sm text-white" style="background-color: #6f42c1;">
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
            <div class="mt-3">
                {{ $menus->links('pagination::bootstrap-5') }}
            </div>
        </main>
    </div>
@endsection
