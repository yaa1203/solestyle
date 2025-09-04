<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Tampilkan halaman keranjang
    public function index()
    {
        $cartItems = Cart::with('product.category')
            ->forCurrentUser()
            ->get();

        // Transform data untuk view
        $cartItems->transform(function ($item) {
            $item->product->formatted_price = 'Rp ' . number_format($item->product->price, 0, ',', '.');
            $item->formatted_subtotal = 'Rp ' . number_format($item->subtotal, 0, ',', '.');
            $item->product->image_url = $item->product->image ? asset('storage/' . $item->product->image) : null;
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
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|integer'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Cek apakah produk aktif
        if ($product->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak tersedia'
            ]);
        }

        // Data cart
        $cartData = [
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'size' => $request->size
        ];

        // Set user_id atau session_id
        if (auth()->check()) {
            $cartData['user_id'] = auth()->id();
        } else {
            $cartData['session_id'] = session()->getId();
        }

        // Cari cart item yang sudah ada
        $existingCart = Cart::where('product_id', $request->product_id)
            ->where('size', $request->size);

        if (auth()->check()) {
            $existingCart->where('user_id', auth()->id());
        } else {
            $existingCart->where('session_id', session()->getId());
        }

        $existingCart = $existingCart->first();

        if ($existingCart) {
            // Update quantity
            $existingCart->quantity += $request->quantity;
            $existingCart->save();
        } else {
            // Buat cart baru
            Cart::create($cartData);
        }

        // Hitung total items di cart
        $cartCount = Cart::forCurrentUser()->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'cart_count' => $cartCount
        ]);
    }

    // Update quantity
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::forCurrentUser()->findOrFail($id);
        $cartItem->update(['quantity' => $request->quantity]);

        $cartItem->load('product');
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