<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Category; // Tambahkan import Category model
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display user product catalog
     */
    public function index(Request $request)
    {
        // Query dengan relasi kategori dan ukuran
        $query = Product::with(['category', 'sizes'])->where('status', 'active');
        
        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('category', function($categoryQuery) use ($search) {
                      $categoryQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter berdasarkan kategori
        if ($request->filled('category') && is_array($request->category)) {
            $query->whereHas('category', function($categoryQuery) use ($request) {
                $categoryQuery->whereIn('name', $request->category);
            });
        }
        
        // Filter berdasarkan ukuran
        if ($request->filled('size') && is_array($request->size)) {
            $query->whereHas('sizes', function($sizeQuery) use ($request) {
                $sizeQuery->whereIn('size', $request->size);
            });
        }
        
        // Filter berdasarkan harga
        if ($request->filled('price_range')) {
            switch ($request->price_range) {
                case '0-500000':
                    $query->where('price', '<', 500000);
                    break;
                case '500000-1000000':
                    $query->whereBetween('price', [500000, 1000000]);
                    break;
                case '1000000-2000000':
                    $query->whereBetween('price', [1000000, 2000000]);
                    break;
                case '2000000+':
                    $query->where('price', '>', 2000000);
                    break;
            }
        }
        
        // Sorting
        $sortBy = $request->get('sort', 'popular');
        switch ($sortBy) {
            case 'price-low':
                $query->orderBy('price', 'asc');
                break;
            case 'price-high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default: // popular
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        // Pagination
        $products = $query->paginate(12)->withQueryString();
        
        // Transform products untuk menambahkan data yang dibutuhkan view
        $products->getCollection()->transform(function ($product) {
            $product->formatted_price = 'Rp ' . number_format($product->price, 0, ',', '.');
            $product->image_exists = !empty($product->image);
            $product->image_url = $product->image_exists ? asset('storage/' . $product->image) : null;
            $product->category_name = $product->category ? $product->category->name : 'Uncategorized';
            $product->total_stock = $product->sizes->sum('stock');
            $product->available_sizes = $product->sizes->filter(function ($size) {
                return $size->stock > 0;
            })->pluck('size');
            return $product;
        });
        
        // Data untuk filter - ambil nama kategori yang unik
        $categories = Category::whereHas('products', function($query) {
                $query->where('status', 'active');
            })
            ->orderBy('name')
            ->pluck('name')
            ->toArray();
            
        // Data untuk filter ukuran - ambil ukuran yang tersedia
        $availableSizes = ProductSize::select('size')
            ->whereHas('product', function($query) {
                $query->where('status', 'active');
            })
            ->where('stock', '>', 0)
            ->distinct()
            ->orderBy('size')
            ->pluck('size')
            ->toArray();
        
        return view('user.produk.index', compact('products', 'categories', 'availableSizes'));
    }

    /**
     * Show product detail
     */
    public function show(Product $product)
    {
        // Pastikan produk aktif
        if ($product->status !== 'active') {
            abort(404, 'Produk tidak ditemukan');
        }
        
        // Load relasi kategori dan ukuran
        $product->load(['category', 'sizes']);
        
        // Produk terkait berdasarkan kategori
        $relatedProducts = Product::with(['category', 'sizes'])
            ->where('status', 'active')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();
            
        // Transform related products
        $relatedProducts->transform(function ($product) {
            $product->formatted_price = 'Rp ' . number_format($product->price, 0, ',', '.');
            $product->image_exists = !empty($product->image);
            $product->image_url = $product->image_exists ? asset('storage/' . $product->image) : null;
            $product->total_stock = $product->sizes->sum('stock');
            return $product;
        });
        
        return view('user.produk.show', compact('product', 'relatedProducts'));
    }

    /**
     * API endpoint untuk mendapatkan produk (untuk AJAX)
     */
    public function apiProducts(Request $request)
    {
        $query = Product::with('category')->where('status', 'active');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('category', function($categoryQuery) use ($search) {
                      $categoryQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('categories')) {
            $query->whereHas('category', function($categoryQuery) use ($request) {
                $categoryQuery->whereIn('name', $request->categories);
            });
        }

        if ($request->filled('price_range')) {
            switch ($request->price_range) {
                case '0-500000':
                    $query->where('price', '<', 500000);
                    break;
                case '500000-1000000':
                    $query->whereBetween('price', [500000, 1000000]);
                    break;
                case '1000000-2000000':
                    $query->whereBetween('price', [1000000, 2000000]);
                    break;
                case '2000000+':
                    $query->where('price', '>', 2000000);
                    break;
            }
        }

        // Sorting
        $sortBy = $request->get('sort', 'popular');
        switch ($sortBy) {
            case 'price-low':
                $query->orderBy('price', 'asc');
                break;
            case 'price-high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12);

        // Transform products untuk API response
        $products->getCollection()->transform(function ($product) {
            $product->formatted_price = 'Rp ' . number_format($product->price, 0, ',', '.');
            $product->category_name = $product->category ? $product->category->name : 'Uncategorized';
            $product->image_url = $product->image ? asset('storage/' . $product->image) : null;
            return $product;
        });

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'has_more' => $products->hasMorePages()
            ]
        ]);
    }

    /**
     * Search suggestions for autocomplete
     */
    public function searchSuggestions(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = Product::with('category')
            ->where('status', 'active')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhereHas('category', function($categoryQuery) use ($query) {
                      $categoryQuery->where('name', 'like', "%{$query}%");
                  });
            })
            ->select('name', 'category_id')
            ->limit(8)
            ->get()
            ->map(function($product) {
                return [
                    'name' => $product->name,
                    'category' => $product->category ? $product->category->name : 'Uncategorized'
                ];
            });

        return response()->json($suggestions);
    }
}