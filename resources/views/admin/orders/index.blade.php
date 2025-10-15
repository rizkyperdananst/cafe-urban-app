@extends('template')

@section('title', 'Kelola Status Pesanan')

@section('content')
    <div class="container py-4">

        <h3 class="fw-bold mb-4">
            <i class="bi bi-truck me-2"></i> Kelola Status Pesanan
        </h3>

        {{-- Statistik singkat --}}
        <div class="row mb-4 g-3">
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Pesanan Hari Ini</h6>
                        <h4>{{ \App\Models\Order::whereDate('created_at', today())->count() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Pendapatan Hari Ini</h6>
                        <h4>Rp
                            {{ number_format(\App\Models\Order::whereDate('created_at', today())->sum('total_price'), 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <form method="GET" class="row g-2 align-items-end mb-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Cari pelanggan / nomor meja">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="done">Done</option>
                    <option value="paid">Paid</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="method" class="form-select">
                    <option value="">Metode Pembayaran</option>
                    <option value="cash">Tunai</option>
                    <option value="qris">QRIS</option>
                    <option value="e_wallet">E-Wallet</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        {{-- Tabel Kelola --}}
        <div class="table-responsive mb-5">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>Nomor Meja</th>
                        <th>Dine-In / Take Away</th>
                        <th>Jumlah Item</th>
                        <th>Total Harga</th>
                        <th>Metode Pembayaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->user->first_name ?? '-' }}</td>
                            <td>{{ $order->table_number ?? '-' }}</td>
                            <td>{{ ucfirst($order->order_type) }}</td>
                            <td>{{ $order->orderItems->sum('quantity') }}</td>
                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>{{ strtoupper($order->payment->method ?? '-') }}</td>
                            <td>
                                <select class="form-select form-select-sm update-status" data-id="{{ $order->id }}">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                                        Processing</option>
                                    <option value="done" {{ $order->status == 'done' ? 'selected' : '' }}>Done</option>
                                    <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                            </td>
                            <td>
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus pesanan ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $orders->links() }}
        </div>

        {{-- Riwayat Pesanan --}}
        <h5 class="fw-bold mb-3">Riwayat Pesanan</h5>
        <div class="table-responsive">
            <table class="table table-striped align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>Nomor Meja</th>
                        <th>Tipe</th>
                        <th>Total Harga</th>
                        <th>Waktu</th>
                        <th>Metode Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($completedOrders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->user->name ?? '-' }}</td>
                            <td>{{ $order->table_number ?? '-' }}</td>
                            <td>{{ ucfirst($order->order_type) }}</td>
                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td>{{ strtoupper($order->payment->method ?? '-') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $completedOrders->links('pagination::bootstrap-5') }}
        </div>

    </div>

    {{-- AJAX untuk Update Status --}}
    <script>
        document.querySelectorAll('.update-status').forEach(select => {
            select.addEventListener('change', function() {
                let orderId = this.dataset.id;
                let status = this.value;

                fetch(`/admin/orders/${orderId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            status
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert('Status pesanan berhasil diperbarui menjadi: ' + status);
                        }
                    });
            });
        });
    </script>
@endsection
