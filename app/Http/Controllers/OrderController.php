<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Endpoint untuk membuat pesanan
    public function create(Request $request, $booth_id)
{
    // Validasi input
    $request->validate([
        'payment_method' => 'required|in:cash,qris',
    ]);

    // Cek booth
    $booth = Booth::findOrFail($booth_id);

    // Validasi jika metode pembayaran QRIS tidak diizinkan
    if ($booth->name === 'Pakde' && $request->payment_method === 'qris') {
        return back()->withErrors(['payment_method' => 'QRIS tidak tersedia untuk booth Pakde.']);
    }

    // Buat pesanan
    $order = Order::create([
        'user_id' => Auth::id(),
        'booth_id' => $booth_id,
        'payment_method' => $request->payment_method,
        'status' => 'pending',
    ]);

    // Redirect sesuai metode pembayaran
    if ($request->payment_method == 'qris') {
        return redirect()->route('payments.create', ['order' => $order->id]);
    }

    return redirect()->route('orders.status', ['order' => $order->id]);
}


    // Halaman untuk melihat status pesanan
    public function showStatus($order_id)
    {
        $order = Order::where('id', $order_id)
                      ->where('user_id', Auth::id()) // Hanya pesanan milik user yang bisa dilihat
                      ->firstOrFail();

        return view('orders.status', compact('order'));
    }

    // Endpoint untuk meng-upload bukti pembayaran QRIS
    public function uploadPaymentProof(Request $request, $order_id)
    {
        $request->validate([
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $payment = Payment::create([
            'order_id' => $order_id,
            'proof_image' => $request->file('proof_image')->store('payments'),
            'status' => 'pending',
        ]);

        return redirect()->route('orders.status', ['order' => $order_id]);
    }

    public function bulkCreate(Request $request, $booth_id)
    {
        $request->validate([
            'quantities' => 'required|array',
            'payment_method' => 'required|in:cash,qris',
        ]);

        $quantities = array_filter($request->quantities, function ($qty) {
            return $qty > 0;
        });

        if (empty($quantities)) {
            return back()->with('error', 'Pilih minimal satu menu dengan jumlah lebih dari 0.');
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'booth_id' => $booth_id,
            'payment_method' => $request->payment_method,
            'status' => $request->payment_method === 'qris' ? 'pending' : 'approved',
        ]);

        foreach ($quantities as $menu_id => $qty) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $menu_id,
                'quantity' => $qty,
            ]);
        }

        if ($request->payment_method === 'qris') {
            return redirect()->route('payments.show', $order->id);
        }

        return redirect()->route('orders.status', $order->id)->with('success', 'Pesanan berhasil dibuat.');
    }

    public function manageOrders()
    {
        $userId = auth()->id();
    
        // Ambil pesanan dari booth yang dimiliki oleh penjual
        $orders = Order::whereHas('booth', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->with('booth', 'user', 'payment')
        ->orderBy('created_at', 'desc')
        ->get();
    
        return view('orders.manage', compact('orders'));
    }
    
    public function approvePayment($payment_id)
    {
        // Cari payment berdasarkan ID
        $payment = Payment::findOrFail($payment_id);
    
        // Set status pembayaran menjadi approved
        $payment->status = 'approved';
        $payment->save();
    
        // Update status pesanan menjadi 'approved'
        $order = $payment->order;
        $order->status = 'approved';
        $order->save();
    
        return redirect()->route('orders.manage')->with('success', 'Pembayaran QRIS disetujui.');
    }

    public function declinePayment($payment_id)
    {
        $payment = Payment::findOrFail($payment_id);
        $payment->status = 'declined';
        $payment->save();
    
        return redirect()->route('orders.manage')->with('error', 'Pembayaran QRIS ditolak.');
    }

    public function completeOrder($order_id)
    {
        $order = Order::findOrFail($order_id);
        $order->status = 'completed';
        $order->save();
    
        return redirect()->route('orders.manage')->with('success', 'Pesanan selesai!');
    }

    public function myOrdersSearch(Request $request)
{
    $query = Order::where('user_id', auth()->id()); // Hanya pesanan milik pengguna yang login

    // Tambahkan filter berdasarkan kriteria
    if ($request->has('status') && $request->status !== null) {
        $query->where('status', $request->status);
    }

    if ($request->has('order_id') && $request->order_id !== null) {
        $query->where('id', $request->order_id);
    }

    if ($request->has('booth_name') && $request->booth_name !== null) {
        $query->whereHas('booth', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->booth_name . '%');
        });
    }

    $orders = $query->latest()->paginate(10); // Paginate hasil pencarian

    return view('orders.my-search', compact('orders'));
}
    
    public function userOrders()
{
    $orders = Order::where('user_id', auth()->id())->get();

    return view('orders.index', compact('orders'));
}

    public function show($id)
{
    $order = Order::where('id', $id)
                  ->where('user_id', auth()->id()) // Pastikan pesanan milik user login
                  ->firstOrFail();

    return view('orders.show', compact('order'));
}

public function status(Order $order)
{
    // Periksa otorisasi
    if ($order->user_id !== auth()->id() && $order->booth->user_id !== auth()->id()) {
        abort(403, 'Unauthorized');
    }

    return view('orders.status', compact('order'));
}

}
