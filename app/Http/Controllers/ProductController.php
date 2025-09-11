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
        $query = Product::with(['category', 'sizes']); // Eager load sizes relationship juga
        
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
        
        // Filter berdasarkan stok - gunakan scope yang sudah diperbaiki
        if ($request->filled('stock_filter') && $request->stock_filter !== 'Semua Stok') {
            $query->byStock($request->stock_filter);
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['name', 'sku', 'price', 'status', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } elseif ($sortBy === 'category') {
            // Sort by category name through relationship
            $query->join('categories', 'products.category_id', '=', 'categories.id')
                  ->orderBy('categories.name', $sortOrder)
                  ->select('products.*');
        } elseif ($sortBy === 'stock') {
            // Sort by total stock dari sizes
            $query->withSum('sizes', 'stock')
                  ->orderBy('sizes_sum_stock', $sortOrder);
        }
        
        // Pagination
        $products = $query->paginate(15)->withQueryString();
        
        // Data untuk filter dropdown
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0|max:999999999999.99',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sizes' => 'required|array',
            'sizes.*.size' => 'required|string|max:10',
            'sizes.*.stock' => 'required|integer|min:0'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            try {
                $imagePath = $this->handleImageUpload($request->file('image'));
            } catch (\Exception $e) {
                return redirect()->back()
                    ->with('error', 'Gagal mengunggah gambar: ' . $e->getMessage())
                    ->withInput();
            }
        }
        
        // Hitung total stock dari sizes
        $totalStock = array_sum(array_column($request->sizes, 'stock'));
        
        // Simpan produk terlebih dahulu
        $data = $request->only([
            'name', 'sku', 'category_id', 'price', 
            'status', 'description'
        ]);
        
        // Set stock dari total sizes
        $data['stock'] = $totalStock;
        
        // Tambahkan path gambar jika ada
        if ($imagePath) {
            $data['image'] = $imagePath;
        }
        
        $product = Product::create($data);
        
        // Simpan ukuran sepatu
        $sizes = [];
        foreach ($request->sizes as $size) {
            $sizes[] = [
                'size' => $size['size'],
                'stock' => $size['stock']
            ];
        }
        
        $product->sizes()->createMany($sizes);
        
        // PERBAIKAN: Gunakan session()->flash() dan redirect langsung tanpa with()
        session()->flash('success', 'Produk berhasil ditambahkan!');
        return redirect()->route('products.index');
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
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sizes' => 'required|array',
            'sizes.*.size' => 'required|string|max:10',
            'sizes.*.stock' => 'required|integer|min:0',
            'sizes.*.id' => 'nullable|exists:product_sizes,id'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Handle image upload
        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            try {
                $imagePath = $this->handleImageUpload($request->file('image'), $product->image);
            } catch (\Exception $e) {
                return redirect()->back()
                    ->with('error', 'Gagal mengunggah gambar: ' . $e->getMessage())
                    ->withInput();
            }
        }
        
        // Hitung total stock dari sizes
        $totalStock = array_sum(array_column($request->sizes, 'stock'));
        
        // Update produk
        $data = $request->only([
            'name', 'sku', 'category_id', 'price', 
            'status', 'description'
        ]);
        
        // Update total stock
        $data['stock'] = $totalStock;
        
        // Update image path if a new image was uploaded
        if ($imagePath !== $product->image) {
            $data['image'] = $imagePath;
        }
        
        $product->update($data);
        
        // Update ukuran sepatu
        $sizesData = $request->sizes;
        
        // Hapus ukuran yang tidak ada di request
        $existingSizeIds = array_filter(array_column($sizesData, 'id'));
        $product->sizes()->whereNotIn('id', $existingSizeIds)->delete();
        
        // Update atau buat ukuran baru
        foreach ($sizesData as $sizeData) {
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
        
        // PERBAIKAN: Gunakan session()->flash() dan redirect langsung tanpa with()
        session()->flash('success', 'Produk berhasil diperbarui!');
        return redirect()->route('products.index');
    }
    
    // Method untuk update stok per ukuran
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
            
            // Update total stock di produk utama
            $product->updateTotalStock();
            
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
     * Update stock for a product (via sizes)
     */
    public function updateStock(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'size_id' => 'required|exists:product_sizes,id',
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
            $size = $product->sizes()->findOrFail($request->size_id);
            $newStock = $request->stock;
            
            switch ($request->action) {
                case 'add':
                    $newStock = $size->stock + $request->stock;
                    break;
                case 'subtract':
                    $newStock = max(0, $size->stock - $request->stock);
                    break;
                case 'set':
                default:
                    $newStock = $request->stock;
                    break;
            }
            
            $size->update(['stock' => $newStock]);
            
            // Update total stock di produk utama
            $totalStock = $product->updateTotalStock();
            
            return response()->json([
                'success' => true,
                'message' => 'Stok berhasil diperbarui!',
                'new_stock' => $newStock,
                'total_stock' => $totalStock
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
        $query = Product::with(['category', 'sizes']);
        
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
            $query->byStock($request->stock_filter);
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
                'Total Stok',
                'Detail Stok per Ukuran',
                'Status',
                'Deskripsi',
                'Tanggal Dibuat'
            ]);
            
            // Data
            foreach ($products as $product) {
                $sizeDetails = $product->sizes->map(function($size) {
                    return "Ukuran {$size->size}: {$size->stock}";
                })->join(', ');
                
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->sku,
                    $product->category ? $product->category->name : '-',
                    $product->price,
                    $product->total_stock, // Menggunakan total_stock dari sizes
                    $sizeDetails,
                    $product->status_text,
                    $product->description,
                    $product->created_at->format('d/m/Y H:i:s')
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function create()
    {
        $categories = Category::active()->get();
        return view('admin.product.create', compact('categories'));
    }
    
    public function show(Product $product)
    {
        $product->load(['category', 'sizes']);
        return view('admin.product.show', compact('product'));
    }
    
    public function edit(Product $product)
    {
        $categories = Category::active()->get();
        $product->load(['category', 'sizes']);
        return view('admin.product.edit', compact('product', 'categories'));
    }
    
    public function destroy(Product $product)
    {
        try {
            // Delete image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->delete();
            
            // PERBAIKAN: Gunakan session()->flash() dan redirect langsung
            session()->flash('success', 'Produk berhasil dihapus!');
            return redirect()->route('products.index');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
    
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
           // PERBAIKAN: Gunakan session()->flash() dan redirect langsung
            session()->flash('success', "{$count} produk berhasil dihapus!");
            return redirect()->route('products.index');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
    
    public function toggleStatus(Product $product)
    {
        try {
            $product->update([
                'status' => $product->status === 'active' ? 'inactive' : 'active'
            ]);
            $newStatus = $product->status === 'active' ? 'aktif' : 'tidak aktif';
            
            // PERBAIKAN: Gunakan session()->flash() dan redirect langsung
            session()->flash('success', "Status produk berhasil diubah menjadi {$newStatus}!");
            return redirect()->route('products.index');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengubah status produk: ' . $e->getMessage());
        }
    }
    
    public function apiIndex(Request $request)
    {
        $query = Product::with(['category', 'sizes']);
        
        // Apply filters (same as index method)
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
            $query->byStock($request->stock_filter);
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
            
            // Store the image
            $path = $imageFile->storeAs('products', $filename, 'public');
            
            return $path;
            
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
     * Get product sizes with stock
     */
    public function getProductSizes(Product $product)
    {
        $product->load('sizes');
        return response()->json([
            'success' => true,
            'product' => $product,
            'total_stock' => $product->total_stock
        ]);
    }
}