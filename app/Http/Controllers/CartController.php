<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Tampilkan halaman keranjang
    public function index()
    {
        $cartItems = Cart::with(['product.category', 'productSize'])
            ->forCurrentUser()
            ->get();

        // Transform data untuk view
        $cartItems->transform(function ($item) {
            $item->product->formatted_price = 'Rp ' . number_format($item->product->price, 0, ',', '.');
            $item->formatted_subtotal = 'Rp ' . number_format($item->subtotal, 0, ',', '.');
            $item->product->image_url = $item->product->image ? asset('storage/' . $item->product->image) : null;
            
            // Set size information
            $item->size_display = $item->productSize ? $item->productSize->size : ($item->size ?? 'N/A');
            $item->size_stock = $item->productSize ? $item->productSize->stock : 0;
            
            return $item;
        });

        $total = $cartItems->sum('subtotal');
        $formattedTotal = 'Rp ' . number_format($total, 0, ',', '.');

        return view('user.cart.index', compact('cartItems', 'total', 'formattedTotal'));
    }

    // Tambah ke keranjang
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:product_sizes,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $productSize = ProductSize::where('id', $request->size_id)
            ->where('product_id', $request->product_id)
            ->firstOrFail();

        // Cek apakah produk aktif
        if ($product->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak tersedia'
            ], 400);
        }

        // Cek stok ukuran
        if ($productSize->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi. Tersedia: ' . $productSize->stock . ' item'
            ], 400);
        }

        // Data cart
        $cartData = [
            'product_id' => $request->product_id,
            'size_id' => $request->size_id,
            'size' => $productSize->size, // Simpan juga size string untuk backward compatibility
            'quantity' => $request->quantity
        ];

        // Set user_id atau session_id
        if (auth()->check()) {
            $cartData['user_id'] = auth()->id();
        } else {
            $cartData['session_id'] = session()->getId();
        }

        // Cari cart item yang sudah ada (product + size yang sama)
        $existingCart = Cart::where('product_id', $request->product_id)
            ->where('size_id', $request->size_id);

        if (auth()->check()) {
            $existingCart->where('user_id', auth()->id());
        } else {
            $existingCart->where('session_id', session()->getId());
        }

        $existingCart = $existingCart->first();

        if ($existingCart) {
            // Cek total quantity setelah ditambah
            $newQuantity = $existingCart->quantity + $request->quantity;
            if ($newQuantity > $productSize->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Total quantity melebihi stok. Stok tersedia: ' . $productSize->stock . ', sudah di cart: ' . $existingCart->quantity
                ], 400);
            }

            // Update quantity
            $existingCart->quantity = $newQuantity;
            $existingCart->save();
            
            $message = 'Quantity produk di keranjang berhasil diperbarui';
        } else {
            // Buat cart baru
            Cart::create($cartData);
            $message = 'Produk berhasil ditambahkan ke keranjang';
        }

        // Hitung total items di cart
        $cartCount = Cart::forCurrentUser()->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => $message,
            'cart_count' => $cartCount
        ]);
    }

    // Update quantity
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::with(['product', 'productSize'])->forCurrentUser()->findOrFail($id);
        
        // Cek stok jika ada size_id
        if ($cartItem->size_id && $cartItem->productSize) {
            if ($request->quantity > $cartItem->productSize->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantity melebihi stok. Stok tersedia: ' . $cartItem->productSize->stock
                ], 400);
            }
        } else {
            // Fallback jika tidak ada size_id, cek stok produk
            if ($request->quantity > $cartItem->product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantity melebihi stok. Stok tersedia: ' . $cartItem->product->stock
                ], 400);
            }
        }

        $cartItem->update(['quantity' => $request->quantity]);

        $subtotal = $cartItem->product->price * $cartItem->quantity;
        
        // Hitung total keseluruhan
        $total = Cart::forCurrentUser()->get()->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        return response()->json([
            'success' => true,
            'subtotal' => number_format($subtotal, 0, ',', '.'),
            'total' => number_format($total, 0, ',', '.'),
            'cart_count' => Cart::forCurrentUser()->sum('quantity')
        ]);
    }

    // Hapus item dari keranjang
    public function remove($id)
    {
        $cartItem = Cart::forCurrentUser()->findOrFail($id);
        $cartItem->delete();

        $cartCount = Cart::forCurrentUser()->sum('quantity');
        $total = Cart::forCurrentUser()->get()->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus dari keranjang',
            'cart_count' => $cartCount,
            'total' => number_format($total, 0, ',', '.')
        ]);
    }

    // Kosongkan keranjang
    public function clear()
    {
        Cart::forCurrentUser()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil dikosongkan'
        ]);
    }

    // Get cart count untuk navbar
    public function count()
    {
        $count = Cart::forCurrentUser()->sum('quantity');
        
        return response()->json([
            'count' => $count
        ]);
    }
}