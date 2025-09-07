<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\PesananItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PesananController extends Controller
{
    /**
     * Display a listing of all orders for admin
     */
    public function index(Request $request)
    {
        $query = Pesanan::with(['user', 'pesananItems.product'])->recent();
        
        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->byStatus($request->status);
        }
        
        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('tanggal_pesanan', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('tanggal_pesanan', '<=', $request->date_to);
        }
        
        // Search by order number, customer name, or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_pesanan', 'like', "%{$search}%")
                  ->orWhere('nama_pelanggan', 'like', "%{$search}%")
                  ->orWhere('email_pelanggan', 'like', "%{$search}%");
            });
        }
        
        $orders = $query->paginate(20);
        
        return view('admin.pesanan.index', compact('orders'));
    }
    
    /**
     * Display the specified order details
     */
    public function show($id)
    {
        $order = Pesanan::with(['user', 'pesananItems.product', 'pesananItems.productSize'])
            ->findOrFail($id);
        
        return view('admin.pesanan.show', compact('order'));
    }
    
    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', Rule::in(['pending_payment', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'])],
            'tracking_number' => 'nullable|string|max:100',
            'admin_notes' => 'nullable|string|max:1000',
        ]);
        
        $order = Pesanan::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            $oldStatus = $order->status;
            $newStatus = $request->status;
            
            // Prepare update data
            $updateData = [
                'status' => $newStatus,
                'catatan_admin' => $request->admin_notes,
            ];
            
            // Add tracking number if provided
            if ($request->tracking_number) {
                $updateData['nomor_resi'] = $request->tracking_number;
            }
            
            // Set appropriate timestamps based on status
            switch ($newStatus) {
                case 'paid':
                    if (!$order->tanggal_pembayaran) {
                        $updateData['tanggal_pembayaran'] = now();
                    }
                    break;
                case 'shipped':
                    if (!$order->tanggal_pengiriman) {
                        $updateData['tanggal_pengiriman'] = now();
                    }
                    break;
                case 'delivered':
                    if (!$order->tanggal_diterima) {
                        $updateData['tanggal_diterima'] = now();
                    }
                    break;
            }
            
            $order->update($updateData);
            
            // Handle stock restoration if order is cancelled
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($order->pesananItems as $item) {
                    if ($item->size_id && $item->productSize) {
                        $item->productSize->increment('stock', $item->kuantitas);
                    } elseif ($item->product) {
                        $item->product->increment('stock', $item->kuantitas);
                    }
                }
            }
            
            // Handle stock reduction if changing from cancelled to other status
            if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                foreach ($order->pesananItems as $item) {
                    if ($item->size_id && $item->productSize) {
                        if ($item->productSize->stock >= $item->kuantitas) {
                            $item->productSize->decrement('stock', $item->kuantitas);
                        } else {
                            throw new \Exception("Stok tidak mencukupi untuk produk: " . $item->nama_produk);
                        }
                    } elseif ($item->product) {
                        if ($item->product->stock >= $item->kuantitas) {
                            $item->product->decrement('stock', $item->kuantitas);
                        } else {
                            throw new \Exception("Stok tidak mencukupi untuk produk: " . $item->nama_produk);
                        }
                    }
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diupdate'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get admin notes for an order
     */
    public function getAdminNotes($id)
    {
        $order = Pesanan::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'notes' => $order->catatan_admin ? nl2br(e($order->catatan_admin)) : null
        ]);
    }
    
    /**
     * Get payment proof for an order
     */
    public function getPaymentProof($id)
    {
        $order = Pesanan::findOrFail($id);
        
        $imageUrl = null;
        if ($order->bukti_pembayaran) {
            $imageUrl = Storage::url($order->bukti_pembayaran);
        }
        
        return response()->json([
            'success' => true,
            'image_url' => $imageUrl,
            'payment_notes' => $order->catatan_pembayaran ? nl2br(e($order->catatan_pembayaran)) : null
        ]);
    }
    
    /**
     * Print order invoice
     */
    public function printOrder($id)
    {
        $order = Pesanan::with(['pesananItems.product', 'pesananItems.productSize'])
            ->findOrFail($id);
        
        return view('admin.pesanan.print', compact('order'));
    }
    
    /**
     * Get order statistics for dashboard
     */
    public function getStats()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        
        $stats = [
            'total_orders' => Pesanan::count(),
            'pending_orders' => Pesanan::byStatus('pending_payment')->count(),
            'processing_orders' => Pesanan::byStatus('processing')->count(),
            'shipped_orders' => Pesanan::byStatus('shipped')->count(),
            'today_orders' => Pesanan::whereDate('tanggal_pesanan', $today)->count(),
            'month_orders' => Pesanan::where('tanggal_pesanan', '>=', $thisMonth)->count(),
            'total_revenue' => Pesanan::whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])
                ->sum('total_harga'),
            'month_revenue' => Pesanan::whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])
                ->where('tanggal_pesanan', '>=', $thisMonth)
                ->sum('total_harga'),
        ];
        
        return response()->json($stats);
    }
    
    /**
     * Bulk update order status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:pesanan,id',
            'status' => ['required', Rule::in(['pending_payment', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'])],
        ]);
        
        try {
            DB::beginTransaction();
            
            $updatedCount = 0;
            
            foreach ($request->order_ids as $orderId) {
                $order = Pesanan::find($orderId);
                if ($order) {
                    $updateData = ['status' => $request->status];
                    
                    // Set appropriate timestamps
                    switch ($request->status) {
                        case 'paid':
                            if (!$order->tanggal_pembayaran) {
                                $updateData['tanggal_pembayaran'] = now();
                            }
                            break;
                        case 'shipped':
                            if (!$order->tanggal_pengiriman) {
                                $updateData['tanggal_pengiriman'] = now();
                            }
                            break;
                        case 'delivered':
                            if (!$order->tanggal_diterima) {
                                $updateData['tanggal_diterima'] = now();
                            }
                            break;
                    }
                    
                    $order->update($updateData);
                    $updatedCount++;
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "{$updatedCount} pesanan berhasil diupdate"
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Export orders to CSV
     */
    public function exportCsv(Request $request)
    {
        $query = Pesanan::with(['user', 'pesananItems']);
        
        // Apply same filters as index
        if ($request->has('status') && $request->status !== 'all') {
            $query->byStatus($request->status);
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('tanggal_pesanan', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('tanggal_pesanan', '<=', $request->date_to);
        }
        
        $orders = $query->get();
        
        $filename = 'pesanan_export_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        return response()->stream(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($handle, [
                'Nomor Pesanan',
                'Tanggal Pesanan',
                'Nama Pelanggan',
                'Email Pelanggan',
                'Status',
                'Metode Pembayaran',
                'Total Harga',
                'Jumlah Item',
                'Nomor Resi'
            ]);
            
            // CSV data
            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->nomor_pesanan,
                    $order->tanggal_pesanan->format('Y-m-d H:i:s'),
                    $order->nama_pelanggan,
                    $order->email_pelanggan,
                    $order->status_label,
                    $order->metode_pembayaran_label,
                    $order->total_harga,
                    $order->pesananItems->count(),
                    $order->nomor_resi
                ]);
            }
            
            fclose($handle);
        }, 200, $headers);
    }
    
    /**
     * Delete order (soft delete)
     */
    public function destroy($id)
    {
        $order = Pesanan::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Restore stock if order was not cancelled
            if ($order->status !== 'cancelled') {
                foreach ($order->pesananItems as $item) {
                    if ($item->size_id && $item->productSize) {
                        $item->productSize->increment('stock', $item->kuantitas);
                    } elseif ($item->product) {
                        $item->product->increment('stock', $item->kuantitas);
                    }
                }
            }
            
            $order->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
}