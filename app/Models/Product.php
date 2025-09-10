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
            switch ($stockFilter) {
                case 'Stok Tersedia':
                    return $query->where('stock', '>', 10);
                case 'Stok Rendah':
                    return $query->where('stock', '>', 0)->where('stock', '<=', 10);
                case 'Habis':
                    return $query->where('stock', 0);
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
        return $query->where('stock', '>', 0);
    }
    
    // Accessors & Mutators
    public function getTotalStockAttribute()
    {
        // Total stok dari produk utama + semua ukuran
        $totalStock = $this->stock;
        
        foreach ($this->sizes as $size) {
            $totalStock += $size->stock;
        }
        
        return $totalStock;
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
    
    public function reduceStock($quantity)
    {
        if ($this->stock >= $quantity) {
            $this->decrement('stock', $quantity);
            return true;
        }
        return false;
    }
    
    public function addStock($quantity)
    {
        $this->increment('stock', $quantity);
        return true;
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
            if ($product->image && Storage::disk('public')->exists($this->image)) {
                Storage::disk('public')->delete($this->image);
            }
        });
    }
}