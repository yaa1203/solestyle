<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category'); // Eager load category relationship

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhereHas('category', function($categoryQuery) use ($search) {
                      $categoryQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter berdasarkan status
        if ($request->filled('status') && $request->status !== 'Semua Status') {
            $statusValue = $request->status === 'Aktif' ? 'active' : 'inactive';
            $query->where('status', $statusValue);
        }

        // Filter berdasarkan stok total
        if ($request->filled('stock_filter') && $request->stock_filter !== 'Semua Stok') {
            switch ($request->stock_filter) {
                case 'Stok Tersedia':
                    $query->whereHas('sizes', function($sizeQuery) {
                        $sizeQuery->selectRaw('SUM(stock) as total_stock')
                                ->havingRaw('SUM(stock) > 10');
                    });
                    break;
                case 'Stok Rendah':
                    $query->whereHas('sizes', function($sizeQuery) {
                        $sizeQuery->selectRaw('SUM(stock) as total_stock')
                                ->havingRaw('SUM(stock) > 0 AND SUM(stock) <= 10');
                    });
                    break;
                case 'Habis':
                    $query->whereDoesntHave('sizes', function($sizeQuery) {
                        $sizeQuery->where('stock', '>', 0);
                    })->orWhereHas('sizes', function($sizeQuery) {
                        $sizeQuery->where('stock', 0);
                    });
                    break;
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['name', 'sku', 'price', 'stock', 'status', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } elseif ($sortBy === 'category') {
            // Sort by category name through relationship
            $query->join('categories', 'products.category_id', '=', 'categories.id')
                  ->orderBy('categories.name', $sortOrder)
                  ->select('products.*');
        }

        // Pagination
        $products = $query->paginate(15)->withQueryString();

        // Data untuk filter dropdown - menggunakan categories table
        $categories = Category::active()->orderBy('name')->get();
        $statusOptions = ['Semua Status', 'Aktif', 'Tidak Aktif'];
        $stockOptions = ['Semua Stok', 'Stok Tersedia', 'Stok Rendah', 'Habis'];

        return view('admin.product.index', compact(
            'products', 
            'categories', 
            'statusOptions', 
            'stockOptions'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::active()->get(); // hanya kategori aktif
        return view('admin.product.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'sku' => 'required|string|max:255|unique:products',
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric|min:0|max:999999999999.99',
        'stock' => 'required|integer|min:0',
        'status' => 'required|in:active,inactive',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'sizes' => 'required|array|min:1',
        'sizes.*.size' => 'required|string|max:10',
        'sizes.*.stock' => 'required|integer|min:0'
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $data = $request->only([
        'name', 'sku', 'category_id', 'price',
        'stock', 'status', 'description'
    ]);

    // Handle image upload
    if ($request->hasFile('image')) {
        try {
            $image = $request->file('image');
            if (!$image->isValid()) {
                return redirect()->back()
                    ->with('error', 'File gambar tidak valid!')
                    ->withInput();
            }
            $extension = $image->getClientOriginalExtension();
            $filename = Str::random(20) . '_' . time() . '.' . $extension;
            if (!Storage::disk('public')->exists('products')) {
                Storage::disk('public')->makeDirectory('products');
            }
            $imagePath = $image->storeAs('products', $filename, 'public');
            if (!Storage::disk('public')->exists($imagePath)) {
                return redirect()->back()
                    ->with('error', 'Gagal menyimpan gambar!')
                    ->withInput();
            }
            $data['image'] = $imagePath;
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal upload gambar: ' . $e->getMessage())
                ->withInput();
        }
    }

    try {
        // Buat produk
        $product = Product::create($data);
        
        // Handle gambar utama
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = Str::random(20) . '_' . time() . '.' . $extension;
            
            if (!Storage::disk('public')->exists('products')) {
                Storage::disk('public')->makeDirectory('products');
            }
            
            $imagePath = $image->storeAs('products', $filename, 'public');
            $product->update(['image' => $imagePath]);
            
            // Simpan juga di tabel product_images sebagai gambar utama
            $product->images()->create([
                'path' => $imagePath,
                'is_primary' => true
            ]);
        }
        
        // Handle gambar tambahan
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $extension = $image->getClientOriginalExtension();
                    $filename = Str::random(20) . '_' . time() . '.' . $extension;
                    
                    $imagePath = $image->storeAs('products', $filename, 'public');
                    
                    $product->images()->create([
                        'path' => $imagePath,
                        'is_primary' => false
                    ]);
                }
            }
        }
        
        // Handle ukuran sepatu
        if ($request->has('sizes') && is_array($request->sizes)) {
            $sizesData = [];
            foreach ($request->sizes as $size) {
                $sizesData[] = [
                    'size' => $size['size'],
                    'stock' => $size['stock']
                ];
            }
            $product->sizes()->createMany($sizesData);
        }
        
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    } catch (\Exception $e) {
        if (isset($data['image']) && Storage::disk('public')->exists($data['image'])) {
            Storage::disk('public')->delete($data['image']);
        }
        return redirect()->back()
            ->with('error', 'Gagal menambahkan produk: ' . $e->getMessage())
            ->withInput();
    }
}


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category'); // Load category relationship
        return view('admin.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::active()->get(); // Menggunakan categories table
        $product->load('category'); // Load category relationship
        return view('admin.product.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'sku' => [
            'required',
            'string',
            'max:255',
            Rule::unique('products')->ignore($product->id)
        ],
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric|min:0|max:999999999999.99',
        'stock' => 'required|integer|min:0',
        'status' => 'required|in:active,inactive',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'sizes' => 'required|array|min:1',
        'sizes.*.id' => 'nullable|exists:product_sizes,id',
        'sizes.*.size' => 'required|string|max:10',
        'sizes.*.stock' => 'required|integer|min:0'
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $data = $request->only([
        'name', 'sku', 'category_id', 'price', 
        'stock', 'status', 'description'
    ]);

    // Handle image upload
    if ($request->hasFile('image')) {
        try {
            $image = $request->file('image');
            if (!$image->isValid()) {
                return redirect()->back()
                    ->with('error', 'File gambar tidak valid!')
                    ->withInput();
            }
            
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            
            $extension = $image->getClientOriginalExtension();
            $filename = Str::random(20) . '_' . time() . '.' . $extension;
            if (!Storage::disk('public')->exists('products')) {
                Storage::disk('public')->makeDirectory('products');
            }
            $imagePath = $image->storeAs('products', $filename, 'public');
            if (!Storage::disk('public')->exists($imagePath)) {
                return redirect()->back()
                    ->with('error', 'Gagal menyimpan gambar!')
                    ->withInput();
            }
            $data['image'] = $imagePath;
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal upload gambar: ' . $e->getMessage())
                ->withInput();
        }
    }

    try {
        // Update produk
        $product->update($data);
        
        // Handle gambar utama
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            
            // Hapus gambar utama dari tabel product_images
            $product->images()->where('is_primary', true)->delete();
            
            // Upload gambar baru
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = Str::random(20) . '_' . time() . '.' . $extension;
            
            if (!Storage::disk('public')->exists('products')) {
                Storage::disk('public')->makeDirectory('products');
            }
            
            $imagePath = $image->storeAs('products', $filename, 'public');
            $product->update(['image' => $imagePath]);
            
            // Simpan juga di tabel product_images sebagai gambar utama
            $product->images()->create([
                'path' => $imagePath,
                'is_primary' => true
            ]);
        }
        
        // Handle gambar tambahan
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $extension = $image->getClientOriginalExtension();
                    $filename = Str::random(20) . '_' . time() . '.' . $extension;
                    
                    $imagePath = $image->storeAs('products', $filename, 'public');
                    
                    $product->images()->create([
                        'path' => $imagePath,
                        'is_primary' => false
                    ]);
                }
            }
        }
        
        // Handle ukuran sepatu
        if ($request->has('sizes')) {
            // Hapus ukuran yang ditandai untuk dihapus
            if ($request->has('removed_sizes')) {
                $product->sizes()->whereIn('id', $request->removed_sizes)->delete();
            }
            
            // Update atau buat ukuran baru
            foreach ($request->sizes as $sizeData) {
                if (!empty($sizeData['id'])) {
                    // Update existing size
                    $product->sizes()->where('id', $sizeData['id'])->update([
                        'size' => $sizeData['size'],
                        'stock' => $sizeData['stock']
                    ]);
                } else {
                    // Create new size
                    $product->sizes()->create([
                        'size' => $sizeData['size'],
                        'stock' => $sizeData['stock']
                    ]);
                }
            }
        }
        
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    } catch (\Exception $e) {
        if (isset($data['image']) && Storage::disk('public')->exists($data['image'])) {
            Storage::disk('public')->delete($data['image']);
        }
        return redirect()->back()
            ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage())
            ->withInput();
    }
}

public function setPrimaryImage(Request $request, Product $product)
{
    $validator = Validator::make($request->all(), [
        'image_id' => 'required|exists:product_images,id'
    ]);
    
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Data tidak valid!'
        ], 422);
    }
    
    try {
        // Hapus status utama dari semua gambar produk
        $product->images()->update(['is_primary' => false]);
        
        // Set gambar baru sebagai utama
        $product->images()->where('id', $request->image_id)->update(['is_primary' => true]);
        
        // Update juga field image di tabel products
        $primaryImage = $product->images()->where('is_primary', true)->first();
        if ($primaryImage) {
            $product->update(['image' => $primaryImage->path]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Gambar utama berhasil diubah!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengubah gambar utama: ' . $e->getMessage()
        ], 500);
    }
}

// Tambahkan method untuk update stok per ukuran
public function updateSizeStock(Request $request, Product $product)
{
    $validator = Validator::make($request->all(), [
        'sizes' => 'required|array',
        'sizes.*.id' => 'required|exists:product_sizes,id',
        'sizes.*.stock' => 'required|integer|min:0'
    ]);
    
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Data tidak valid!'
        ], 422);
    }
    
    try {
        foreach ($request->sizes as $size) {
            $product->sizes()->where('id', $size['id'])->update([
                'stock' => $size['stock']
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Stok per ukuran berhasil diperbarui!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal memperbarui stok: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            // Delete image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();
            
            return redirect()->route('products.index')
                ->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    /**
     * Delete multiple products
     */
    public function destroyMultiple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Data tidak valid!');
        }

        try {
            $products = Product::whereIn('id', $request->product_ids)->get();
            
            foreach ($products as $product) {
                // Delete image if exists
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $product->delete();
            }
            
            $count = count($request->product_ids);
            return redirect()->route('products.index')
                ->with('success', "{$count} produk berhasil dihapus!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    /**
     * Toggle product status (active/inactive)
     */
    public function toggleStatus(Product $product)
    {
        try {
            $product->update([
                'status' => $product->status === 'active' ? 'inactive' : 'active'
            ]);

            $newStatus = $product->status === 'active' ? 'aktif' : 'tidak aktif';
            
            return redirect()->back()
                ->with('success', "Status produk berhasil diubah menjadi {$newStatus}!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengubah status produk: ' . $e->getMessage());
        }
    }

    /**
     * Update stock for a product
     */
    public function updateStock(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'stock' => 'required|integer|min:0',
            'action' => 'required|in:set,add,subtract'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid!'
            ], 422);
        }

        try {
            $newStock = $request->stock;
            
            switch ($request->action) {
                case 'add':
                    $newStock = $product->stock + $request->stock;
                    break;
                case 'subtract':
                    $newStock = max(0, $product->stock - $request->stock);
                    break;
                case 'set':
                default:
                    $newStock = $request->stock;
                    break;
            }

            $product->update(['stock' => $newStock]);

            return response()->json([
                'success' => true,
                'message' => 'Stok berhasil diperbarui!',
                'new_stock' => $newStock
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui stok: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export products to CSV
     */
    public function export(Request $request)
    {
        $query = Product::with('category');

        // Apply same filters as index method
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhereHas('category', function($categoryQuery) use ($search) {
                      $categoryQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status') && $request->status !== 'Semua Status') {
            $statusValue = $request->status === 'Aktif' ? 'active' : 'inactive';
            $query->where('status', $statusValue);
        }

        if ($request->filled('stock_filter') && $request->stock_filter !== 'Semua Stok') {
            switch ($request->stock_filter) {
                case 'Stok Tersedia':
                    $query->where('stock', '>', 10);
                    break;
                case 'Stok Rendah':
                    $query->whereBetween('stock', [1, 10]);
                    break;
                case 'Habis':
                    $query->where('stock', '=', 0);
                    break;
            }
        }

        $products = $query->orderBy('name')->get();

        $filename = 'products_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for proper UTF-8 encoding in Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, [
                'ID',
                'Nama Produk',
                'SKU',
                'Kategori',
                'Harga',
                'Stok',
                'Status',
                'Deskripsi',
                'Tanggal Dibuat'
            ]);

            // Data
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->sku,
                    $product->category ? $product->category->name : '-',
                    $product->price,
                    $product->stock,
                    $product->status_text,
                    $product->description,
                    $product->created_at->format('d/m/Y H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get product data for API/AJAX requests
     */
    public function apiIndex(Request $request)
    {
        $query = Product::with('category');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhereHas('category', function($categoryQuery) use ($search) {
                      $categoryQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status') && $request->status !== 'Semua Status') {
            $statusValue = $request->status === 'Aktif' ? 'active' : 'inactive';
            $query->where('status', $statusValue);
        }

        if ($request->filled('stock_filter') && $request->stock_filter !== 'Semua Stok') {
            switch ($request->stock_filter) {
                case 'Stok Tersedia':
                    $query->where('stock', '>', 10);
                    break;
                case 'Stok Rendah':
                    $query->whereBetween('stock', [1, 10]);
                    break;
                case 'Habis':
                    $query->where('stock', '=', 0);
                    break;
            }
        }

        $products = $query->orderBy('name')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Helper method to handle image upload
     */
    private function handleImageUpload($imageFile, $oldImagePath = null)
    {
        try {
            // Validate file
            if (!$imageFile->isValid()) {
                throw new \Exception('File tidak valid');
            }

            // Delete old image if exists
            if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }

            // Create unique filename
            $extension = $imageFile->getClientOriginalExtension();
            $filename = Str::random(20) . '_' . time() . '.' . $extension;
            
            // Ensure products directory exists
            if (!Storage::disk('public')->exists('products')) {
                Storage::disk('public')->makeDirectory('products');
            }

            // Store the file
            $imagePath = $imageFile->storeAs('products', $filename, 'public');
            
            // Verify file was actually stored
            if (!Storage::disk('public')->exists($imagePath)) {
                throw new \Exception('File tidak tersimpan di storage');
            }
            
            return $imagePath;
            
        } catch (\Exception $e) {
            throw new \Exception('Upload gagal: ' . $e->getMessage());
        }
    }

    /**
     * Check if image exists and is accessible
     */
    public function checkImage(Product $product)
    {
        if (!$product->image) {
            return response()->json([
                'exists' => false,
                'message' => 'Produk tidak memiliki gambar'
            ]);
        }

        $exists = Storage::disk('public')->exists($product->image);
        $url = $exists ? asset('storage/' . $product->image) : null;

        return response()->json([
            'exists' => $exists,
            'url' => $url,
            'path' => $product->image,
            'full_path' => storage_path('app/public/' . $product->image)
        ]);
    }

    /**
 * Get product sizes
 */
public function getProductSizes(Product $product)
{
    $product->load('sizes');
    return response()->json([
        'success' => true,
        'product' => $product
    ]);
}
}