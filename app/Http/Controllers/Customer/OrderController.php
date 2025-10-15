<?php

namespace App\Http\Controllers\Customer;

use App\Models\Order;
use App\Models\Payment;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.cart.index')->with('error', 'Keranjang kosong!');
        }

        return view('customer.checkout', compact('cart'));
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'order_type'     => 'required|in:takeaway,dine_in',
            'table_number'   => 'nullable|string|max:10',
            'payment_method' => 'required|in:qris,cash,e_wallet',
        ]);

        DB::beginTransaction();
        try {
            $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

            $order = Order::create([
                'user_id'       => auth()->id(),
                'total_price'   => $total,
                'status'        => 'pending',
                'order_type'    => $request->order_type,
                'table_number'  => $request->table_number,
            ]);

            foreach ($cart as $c) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id'  => $c['id'],
                    'quantity' => $c['quantity'],
                    'subtotal' => $c['price'] * $c['quantity'],
                ]);
            }

            Payment::create([
                'order_id'  => $order->id,
                'method'    => $request->payment_method,
                'amount'    => $total,
                'reference' => Str::upper(Str::random(8)),
            ]);

            DB::commit();
            session()->forget('cart');

            return redirect()->route('customer.dashboard')->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    public function history(Request $request)
    {
        $user = auth()->user();

        $query = \App\Models\Order::with(['orderItems.menu', 'payment'])
            ->where('user_id', $user->id)
            ->latest();

        // Filter status
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Cari berdasarkan nomor pesanan (reference dari Payment)
        if ($request->filled('search')) {
            $query->whereHas('payment', function ($q) use ($request) {
                $q->where('reference', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->paginate(6);

        return view('customer.history', compact('orders'));
    }

    public function show($id)
    {
        $order = \App\Models\Order::with(['orderItems.menu', 'payment'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('customer.order-detail', compact('order'));
    }

    public function print($id)
    {
        $order = \App\Models\Order::with(['orderItems.menu', 'payment'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('customer.order-print', compact('order'));
    }
}
