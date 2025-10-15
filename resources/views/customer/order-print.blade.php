<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan Cafe Urban</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-size: 14px;
            margin: 30px;
        }

        .line {
            border-bottom: 1px dashed #999;
            margin: 8px 0;
        }

        .text-small {
            font-size: 13px;
        }
    </style>
</head>

<body onload="window.print()">

    <div class="text-center mb-3">
        <h5 class="fw-bold mb-0">Cafe Urban</h5>
        <p class="text-muted text-small mb-0">Jl. Contoh Alamat No. 123, Medan</p>
        <p class="text-small mb-1">Telp: 0812-3456-7890</p>
        <div class="line"></div>
    </div>

    <p><strong>No. Pesanan:</strong>
        #{{ $order->payment->reference ?? 'INV-' . str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</p>
    <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
    <p><strong>Jenis Pesanan:</strong> {{ strtoupper($order->order_type) }}
        @if ($order->order_type == 'dine_in' && $order->table_number)
            (Meja {{ $order->table_number }})
        @endif
    </p>

    <div class="line"></div>
    <h6 class="fw-bold">Detail Pesanan</h6>

    <table class="table table-borderless text-small">
        <thead>
            <tr>
                <th>Menu</th>
                <th class="text-end">Qty</th>
                <th class="text-end">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderItems as $item)
                <tr>
                    <td>{{ $item->menu->name }}</td>
                    <td class="text-end">{{ $item->quantity }}</td>
                    <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>
    <p class="text-end fw-bold mb-1">Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
    <p class="text-end mb-1">Pembayaran: {{ strtoupper($order->payment->method ?? '-') }}</p>
    <p class="text-end fw-bold">Dibayar: Rp {{ number_format($order->payment->amount ?? 0, 0, ',', '.') }}</p>
    <div class="line"></div>

    <p class="text-center mt-3 text-small">Terima kasih telah berkunjung ke Cafe Urban ☕</p>
</body>

</html>
