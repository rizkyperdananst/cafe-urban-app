@extends('template')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4">Riwayat Pesanan Cafe Urban</h3>

    {{-- Filter dan Search --}}
    <form action="{{ route('customer.orders.history') }}" method="GET" class="d-flex flex-wrap align-items-center mb-4 gap-2">
        <select name="status" class="form-select w-auto">
            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>SEMUA</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
            <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Selesai</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Dibayar</option>
        </select>

        <div class="input-group" style="max-width: 300px;">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                placeholder="Cari berdasarkan nomor pesanan...">
            <button class="btn btn-primary"><i class="bi bi-search"></i></button>
        </div>
    </form>

    {{-- List Pesanan --}}
    @forelse($orders as $order)
        <div class="card mb-3 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">
                        #{{ $order->payment->reference ?? 'INV-' . str_pad($order->id, 3, '0', STR_PAD_LEFT) }}
                    </h6>
                    <small class="text-muted">{{ $order->created_at->format('d-m-Y') }}</small>
                </div>

                {{-- Status --}}
                @php
                    $statusColors = [
                        'pending' => 'secondary',
                        'processing' => 'warning',
                        'done' => 'success',
                        'cancelled' => 'danger',
                        'paid' => 'info'
                    ];
                @endphp
                <button class="btn btn-sm btn-{{ $statusColors[$order->status] ?? 'secondary' }} mb-3" disabled>
                    {{ ucfirst($order->status) }}
                </button>

                <div class="row align-items-center">
                    <div class="col-md-8">
                        <p class="mb-1"><strong>Nama Menu:</strong>
                            {{ $order->orderItems->first()->menu->name ?? '-' }}
                            @if($order->orderItems->count() > 1)
                                (+{{ $order->orderItems->count() - 1 }} item lainnya)
                            @endif
                        </p>
                        <p class="mb-1"><strong>Jumlah:</strong> {{ $order->orderItems->sum('quantity') }}</p>
                        <p class="mb-1"><strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        <p class="mb-1"><strong>Pembayaran:</strong> {{ strtoupper($order->payment->method ?? '-') }}</p>
                    </div>

                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-info-circle me-1"></i> Lihat Detail
                        </a>
                        <a href="{{ route('customer.orders.print', $order->id) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            Cetak Struk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted text-center">Belum ada riwayat pesanan.</p>
    @endforelse

    <div class="d-flex justify-content-center mt-4">
        {{ $orders->withQueryString()->links() }}
    </div>
</div>
@endsection
