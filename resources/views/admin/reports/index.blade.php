@extends('template')

@section('title', 'Laporan Penjualan')

@section('content')
    <div class="container py-4">

        <h3 class="fw-bold mb-4">
            <i class="bi bi-graph-up-arrow me-2"></i> Laporan Penjualan Cafe Urban
        </h3>

        {{-- ======= KOTAK STATISTIK ======= --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0 text-center">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Pesan Masuk Hari Ini</h6>
                        <h4>{{ \App\Models\Order::whereDate('created_at', today())->count() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 text-center">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Jumlah Pesanan</h6>
                        <h4>{{ \App\Models\Order::count() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 text-center">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Pesanan Selesai</h6>
                        <h4>{{ \App\Models\Order::whereIn('status', ['done', 'paid'])->count() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 text-center">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Pesanan Dibatalkan</h6>
                        <h4>{{ \App\Models\Order::where('status', 'cancelled')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- ======= FILTER ======= --}}
        <form action="{{ route('admin.reports.index') }}" method="GET" class="row g-2 align-items-end mb-4">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Cari nama / meja / ID pesanan"
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Selesai</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Dibayar</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="method" class="form-select">
                    <option value="">Metode Pembayaran</option>
                    <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                    <option value="qris" {{ request('method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                    <option value="e_wallet" {{ request('method') == 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-filter me-1"></i> Filter
                </button>
            </div>
        </form>

        {{-- ======= TABEL LAPORAN ======= --}}
        <div class="table-responsive mb-4">
            <table class="table table-bordered text-center align-middle" width="100%" style="size: 10px">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>ID Pesanan</th>
                        <th>Tanggal</th>
                        <th>Nama Pelanggan</th>
                        <th>Nomor Meja</th>
                        <th>Dine-In / Take Away</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                        <th>Metode Pembayaran</th>
                        <th>Status Pesanan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>#INV-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>{{ $order->user->first_name ?? '-' }}</td>
                            <td>{{ $order->table_number ?? '-' }}</td>
                            <td>{{ ucfirst($order->order_type) }}</td>
                            <td>{{ $order->orderItems->sum('quantity') }}</td>
                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>{{ strtoupper($order->payment->method ?? '-') }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $order->status == 'done'
                                        ? 'success'
                                        : ($order->status == 'processing'
                                            ? 'info'
                                            : ($order->status == 'cancelled'
                                                ? 'danger'
                                                : 'secondary')) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td width="15%">
                                <a href="#" class="btn btn-sm btn-secondary"><i class="bi bi-eye"></i></a>
                                <a href="#" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus pesanan ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-muted">Tidak ada data ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>

        {{-- ======= EKSPOR PDF ======= --}}
        <div class="text-center mt-4">
            <a href="{{ route('admin.reports.pdf', request()->query()) }}" class="btn btn-outline-danger px-4"
                target="_blank">
                <i class="bi bi-file-earmark-pdf me-2"></i> Ekspor PDF
            </a>
        </div>


    </div>
@endsection
