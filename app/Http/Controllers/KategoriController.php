<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Tampilkan halaman kategori untuk user
     */
    public function index()
    {
        // Ambil kategori aktif dengan jumlah produk
        $categories = Category::where('status', 'active')
            ->withCount(['products' => function($query) {
                $query->where('status', 'active'); // Hanya hitung produk aktif
            }])
            ->orderBy('name')
            ->get();

        // Statistik umum
        $totalProducts = Product::where('status', 'active')->count();
        $totalCategories = $categories->count();
        
        // Produk terbaru
        $newArrivals = Product::where('status', 'active')
            ->latest()
            ->limit(10)
            ->count();

        // Produk sale (contoh: produk dengan stok rendah atau bisa ditambah field is_sale)
        $saleProducts = Product::where('status', 'active')
            ->where('stock', '<=', 10)
            ->count();

        // Kategori dengan produk terbanyak (untuk featured)
        $featuredCategories = Category::where('status', 'active')
            ->withCount(['products' => function($query) {
                $query->where('status', 'active');
            }])
            ->orderByDesc('products_count')
            ->limit(6)
            ->get();

        return view('user.kategori.index', compact(
            'categories', 
            'totalProducts', 
            'totalCategories',
            'newArrivals',
            'saleProducts',
            'featuredCategories'
        ));
    }

    /**
     * Tampilkan produk berdasarkan kategori
     */
    public function show(Category $category, Request $request)
    {
        // Pastikan kategori aktif
        if ($category->status !== 'active') {
            abort(404, 'Kategori tidak ditemukan');
        }

        $query = $category->products()->where('status', 'active');

        // Filter berdasarkan harga
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter berdasarkan stok
        if ($request->filled('in_stock') && $request->in_stock) {
            $query->where('stock', '>', 0);
        }

        // Sorting
        $sortBy = $request->get('sort', 'name');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'popular':
                // Bisa ditambahkan field popularity atau berdasarkan stock
                $query->orderByDesc('stock');
                break;
            default:
                $query->orderBy('name');
        }

        $products = $query->paginate(12)->withQueryString();

        // Info kategori
        $categoryStats = [
            'total_products' => $category->products()->where('status', 'active')->count(),
            'min_price' => $category->products()->where('status', 'active')->min('price'),
            'max_price' => $category->products()->where('status', 'active')->max('price'),
            'avg_price' => $category->products()->where('status', 'active')->avg('price'),
        ];

        return view('user.kategori.show', compact('category', 'products', 'categoryStats'));
    }

    /**
     * API untuk mendapatkan kategori (untuk AJAX)
     */
    public function api()
    {
        $categories = Category::where('status', 'active')
            ->withCount(['products' => function($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('name')
            ->get()
            ->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'products_count' => $category->products_count,
                    'url' => route('user.kategori.show', $category->id)
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}