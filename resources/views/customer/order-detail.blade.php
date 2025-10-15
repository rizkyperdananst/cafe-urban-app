@extends('template')
@section('title', 'Detail Pesanan')

@section('content')
    <div class="container py-4">
        <h3 class="fw-bold mb-4">
            <i class="bi bi-info-circle me-2"></i> Detail Pesanan
        </h3>

        {{-- Informasi Utama --}}
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="fw-bold mb-1">
                            Nomor Pesanan:
                            #{{ $order->payment->reference ?? 'INV-' . str_pad($order->id, 3, '0', STR_PAD_LEFT) }}
                        </h6>
                        <p class="text-muted mb-0">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @php
                        $statusColors = [
                            'pending' => 'secondary',
                            'processing' => 'warning',
                            'done' => 'success',
                            'cancelled' => 'danger',
                            'paid' => 'info',
                        ];
                        $badge = $statusColors[$order->status] ?? 'secondary';
                    @endphp

                    <span class="badge bg-{{ $badge }}">
                        {{ ucfirst($order->status) }}
                    </span>

                </div>
            </div>
        </div>

        {{-- Daftar Item Pesanan --}}
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Daftar Menu</h5>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Menu</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderItems as $item)
                                <tr>
                                    <td>{{ $item->menu->name }}</td>
                                    <td>Rp {{ number_format($item->menu->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-end fw-bold">
                    Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- Informasi Pembayaran --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Informasi Pembayaran</h5>
                <p class="mb-1"><strong>Metode Pembayaran:</strong> {{ strtoupper($order->payment->method ?? '-') }}</p>
                <p class="mb-1"><strong>Total Dibayar:</strong> Rp
                    {{ number_format($order->payment->amount ?? 0, 0, ',', '.') }}</p>
                <p class="mb-1"><strong>Kode Referensi:</strong> {{ $order->payment->reference ?? '-' }}</p>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('customer.orders.history') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('customer.orders.print', $order->id) }}" target="_blank" class="btn btn-primary">
                <i class="bi bi-printer"></i> Cetak Struk
            </a>
        </div>
    </div>
@endsection
