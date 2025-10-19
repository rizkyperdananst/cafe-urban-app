<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        $query = Order::with('payment')
            ->whereIn('status', ['done', 'paid']);

        if ($start && $end) {
            $query->whereBetween('created_at', [$start, $end]);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        $totalRevenue = $orders->sum('total_price');

        return view('admin.reports.index', compact('orders', 'totalRevenue', 'start', 'end'));
    }

    public function exportPdf(Request $request)
    {
        $orders = Order::with(['user', 'payment', 'orderItems'])
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('user', fn($u) => $u->where('first_name', 'like', "%{$request->search}%"))
                    ->orWhere('table_number', 'like', "%{$request->search}%")
                    ->orWhere('id', 'like', "%{$request->search}%");
            })
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->method, function ($q) use ($request) {
                $q->whereHas('payment', fn($p) => $p->where('method', $request->method));
            })
            ->latest()->get();

        $pdf = Pdf::loadview('admin.reports.pdf', compact('orders'))->setPaper('a4', 'landscape');
        return $pdf->stream('laporan-penjualan.pdf');
    }
}
