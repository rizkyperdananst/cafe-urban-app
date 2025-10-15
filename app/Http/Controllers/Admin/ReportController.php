<?php

namespace App\Http\Controllers\Admin;

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
}
