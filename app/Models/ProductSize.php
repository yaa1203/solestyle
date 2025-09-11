<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'size',
        'stock'
    ];
    
    protected $casts = [
        'stock' => 'integer',
        'product_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    // Scopes
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }
    
    public function scopeBySize($query, $size)
    {
        return $query->where('size', $size);
    }
    
    // Accessors
    public function getIsAvailableAttribute()
    {
        return $this->stock > 0;
    }
    
    public function getStockStatusAttribute()
    {
        if ($this->stock == 0) {
            return 'out_of_stock';
        } elseif ($this->stock <= 5) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }
    
    public function getStockBadgeClassAttribute()
    {
        if ($this->stock == 0) {
            return 'text-red-400';
        } elseif ($this->stock <= 5) {
            return 'text-yellow-400';
        } else {
            return 'text-green-400';
        }
    }
    
    // Methods
    public function isAvailable()
    {
        return $this->stock > 0;
    }
    
    public function isLowStock($threshold = 5)
    {
        return $this->stock > 0 && $this->stock <= $threshold;
    }
    
    public function isOutOfStock()
    {
        return $this->stock == 0;
    }
    
    public function canFulfill($quantity)
    {
        return $this->stock >= $quantity;
    }
    
    // Boot method untuk auto update total stock produk
    protected static function boot()
    {
        parent::boot();
        
        // Update total stock produk setelah size dibuat/diupdate/dihapus
        static::saved(function ($productSize) {
            if ($productSize->product) {
                $productSize->product->updateTotalStock();
            }
        });
        
        static::deleted(function ($productSize) {
            if ($productSize->product) {
                $productSize->product->updateTotalStock();
            }
        });
    }
}