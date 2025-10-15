<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'payment', 'orderItems.menu'])->latest();

        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);
        $completedOrders = Order::whereIn('status', ['done', 'paid'])
            ->with(['user', 'payment'])
            ->latest()->paginate(5, ['*'], 'riwayat');

        return view('admin.orders.index', compact('orders', 'completedOrders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,done,cancelled,paid',
        ]);

        $order->update(['status' => $request->status]);

        return response()->json(['success' => true, 'status' => $order->status]);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->back()->with('success', 'Pesanan berhasil dihapus.');
    }
}
