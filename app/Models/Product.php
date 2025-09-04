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

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Scope untuk filter berdasarkan kategori
     * DIPERBAIKI: menggunakan category_id, bukan category
     */
    public function scopeByCategory($query, $category)
    {
        if ($category) {
            return $query->where('category_id', $category);
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan status
     */
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

    /**
     * Scope untuk filter berdasarkan stok
     */
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

    /**
     * Scope untuk produk aktif (untuk user)
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope untuk produk dengan stok tersedia
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Accessor untuk format harga
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Accessor untuk status text
     */
    public function getStatusTextAttribute()
    {
        return $this->status === 'active' ? 'Aktif' : 'Tidak Aktif';
    }

    /**
     * Accessor untuk badge class berdasarkan status
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->status === 'active' 
            ? 'inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-400 border border-green-500/30'
            : 'inline-block px-3 py-1 rounded-full text-xs font-semibold bg-red-500/20 text-red-400 border border-red-500/30';
    }

    /**
     * Accessor untuk badge class berdasarkan stok
     */
    public function getStockBadgeClassAttribute()
    {
        if ($this->stock == 0) {
            return 'inline-block px-3 py-1 rounded-full text-xs font-semibold bg-red-500/20 text-red-400 border border-red-500/30 cursor-pointer hover:bg-red-500/30';
        } elseif ($this->stock <= 10) {
            return 'inline-block px-3 py-1 rounded-full text-xs font-semibold bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 cursor-pointer hover:bg-yellow-500/30';
        } else {
            return 'inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-400 border border-green-500/30 cursor-pointer hover:bg-green-500/30';
        }
    }

    /**
     * Accessor untuk mengecek apakah gambar ada
     */
    public function getImageExistsAttribute()
    {
        return $this->image && Storage::disk('public')->exists($this->image);
    }

    /**
     * Accessor untuk URL gambar
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }
        
        // Fallback ke placeholder jika gambar tidak ada
        return asset('images/product-placeholder.png');
    }

    /**
     * Accessor untuk thumbnail URL
     */
    public function getThumbnailUrlAttribute()
    {
        // Untuk implementasi future: bisa membuat thumbnail dari gambar asli
        return $this->image_url;
    }

    /**
     * Accessor untuk status stok
     */
    public function getStockStatusAttribute()
    {
        if ($this->stock == 0) {
            return 'out_of_stock';
        } elseif ($this->stock <= 10) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    /**
     * Accessor untuk availability text
     */
    public function getAvailabilityTextAttribute()
    {
        if ($this->stock == 0) {
            return 'Habis';
        } elseif ($this->stock <= 5) {
            return 'Stok Terbatas';
        } else {
            return 'Tersedia';
        }
    }

    /**
     * Accessor untuk SEO friendly slug
     */
    public function getSlugAttribute()
    {
        return \Illuminate\Support\Str::slug($this->name);
    }

    /**
     * Accessor untuk nama kategori (backward compatibility)
     */
    public function getCategoryNameAttribute()
    {
        return $this->category ? $this->category->name : '-';
    }

    /**
     * Method untuk check apakah produk available untuk dibeli
     */
    public function isAvailable()
    {
        return $this->status === 'active' && $this->stock > 0;
    }

    /**
     * Method untuk check apakah stok rendah
     */
    public function isLowStock($threshold = 10)
    {
        return $this->stock > 0 && $this->stock <= $threshold;
    }

    /**
     * Method untuk check apakah stok habis
     */
    public function isOutOfStock()
    {
        return $this->stock == 0;
    }

    /**
     * Method untuk mengurangi stok (untuk order)
     */
    public function reduceStock($quantity)
    {
        if ($this->stock >= $quantity) {
            $this->decrement('stock', $quantity);
            return true;
        }
        return false;
    }

    /**
     * Method untuk menambah stok
     */
    public function addStock($quantity)
    {
        $this->increment('stock', $quantity);
        return true;
    }

    /**
     * Boot method untuk handle events
     */
    protected static function boot()
    {
        parent::boot();

        // Event ketika produk dibuat
        static::created(function ($product) {
            // Log atau trigger event lain jika diperlukan
            \Log::info("Produk baru dibuat: {$product->name} (ID: {$product->id})");
        });

        // Event ketika produk diupdate
        static::updated(function ($product) {
            // Log perubahan status jika diperlukan
            if ($product->wasChanged('status')) {
                \Log::info("Status produk {$product->name} diubah menjadi: {$product->status}");
            }
            
            if ($product->wasChanged('stock')) {
                \Log::info("Stok produk {$product->name} diubah menjadi: {$product->stock}");
            }
        });

        // Event ketika produk dihapus
        static::deleting(function ($product) {
            // Hapus gambar jika ada
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
        });
    }
}