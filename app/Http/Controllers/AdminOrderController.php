<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems', 'payment'])
            ->latest();
        
        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }
        
        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }
        
        $orders = $query->paginate(20);
        
        // Calculate order statistics
        $orderStats = [
            'total' => Order::count(),
            'pending_payment' => Order::where('status', 'pending_payment')->count(),
            'paid' => Order::where('status', 'paid')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
        ];
        
        return view('admin.order.index', compact('orders', 'orderStats'));
    }
    
    /**
     * Display the specified order
     */
    public function show($id)
    {
        $order = Order::with(['user', 'orderItems.product', 'orderItems.productSize', 'payment'])
            ->findOrFail($id);
        
        $timeline = $this->generateTrackingTimeline($order);
        
        return view('admin.order.show', compact('order', 'timeline'));
    }
    
    /**
     * View payment proof - FIXED VERSION
     */
    public function viewPaymentProof($id)
    {
        try {
            $order = Order::with(['payment'])->findOrFail($id);
            
            // Check multiple possible locations for payment proof
            $receiptPath = null;
            
            // Priority 1: Check order's payment_proof field
            if ($order->payment_proof) {
                $receiptPath = $order->payment_proof;
            }
            // Priority 2: Check payment table's receipt_path
            elseif ($order->payment && $order->payment->receipt_path) {
                $receiptPath = $order->payment->receipt_path;
            }
            
            if (!$receiptPath) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bukti pembayaran tidak ditemukan untuk pesanan ini'
                ], 404);
            }
            
            // Clean the path - remove any duplicate 'storage/' or 'public/' prefixes
            $cleanPath = str_replace(['storage/', 'public/'], '', $receiptPath);
            
            // Check if file exists in storage
            if (!Storage::disk('public')->exists($cleanPath)) {
                \Log::error('Payment proof file not found', [
                    'order_id' => $id,
                    'original_path' => $receiptPath,
                    'clean_path' => $cleanPath,
                    'full_path' => storage_path('app/public/' . $cleanPath)
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'File bukti pembayaran tidak ditemukan di server. Path: ' . $cleanPath
                ], 404);
            }
            
            // Generate the proper URL for the image
            $imageUrl = asset('storage/' . $cleanPath);
            
            return response()->json([
                'success' => true,
                'image_url' => $imageUrl,
                'file_path' => $cleanPath,
                'order_number' => $order->order_number
            ]);
            
        } catch (\Exception $e) {
            \Log::error('View payment proof error', [
                'order_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat bukti pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Generate tracking timeline for order
     */
    private function generateTrackingTimeline($order)
    {
        $timeline = [
            [
                'title' => 'Pesanan Dibuat',
                'date' => $order->order_date,
                'status' => 'completed',
                'icon' => 'fas fa-receipt',
                'description' => 'Pesanan #' . $order->order_number . ' telah dibuat',
            ]
        ];
        
        // Payment step
        if ($order->payment_method === 'cod') {
            $timeline[] = [
                'title' => 'Siap Dikirim',
                'date' => $order->order_date,
                'status' => in_array($order->status, ['paid', 'processing', 'shipped', 'delivered']) ? 'completed' : 'current',
                'icon' => 'fas fa-money-bill-wave',
                'description' => 'Pesanan COD siap untuk diproses',
            ];
        } else {
            $timeline[] = [
                'title' => $order->payment_date ? 'Pembayaran Diterima' : 'Menunggu Pembayaran',
                'date' => $order->payment_date,
                'status' => $order->payment_date ? 'completed' : ($order->status === 'pending_payment' ? 'current' : 'pending'),
                'icon' => 'fas fa-credit-card',
                'description' => $order->payment_date ? 'Pembayaran telah dikonfirmasi' : 'Menunggu konfirmasi pembayaran',
            ];
        }
        
        // Processing step
        $timeline[] = [
            'title' => 'Persiapan Pengiriman',
            'date' => $order->processing_date,
            'status' => $order->status === 'processing' ? 'current' : (in_array($order->status, ['shipped', 'delivered']) ? 'completed' : 'pending'),
            'icon' => 'fas fa-box',
            'description' => $order->status === 'processing' ? 'Pesanan sedang dikemas' : 'Menunggu proses kemasan',
        ];
        
        // Shipping step
        $timeline[] = [
            'title' => 'Pengiriman',
            'date' => $order->shipped_date,
            'status' => $order->status === 'shipped' ? 'current' : ($order->status === 'delivered' ? 'completed' : 'pending'),
            'icon' => 'fas fa-truck',
            'description' => $order->shipped_date ? 'Pesanan dalam perjalanan' : 'Belum dikirim',
        ];
        
        // Delivery step
        $timeline[] = [
            'title' => 'Pesanan Diterima',
            'date' => $order->delivered_date,
            'status' => $order->status === 'delivered' ? 'completed' : 'pending',
            'icon' => 'fas fa-home',
            'description' => $order->delivered_date ? 'Pesanan telah diterima' : 'Menunggu konfirmasi penerimaan',
        ];
        
        return $timeline;
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
        
        $order = Order::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            $oldStatus = $order->status;
            $newStatus = $request->status;
            
            // Update order
            $updateData = [
                'status' => $newStatus,
                'admin_notes' => $request->admin_notes,
            ];
            
            if ($request->tracking_number) {
                $updateData['tracking_number'] = $request->tracking_number;
            }
            
            // Set appropriate timestamps
            switch ($newStatus) {
                case 'paid':
                    if (!$order->payment_date) {
                        $updateData['payment_date'] = now();
                    }
                    break;
                case 'shipped':
                    if (!$order->shipped_date) {
                        $updateData['shipped_date'] = now();
                    }
                    break;
                case 'delivered':
                    if (!$order->delivered_date) {
                        $updateData['delivered_date'] = now();
                    }
                    break;
            }
            
            $order->update($updateData);
            
            // Handle stock restoration if cancelled
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($order->orderItems as $item) {
                    if ($item->size_id && $item->productSize) {
                        $item->productSize->increment('stock', $item->quantity);
                    } elseif ($item->product) {
                        $item->product->increment('stock', $item->quantity);
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
     * Get order statistics for dashboard
     */
    public function getOrderStats()
    {
        $stats = [
            'total' => Order::count(),
            'today' => Order::whereDate('order_date', today())->count(),
            'pending_payment' => Order::where('status', 'pending_payment')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'total_revenue' => Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])->sum('total'),
            'monthly_revenue' => Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])
                                    ->whereMonth('order_date', now()->month)
                                    ->whereYear('order_date', now()->year)
                                    ->sum('total'),
        ];
        
        return response()->json($stats);
    }
}