<?php
namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Order;
use App\Models\Payment;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        // Initialize variables
        $checkoutItems = collect();
        $subtotal = 0;
        $promoCode = null;
        $promoDiscount = 0;
        
        if ($request->isMethod('post')) {
            // Handle POST request from cart page
            $selectedItems = $request->input('selected_items', []);
            $promoCode = $request->input('promo_code');
            
            if (empty($selectedItems)) {
                return redirect()->route('cart.index')
                    ->with('error', 'Tidak ada item yang dipilih untuk checkout');
            }
            
            // Get cart items by IDs
            $cartIds = collect($selectedItems)->pluck('id')->toArray();
            
            $cartItems = Cart::with(['product.category', 'productSize'])
                ->forCurrentUser()
                ->whereIn('id', $cartIds)
                ->get();
            
            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Item yang dipilih tidak ditemukan');
            }
            
            // Transform cart items to checkout items
            foreach ($cartItems as $cartItem) {
                // Find matching selected item to get quantity
                $selectedItem = collect($selectedItems)->firstWhere('id', $cartItem->id);
                $quantity = $selectedItem ? (int)$selectedItem['quantity'] : $cartItem->quantity;
                
                // Validate stock
                $availableStock = $cartItem->productSize 
                    ? $cartItem->productSize->stock 
                    : $cartItem->product->stock;
                    
                if ($quantity > $availableStock) {
                    return redirect()->route('cart.index')
                        ->with('error', "Stok produk {$cartItem->product->name} tidak mencukupi");
                }
                
                // Add to checkout items
                $checkoutItems->push([
                    'cart_id' => $cartItem->id,
                    'product_id' => $cartItem->product->id,
                    'product' => $cartItem->product,
                    'size_id' => $cartItem->size_id,
                    'size_display' => $cartItem->productSize ? $cartItem->productSize->size : ($cartItem->size ?? 'N/A'),
                    'quantity' => $quantity,
                    'price' => $cartItem->product->price,
                    'subtotal' => $cartItem->product->price * $quantity,
                    'formatted_price' => 'Rp ' . number_format($cartItem->product->price, 0, ',', '.'),
                    'formatted_subtotal' => 'Rp ' . number_format($cartItem->product->price * $quantity, 0, ',', '.'),
                    'image_url' => $cartItem->product->image ? asset('storage/' . $cartItem->product->image) : null,
                ]);
            }
            
        } else {
            // Handle GET request - show all cart items
            $cartItems = Cart::with(['product.category', 'productSize'])
                ->forCurrentUser()
                ->get();
                
            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('info', 'Keranjang kosong. Silakan tambahkan produk terlebih dahulu');
            }
            
            // Transform all cart items
            foreach ($cartItems as $cartItem) {
                $checkoutItems->push([
                    'cart_id' => $cartItem->id,
                    'product_id' => $cartItem->product->id,
                    'product' => $cartItem->product,
                    'size_id' => $cartItem->size_id,
                    'size_display' => $cartItem->productSize ? $cartItem->productSize->size : ($cartItem->size ?? 'N/A'),
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                    'subtotal' => $cartItem->product->price * $cartItem->quantity,
                    'formatted_price' => 'Rp ' . number_format($cartItem->product->price, 0, ',', '.'),
                    'formatted_subtotal' => 'Rp ' . number_format($cartItem->product->price * $cartItem->quantity, 0, ',', '.'),
                    'image_url' => $cartItem->product->image ? asset('storage/' . $cartItem->product->image) : null,
                ]);
            }
        }
        
        // Calculate totals
        $subtotal = $checkoutItems->sum('subtotal');
        
        // Apply promo discount if valid
        if ($promoCode) {
            $promoDiscount = $this->calculatePromoDiscount($promoCode, $subtotal);
        }
        
        // Calculate other costs (no tax)
        $shippingCost = 0; // Free shipping
        $total = $subtotal - $promoDiscount + $shippingCost;
        
        // Format prices
        $formattedSubtotal = 'Rp ' . number_format($subtotal, 0, ',', '.');
        $formattedPromoDiscount = 'Rp ' . number_format($promoDiscount, 0, ',', '.');
        $formattedShipping = 'Rp ' . number_format($shippingCost, 0, ',', '.');
        $formattedTotal = 'Rp ' . number_format($total, 0, ',', '.');
        
        return view('user.checkout.index', compact(
            'checkoutItems',
            'subtotal',
            'promoCode',
            'promoDiscount',
            'shippingCost',
            'total',
            'formattedSubtotal',
            'formattedPromoDiscount',
            'formattedShipping',
            'formattedTotal'
        ));
    }
    
    /**
     * Calculate promo discount
     */
    private function calculatePromoDiscount($promoCode, $subtotal)
    {
        // Mock promo codes - replace with database lookup
        $promoCodes = [
            'WELCOME10' => ['type' => 'percentage', 'value' => 10, 'min_amount' => 0],
            'SAVE50K' => ['type' => 'fixed', 'value' => 50000, 'min_amount' => 200000],
            'FREESHIP' => ['type' => 'shipping', 'value' => 0, 'min_amount' => 0],
        ];
        
        $code = strtoupper($promoCode);
        
        if (!isset($promoCodes[$code])) {
            return 0;
        }
        
        $promo = $promoCodes[$code];
        
        if ($subtotal < $promo['min_amount']) {
            return 0;
        }
        
        switch ($promo['type']) {
            case 'percentage':
                return (int)($subtotal * $promo['value'] / 100);
            case 'fixed':
                return min($promo['value'], $subtotal);
            case 'shipping':
                return 0; // Handle shipping discount separately
            default:
                return 0;
        }
    }
    
    /**
     * Process checkout
     */
    public function process(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.cart_id' => 'required|exists:carts,id',
            'items.*.quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'payment_method' => 'required|string|in:cod,dana,ovo,gopay',
            'order_notes' => 'nullable|string|max:1000',
        ]);
        
        // Update profile jika kosong atau user mengisi data baru
        $user = Auth::user();
        $user->update([
            'phone' => $request->customer_phone,
            'address' => $request->shipping_address,
            'city' => $request->city,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
        ]);
        
        try {
            DB::beginTransaction();
            
            // Get cart items
            $cartIds = collect($request->items)->pluck('cart_id');
            $cartItems = Cart::with(['product', 'productSize'])
                ->whereIn('id', $cartIds)
                ->get();
            
            // Calculate totals
            $subtotal = 0;
            $orderItems = [];
            
            foreach ($request->items as $itemData) {
                $cartItem = $cartItems->firstWhere('id', $itemData['cart_id']);
                if (!$cartItem) {
                    throw new \Exception("Cart item not found");
                }
                
                $quantity = $itemData['quantity'];
                $itemSubtotal = $cartItem->product->price * $quantity;
                $subtotal += $itemSubtotal;
                
                // Validate stock
                $availableStock = $cartItem->productSize 
                    ? $cartItem->productSize->stock 
                    : $cartItem->product->stock;
                    
                if ($quantity > $availableStock) {
                    throw new \Exception("Stok produk {$cartItem->product->name} tidak mencukupi");
                }
                
                $orderItems[] = [
                    'product_id' => $cartItem->product->id,
                    'product_name' => $cartItem->product->name,
                    'size_id' => $cartItem->size_id,
                    'size' => $cartItem->productSize ? $cartItem->productSize->size : null,
                    'quantity' => $quantity,
                    'price' => $cartItem->product->price,
                    'subtotal' => $itemSubtotal,
                    'image_url' => $cartItem->product->image ? asset('storage/' . $cartItem->product->image) : null,
                ];
            }
            
            // Calculate final amounts (no tax)
            $shippingCost = 0; // Free shipping
            $promoDiscount = 0; // Add promo logic if needed
            $total = $subtotal - $promoDiscount + $shippingCost;
            
            // Generate order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            
            // Create order
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'city' => $request->city,
                'province' => $request->province,
                'postal_code' => $request->postal_code,
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'tax' => 0, // No tax
                'shipping_cost' => $shippingCost,
                'promo_discount' => $promoDiscount,
                'total' => $total,
                'status' => $request->payment_method === 'cod' ? 'pending' : 'pending_payment',
                'order_notes' => $request->order_notes,
                'order_date' => now(),
            ]);
            
            // Create order items
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'size_id' => $item['size_id'],
                    'size' => $item['size'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }
            
            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'amount' => $total,
                'status' => $request->payment_method === 'cod' ? 'pending' : 'pending',
                'transaction_id' => null,
            ]);
            
            // Update product stock
            foreach ($request->items as $itemData) {
                $cartItem = $cartItems->firstWhere('id', $itemData['cart_id']);
                $quantity = $itemData['quantity'];
                
                if ($cartItem->productSize) {
                    // Update size-specific stock
                    $cartItem->productSize->decrement('stock', $quantity);
                } else {
                    // Update general product stock
                    $cartItem->product->decrement('stock', $quantity);
                }
            }
            
            // Clear cart items
            Cart::whereIn('id', $cartIds)->delete();
            
            DB::commit();
            
            // Store order ID in session for success page
            session(['last_order_id' => $order->id]);
            
            // For COD, redirect to success page directly
            if ($request->payment_method === 'cod') {
                return response()->json([
                    'success' => true,
                    'message' => 'Order berhasil dibuat',
                    'redirect_url' => route('order.success')
                ]);
            }
            
            // For e-wallet, redirect to payment page
            return response()->json([
                'success' => true,
                'message' => 'Redirecting to payment page',
                'redirect_url' => route('payment.e-wallet', [
                    'order_id' => $order->id,
                    'payment_method' => $request->payment_method
                ])
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses order: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Show order success page
     */
    public function success()
    {
        $orderId = session('last_order_id');
        
        if (!$orderId) {
            return redirect()->route('dashboard')
                ->with('error', 'Tidak ada data pesanan yang ditemukan');
        }
        
        $order = Order::with('orderItems')->find($orderId);
        
        if (!$order) {
            return redirect()->route('dashboard')
                ->with('error', 'Pesanan tidak ditemukan');
        }
        
        // Clear the session after retrieving the order
        session()->forget('last_order_id');
        
        return view('user.orders.success', compact('order'));
    }
}