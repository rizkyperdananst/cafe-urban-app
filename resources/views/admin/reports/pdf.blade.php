<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan Cafe Urban</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }
    </style>
</head>

<body>

    <h3 style="text-align:center; margin-bottom: 5px;">Laporan Penjualan Cafe Urban</h3>
    <p style="text-align:right">Tanggal: {{ now()->format('d M Y') }}</p>

    @php
        $grandTotal = $orders->sum('total_price');
    @endphp

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Pesanan</th>
                <th>Tanggal</th>
                <th>Nama Pelanggan</th>
                <th>No Meja</th>
                <th>Jumlah Item</th>
                <th>Total Harga</th>
                <th>Metode Pembayaran</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $i => $order)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>#INV-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td>{{ $order->user->first_name ?? '-' }}</td>
                    <td>{{ $order->table_number ?? '-' }}</td>
                    <td>{{ $order->orderItems->sum('quantity') }}</td>
                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>{{ strtoupper($order->payment->method ?? '-') }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br><br>

    <table style="width: 35%; float: right; border-collapse: collapse;">
        <tr>
            <th style="border:1px solid #000; padding:6px; text-align:left;">TOTAL PENJUALAN</th>
            <td style="border:1px solid #000; padding:6px; text-align:right;">
                Rp {{ number_format($grandTotal, 0, ',', '.') }}
            </td>
        </tr>
    </table>

</body>

</html>
