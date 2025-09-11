<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'sku', 
        'category_id',
        'brand',
        'price',
        'stock',
        'status',
        'description',
        'image'
    ];
    
    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function reviews()
    {
        return $this->hasManyThrough(
            Review::class,
            OrderItem::class,
            'product_id',     // Foreign key pada order_items table
            'order_item_id',  // Foreign key pada reviews table
            'id',            // Local key pada products table
            'id'             // Local key pada order_items table
        );
    }
    
    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    // Scopes
    public function scopeByCategory($query, $category)
    {
        if ($category) {
            return $query->where('category_id', $category);
        }
        return $query;
    }
    
    public function scopeByStatus($query, $status)
    {
        if ($status && $status !== 'Semua Status') {
            $statusMap = [
                'Aktif' => 'active',
                'Tidak Aktif' => 'inactive'
            ];
            
            $actualStatus = $statusMap[$status] ?? $status;
            return $query->where('status', $actualStatus);
        }
        return $query;
    }
    
    public function scopeByStock($query, $stockFilter)
    {
        if ($stockFilter && $stockFilter !== 'Semua Stok') {
            // Subquery untuk menghitung total stock dari sizes
            $totalStockSubquery = ProductSize::selectRaw('SUM(stock)')
                ->whereColumn('product_id', 'products.id');
            
            switch ($stockFilter) {
                case 'Stok Tersedia':
                    return $query->whereRaw("({$totalStockSubquery->toSql()}) > 10", $totalStockSubquery->getBindings());
                case 'Stok Rendah':
                    return $query->whereRaw("({$totalStockSubquery->toSql()}) > 0 AND ({$totalStockSubquery->toSql()}) <= 10", 
                        array_merge($totalStockSubquery->getBindings(), $totalStockSubquery->getBindings()));
                case 'Habis':
                    return $query->whereRaw("({$totalStockSubquery->toSql()}) = 0", $totalStockSubquery->getBindings());
                default:
                    return $query;
            }
        }
        return $query;
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    public function scopeInStock($query)
    {
        // Gunakan total stock dari sizes
        $totalStockSubquery = ProductSize::selectRaw('SUM(stock)')
            ->whereColumn('product_id', 'products.id');
            
        return $query->whereRaw("({$totalStockSubquery->toSql()}) > 0", $totalStockSubquery->getBindings());
    }
    
    // Accessors & Mutators
    public function getTotalStockAttribute()
    {
        // Total stok dari semua ukuran saja (tidak ditambah dengan stock produk utama)
        return $this->sizes->sum('stock');
    }
    
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
    
    public function getStatusTextAttribute()
    {
        return $this->status === 'active' ? 'Aktif' : 'Tidak Aktif';
    }
    
    public function getStatusBadgeClassAttribute()
    {
        return $this->status === 'active' 
            ? 'bg-green-500/20 text-green-400 border border-green-500/30'
            : 'bg-red-500/20 text-red-400 border border-red-500/30';
    }
    
    public function getStockBadgeClassAttribute()
    {
        if ($this->total_stock == 0) {
            return 'text-red-400';
        } elseif ($this->total_stock <= 10) {
            return 'text-yellow-400';
        } else {
            return 'text-green-400';
        }
    }
    
    public function getImageExistsAttribute()
    {
        return $this->image && Storage::disk('public')->exists($this->image);
    }
    
    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }
        
        // Fallback ke placeholder jika gambar tidak ada
        return asset('images/product-placeholder.png');
    }
    
    public function getStockStatusAttribute()
    {
        if ($this->total_stock == 0) {
            return 'out_of_stock';
        } elseif ($this->total_stock <= 10) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }
    
    public function getAvailabilityTextAttribute()
    {
        if ($this->total_stock == 0) {
            return 'Habis';
        } elseif ($this->total_stock <= 5) {
            return 'Stok Terbatas';
        } else {
            return 'Tersedia';
        }
    }
    
    public function getSlugAttribute()
    {
        return \Illuminate\Support\Str::slug($this->name);
    }
    
    public function getCategoryNameAttribute()
    {
        return $this->category ? $this->category->name : '-';
    }
    
    public function getSoldCountAttribute()
    {
        // Total quantity dari semua order items
        return $this->orderItems()->sum('quantity');
    }
    
    public function getAverageRatingAttribute()
    {
        // Implementasi sistem rating jika ada
        // Untuk sekarang, return default rating
        return '4.5';
    }
    
    // Methods
    public function isAvailable()
    {
        return $this->status === 'active' && $this->total_stock > 0;
    }
    
    public function isLowStock($threshold = 10)
    {
        return $this->total_stock > 0 && $this->total_stock <= $threshold;
    }
    
    public function isOutOfStock()
    {
        return $this->total_stock == 0;
    }
    
    public function reduceStock($quantity, $size = null)
    {
        if ($size) {
            // Kurangi stok dari ukuran tertentu
            $productSize = $this->sizes()->where('size', $size)->first();
            if ($productSize && $productSize->stock >= $quantity) {
                $productSize->decrement('stock', $quantity);
                $this->updateTotalStock(); // Update total stock
                return true;
            }
        } else {
            // Kurangi stok dari ukuran yang tersedia (FIFO atau berdasarkan logika lain)
            $remainingQuantity = $quantity;
            foreach ($this->sizes()->where('stock', '>', 0)->get() as $size) {
                if ($remainingQuantity <= 0) break;
                
                $deductAmount = min($remainingQuantity, $size->stock);
                $size->decrement('stock', $deductAmount);
                $remainingQuantity -= $deductAmount;
            }
            
            if ($remainingQuantity <= 0) {
                $this->updateTotalStock(); // Update total stock
                return true;
            }
        }
        return false;
    }
    
    public function addStock($quantity, $size = null)
    {
        if ($size) {
            // Tambah stok ke ukuran tertentu
            $productSize = $this->sizes()->where('size', $size)->first();
            if ($productSize) {
                $productSize->increment('stock', $quantity);
                $this->updateTotalStock(); // Update total stock
                return true;
            }
        }
        return false;
    }
    
    // Method untuk update total stock di field stock produk utama (opsional)
    public function updateTotalStock()
    {
        $totalStock = $this->sizes()->sum('stock');
        $this->update(['stock' => $totalStock]);
        return $totalStock;
    }
    
    // Boot method
    protected static function boot()
    {
        parent::boot();
        
        static::created(function ($product) {
            \Log::info("Produk baru dibuat: {$product->name} (ID: {$product->id})");
        });
        
        static::updated(function ($product) {
            if ($product->wasChanged('status')) {
                \Log::info("Status produk {$product->name} diubah menjadi: {$product->status}");
            }
            
            if ($product->wasChanged('stock')) {
                \Log::info("Stok produk {$product->name} diubah menjadi: {$product->stock}");
            }
        });
        
        static::deleting(function ($product) {
            // Perbaiki bug: gunakan $product->image bukan $this->image
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
        });
    }
}