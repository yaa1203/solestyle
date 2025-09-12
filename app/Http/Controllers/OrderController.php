<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
class OrderController extends Controller
{
    /**
     * Display a listing of user's orders
     */
    public function index(Request $request)
    {
        $query = Order::with('orderItems')->forUser(Auth::id())->recent();
        
        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->byStatus($request->status);
        }
        
        // Search by order number
        if ($request->has('search') && $request->search) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }
        
        $orders = $query->paginate(10);
        
        // Calculate order statistics
        $orderStats = [
            'total' => Order::forUser(Auth::id())->count(),
            'pending_payment' => Order::forUser(Auth::id())->byStatus('pending_payment')->count(),
            'processing' => Order::forUser(Auth::id())->byStatus('processing')->count(),
            'shipped' => Order::forUser(Auth::id())->byStatus('shipped')->count(),
            'delivered' => Order::forUser(Auth::id())->byStatus('delivered')->count(),
        ];
        
        return view('user.orders.index', compact('orders', 'orderStats'));
    }
    
    /**
     * Display the specified order
     */
    /**
     * Display the specified order
     */
    public function show($id)
    {
        $order = Order::with(['orderItems.product'])
            ->forUser(Auth::id())
            ->findOrFail($id);
        
        // Untuk COD, status "paid" seharusnya ditampilkan sebagai "pending_payment" 
        // karena pembayaran dilakukan saat menerima barang
        if ($order->payment_method === 'cod' && $order->status === 'paid') {
            $order->status = 'pending_payment';
        }
        
        return view('user.orders.show', compact('order'));
    }
        
    /**
     * Track order by order number
     */
    public function track($orderNumber)
    {
        $order = Order::with(['orderItems.product'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();
        
        // Generate tracking timeline
        $timeline = $this->generateTrackingTimeline($order);
        
        return view('user.orders.track', compact('order', 'timeline'));
    }
    
    /**
     * Track order form (for guest users)
     */
    public function trackForm()
    {
        return view('user.orders.track-form');
    }
    
    /**
     * Process track order form
     */
    public function processTrack(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
            'email' => 'required|email',
        ]);
        
        $order = Order::where('order_number', $request->order_number)
            ->where('customer_email', $request->email)
            ->first();
        
        if (!$order) {
            return back()->withErrors([
                'order_number' => 'Nomor pesanan atau email tidak ditemukan'
            ])->withInput();
        }
        
        return redirect()->route('order.track', $order->order_number);
    }
    
    /**
     * Cancel an order
     */
    public function cancel($id)
    {
        $order = Order::forUser(Auth::id())->findOrFail($id);
        
        if (!$order->canBeCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak dapat dibatalkan pada status saat ini'
            ], 400);
        }
        
        try {
            DB::beginTransaction();
            
            $success = $order->cancel();
            
            if ($success) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dibatalkan'
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membatalkan pesanan'
                ], 400);
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Download invoice
     */
    public function invoice($id)
    {
        $order = Order::with(['orderItems.product'])
            ->forUser(Auth::id())
            ->findOrFail($id);
        
        // Only allow invoice download for paid orders
        if (!in_array($order->status, ['paid', 'processing', 'shipped', 'delivered'])) {
            abort(403, 'Invoice tidak tersedia untuk pesanan yang belum dibayar');
        }
        
        return view('user.orders.invoice', compact('order'));
    }
    
    /**
     * Confirm order delivery
     */
    public function confirmDelivery($id)
    {
        $order = Order::forUser(Auth::id())->findOrFail($id);
        if ($order->status !== 'shipped') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan belum dalam status dikirim'
            ], 400);
        }
        try {
            DB::beginTransaction();
            $order->update([
                'status' => 'delivered',
                'delivered_date' => now(),
            ]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Pesanan telah dikonfirmasi diterima'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Upload payment proof
     */
    public function uploadPaymentProof(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'payment_notes' => 'nullable|string|max:500',
        ]);
        
        $order = Order::forUser(Auth::id())->findOrFail($id);
        
        // Untuk COD, tidak perlu upload bukti pembayaran
        if ($order->payment_method === 'cod') {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran COD tidak memerlukan bukti pembayaran'
            ], 400);
        }
        
        if ($order->status !== 'pending_payment') {
            return response()->json([
                'success' => false,
                'message' => 'Upload bukti pembayaran hanya tersedia untuk pesanan yang menunggu pembayaran'
            ], 400);
        }
        
        try {
            DB::beginTransaction();
            
            // Store payment proof
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $fileName = 'payment_' . $order->order_number . '_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('payment_proofs', $fileName, 'public');
                
                $order->update([
                    'payment_proof' => $filePath,
                    'payment_notes' => $request->payment_notes,
                    'status' => 'payment_verification', // Add this status if needed
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil diupload dan sedang diverifikasi'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload bukti pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Reorder (add items to cart)
     */
    public function reorder($id)
    {
        $order = Order::with('orderItems.product')
            ->forUser(Auth::id())
            ->findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            $addedItems = 0;
            $unavailableItems = [];
            
            foreach ($order->orderItems as $item) {
                if (!$item->product) {
                    $unavailableItems[] = $item->product_name . ' (Produk tidak tersedia)';
                    continue;
                }
                
                // Check stock availability
                $availableStock = $item->size_id 
                    ? ($item->productSize ? $item->productSize->stock : 0)
                    : $item->product->stock;
                
                if ($availableStock < $item->quantity) {
                    $unavailableItems[] = $item->product_name . ' (Stok tidak mencukupi)';
                    continue;
                }
                
                // Add to cart
                $existingCart = \App\Models\Cart::forCurrentUser()
                    ->where('product_id', $item->product_id)
                    ->where('size_id', $item->size_id)
                    ->first();
                
                if ($existingCart) {
                    $existingCart->increment('quantity', $item->quantity);
                } else {
                    \App\Models\Cart::create([
                        'user_id' => Auth::id(),
                        'session_id' => session()->getId(),
                        'product_id' => $item->product_id,
                        'size_id' => $item->size_id,
                        'quantity' => $item->quantity,
                    ]);
                }
                
                $addedItems++;
            }
            
            DB::commit();
            
            $message = $addedItems > 0 
                ? "{$addedItems} item berhasil ditambahkan ke keranjang"
                : "Tidak ada item yang dapat ditambahkan ke keranjang";
            
            if (!empty($unavailableItems)) {
                $message .= ". Item yang tidak tersedia: " . implode(', ', $unavailableItems);
            }
            
            return response()->json([
                'success' => $addedItems > 0,
                'message' => $message,
                'added_items' => $addedItems,
                'unavailable_items' => $unavailableItems,
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan item ke keranjang: ' . $e->getMessage()
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
        
        // Processing step
        $timeline[] = [
            'title' => 'Persiapan Pengiriman',
            'date' => null, // Add processing_date to orders table if needed
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
            'description' => $order->shipped_date ? 'Pesanan dalam perjalanan' : 'Sedang dikirim',
        ];
        
        // Delivery step
        $timeline[] = [
            'title' => 'Pesanan Diterima',
            'date' => $order->delivered_date,
            'status' => $order->status === 'delivered' ? 'completed' : 'pending',
            'icon' => 'fas fa-home',
            'description' => $order->delivered_date 
                ? 'Pesanan telah diterima' 
                : 'Menunggu konfirmasi penerimaan',
        ];
        
        return $timeline;
    }
    /**
     * Show order failed page
     */
    public function failed($orderId)
    {
        $order = Order::with('orderItems')->findOrFail($orderId);
        
        return view('user.orders.failed', compact('order'));
    }
    
    /**
     * Admin methods (if this controller also handles admin functions)
     */
    
    /**
     * Admin: List all orders
     */
    public function adminIndex(Request $request)
    {
        $query = Order::with(['user', 'orderItems'])->recent();
        
        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->byStatus($request->status);
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
            'pending_payment' => Order::byStatus('pending_payment')->count(),
            'paid' => Order::byStatus('paid')->count(),
            'processing' => Order::byStatus('processing')->count(),
            'shipped' => Order::byStatus('shipped')->count(),
            'delivered' => Order::byStatus('delivered')->count(),
        ];
        
        return view('admin.order.index', compact('orders', 'orderStats'));
    }
    
    /**
     * Admin: Update order status
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
     * Admin: Show specific order details
     */
    public function adminShow($id)
    {
        $order = Order::with(['user', 'orderItems.product', 'orderItems.productSize'])
            ->findOrFail($id);
        
        $timeline = $this->generateTrackingTimeline($order);
        
        if (request()->ajax()) {
            $html = view('admin.orders.show-modal', compact('order', 'timeline'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        }
        
        return view('admin.order.show', compact('order', 'timeline'));
    }
    /**
     * Admin: Download invoice
     */
    public function adminInvoice($id)
    {
        $order = Order::with(['user', 'orderItems.product'])
            ->findOrFail($id);
        
        return view('admin.order.invoice', compact('order'));
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
            'total_revenue' => Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])->sum('total_amount'),
            'monthly_revenue' => Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])
                                    ->whereMonth('order_date', now()->month)
                                    ->whereYear('order_date', now()->year)
                                    ->sum('total_amount'),
        ];
        
        return response()->json($stats);
    }
}