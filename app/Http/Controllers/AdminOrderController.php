<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Daftar pesanan untuk admin
     */
    public function index()
    {
        $orders = Order::latest()->paginate(15);
        return view('admin.order.index', compact('orders'));
    }

    /**
     * Detail pesanan
     */
    public function show($id)
    {
        $order = Order::with(['orderItems', 'payment'])->findOrFail($id);

        // Timeline sederhana (lebih dinamis bisa dibuat pakai log history)
        // Untuk COD, skip langkah pembayaran
        if ($order->payment_method === 'cod') {
            $timeline = [
                [
                    'title' => 'Pesanan Dibuat',
                    'description' => 'Pesanan COD telah berhasil dibuat.',
                    'date' => $order->created_at,
                    'status' => ($order->status === 'pending' || $order->status === 'pending_payment') ? 'current' : 'completed',
                    'icon' => 'fas fa-file-alt',
                ],
                [
                    'title' => 'Dikemas',
                    'description' => 'Pesanan sedang diproses dan dikemas.',
                    'date' => $order->status === 'processing' ? now() : null,
                    'status' => $order->status === 'processing' ? 'current' : ($order->status === 'shipped' || $order->status === 'delivered' ? 'completed' : 'upcoming'),
                    'icon' => 'fas fa-box',
                ],
                [
                    'title' => 'Dikirim',
                    'description' => 'Pesanan telah dikirim ke alamat tujuan.',
                    'date' => $order->tracking_number ? now() : null,
                    'status' => $order->status === 'shipped' ? 'current' : ($order->status === 'delivered' ? 'completed' : 'upcoming'),
                    'icon' => 'fas fa-truck',
                ],
                [
                    'title' => 'Selesai',
                    'description' => 'Pesanan telah selesai dan diterima. Pembayaran dilakukan saat pengiriman.',
                    'date' => $order->delivered_date,
                    'status' => $order->status === 'delivered' ? 'completed' : 'upcoming',
                    'icon' => 'fas fa-check-circle',
                ],
            ];
        } else {
            // Timeline untuk non-COD (seperti sebelumnya)
            $timeline = [
                [
                    'title' => 'Pesanan Dibuat',
                    'description' => 'Pesanan telah berhasil dibuat.',
                    'date' => $order->created_at,
                    'status' => $order->status === 'pending_payment' ? 'current' : 'completed',
                    'icon' => 'fas fa-file-alt',
                ],
                [
                    'title' => 'Pembayaran',
                    'description' => 'Menunggu pembayaran customer.',
                    'date' => $order->payment_date,
                    'status' => $order->status === 'paid' ? 'current' : ($order->status !== 'pending_payment' ? 'completed' : 'upcoming'),
                    'icon' => 'fas fa-credit-card',
                ],
                [
                    'title' => 'Dikemas',
                    'description' => 'Pesanan sedang diproses dan dikemas.',
                    'date' => $order->status === 'processing' ? now() : null,
                    'status' => $order->status === 'processing' ? 'current' : ($order->status === 'shipped' || $order->status === 'delivered' ? 'completed' : 'upcoming'),
                    'icon' => 'fas fa-box',
                ],
                [
                    'title' => 'Dikirim',
                    'description' => 'Pesanan telah dikirim ke alamat tujuan.',
                    'date' => $order->tracking_number ? now() : null,
                    'status' => $order->status === 'shipped' ? 'current' : ($order->status === 'delivered' ? 'completed' : 'upcoming'),
                    'icon' => 'fas fa-truck',
                ],
                [
                    'title' => 'Selesai',
                    'description' => 'Pesanan telah selesai dan diterima.',
                    'date' => $order->delivered_date,
                    'status' => $order->status === 'delivered' ? 'completed' : 'upcoming',
                    'icon' => 'fas fa-check-circle',
                ],
            ];
        }

        return view('admin.order.show', compact('order', 'timeline'));
    }

    /**
     * Update status pesanan
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);
        
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        
        // Update tracking number jika status shipped
        if ($request->status === 'shipped') {
            $order->tracking_number = $request->input('tracking_number', '');
        }
        
        // Update delivered date jika status delivered
        if ($request->status === 'delivered') {
            $order->delivered_date = now();
        }
        
        $order->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui.',
        ]);
    }

    /**
     * Lihat bukti pembayaran
     */
    public function paymentProof($id)
    {
        $order = Order::with('payment')->findOrFail($id);

        if ($order->payment && $order->payment->receipt_path) {
            return response()->json([
                'success' => true,
                'image_url' => asset('storage/' . $order->payment->receipt_path),
            ]);
        }

        return response()->json(['success' => false]);
    }
}