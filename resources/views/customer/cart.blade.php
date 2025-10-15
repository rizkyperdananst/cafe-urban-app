@extends('template')

@section('title', 'Keranjang Pesanan')

@section('content')
    <div class="container py-4">
        <h3 class="fw-bold mb-4">
            <i class="bi bi-cart3 me-2"></i> Keranjang Pesanan Cafe Urban
        </h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @php $total = 0; @endphp
        @forelse ($cart as $item)
            @php
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            @endphp
            <div class="card mb-3 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        @if ($item['image'])
                            <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" width="80"
                                class="rounded me-3">
                        @else
                            <div class="bg-light rounded me-3" style="width:80px; height:80px;"></div>
                        @endif
                        <div>
                            <h6 class="fw-bold">{{ $item['name'] }} × {{ $item['quantity'] }}</h6>
                            <p class="mb-0 text-muted">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <form action="{{ route('customer.cart.update', $item['id']) }}" method="POST"
                            class="d-flex align-items-center me-3">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm btn-outline-secondary" name="quantity"
                                value="{{ $item['quantity'] - 1 }}">-</button>
                            <span class="mx-2">{{ $item['quantity'] }}</span>
                            <button class="btn btn-sm btn-outline-secondary" name="quantity"
                                value="{{ $item['quantity'] + 1 }}">+</button>
                        </form>
                        <form action="{{ route('customer.cart.remove', $item['id']) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Keranjang masih kosong.</p>
        @endforelse

        <div class="d-flex justify-content-between align-items-center mt-4">
            <h5>Total: Rp {{ number_format($total, 0, ',', '.') }}</h5>
            @if ($total > 0)
                <a href="{{ route('customer.checkout.form') }}" class="btn btn-primary">Buat Pesanan</a>
            @endif
        </div>
    </div>
@endsection
