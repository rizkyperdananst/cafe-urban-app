@extends('template')
@section('title', 'Pembayaran Pesanan')

@section('content')
    <div class="container py-4">
        <h3 class="fw-bold mb-4">
            <i class="bi bi-wallet2 me-2"></i> Pembayaran Pesanan Cafe Urban
        </h3>

        @php $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']); @endphp

        <div class="row g-4">
            {{-- Kiri: Data Pembeli --}}
            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Data Pembeli</h5>

                        <form action="{{ route('customer.checkout.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', auth()->user()->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', auth()->user()->phone) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Opsi Pesanan</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="order_type" value="takeaway"
                                        checked>
                                    <label class="form-check-label">Take Away</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="order_type" value="dine_in">
                                    <label class="form-check-label">Dine-In</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">No. Meja</label>
                                <input type="text" name="table_number" class="form-control"
                                    placeholder="Isi jika Dine-In">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Metode Pembayaran</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payment_method" value="cash"
                                        checked>
                                    <label class="form-check-label">Tunai (Cash)</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payment_method" value="qris">
                                    <label class="form-check-label">QRIS</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payment_method" value="e_wallet">
                                    <label class="form-check-label">E-Wallet</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2">Bayar Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Kanan: Ringkasan Pesanan --}}
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>

                        @foreach ($cart as $item)
                            <div class="d-flex justify-content-between">
                                <span>{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                                <strong>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</strong>
                            </div>
                        @endforeach

                        <hr>
                        <div class="d-flex justify-content-between">
                            <h6>Total Bayar:</h6>
                            <h5 class="fw-bold text-primary">Rp {{ number_format($total, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
