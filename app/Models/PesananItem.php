<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PesananItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pesanan_items';
    
    protected $fillable = [
        'pesanan_id',
        'product_id',
        'size_id',
        'nama_produk',
        'deskripsi_produk',
        'sku_produk',
        'size_display',
        'kuantitas',
        'harga_satuan',
        'subtotal',
        'image_url',
        'catatan_item',
    ];

    protected $casts = [
        'kuantitas' => 'integer',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($item) {
            // Auto calculate subtotal
            $item->subtotal = $item->harga_satuan * $item->kuantitas;
        });
    }

    /**
     * Relationships
     */
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed(); // Include soft deleted products
    }

    public function productSize()
    {
        return $this->belongsTo(ProductSize::class, 'size_id')->withTrashed();
    }

    /**
     * Accessors & Mutators
     */
    public function getFormattedHargaSatuanAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getSizeDisplayFormattedAttribute()
    {
        if ($this->size_display && $this->size_display !== 'N/A') {
            return $this->size_display;
        }
        
        if ($this->productSize) {
            return $this->productSize->name;
        }
        
        return 'N/A';
    }

    public function getImageUrlAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        // Fallback to product image if item image not available
        if ($this->product && $this->product->image_url) {
            return $this->product->image_url;
        }
        
        return null;
    }

    /**
     * Methods
     */
    public function calculateSubtotal()
    {
        $this->subtotal = $this->harga_satuan * $this->kuantitas;
        $this->save();
        return $this->subtotal;
    }

    public function getTotalWeight()
    {
        if ($this->product && $this->product->weight) {
            return $this->product->weight * $this->kuantitas;
        }
        return 0;
    }

    public function hasValidProduct()
    {
        return $this->product !== null;
    }

    public function hasValidSize()
    {
        return $this->size_id === null || $this->productSize !== null;
    }

    public function isStockSufficient()
    {
        if (!$this->hasValidProduct()) {
            return false;
        }

        if ($this->size_id) {
            if (!$this->hasValidSize()) {
                return false;
            }
            return $this->productSize->stock >= $this->kuantitas;
        }

        return $this->product->stock >= $this->kuantitas;
    }

    public function getAvailableStock()
    {
        if (!$this->hasValidProduct()) {
            return 0;
        }

        if ($this->size_id && $this->hasValidSize()) {
            return $this->productSize->stock;
        }

        return $this->product->stock ?? 0;
    }

    public function updateFromProduct()
    {
        if (!$this->product) {
            return false;
        }

        $updateData = [
            'nama_produk' => $this->product->name,
            'deskripsi_produk' => $this->product->description,
            'sku_produk' => $this->product->sku,
        ];

        if ($this->productSize) {
            $updateData['size_display'] = $this->productSize->name;
            $updateData['harga_satuan'] = $this->productSize->price ?? $this->product->price;
        } else {
            $updateData['harga_satuan'] = $this->product->price;
        }

        if ($this->product->image_url) {
            $updateData['image_url'] = $this->product->image_url;
        }

        $this->update($updateData);
        return true;
    }

    public function reduceStock()
    {
        if (!$this->hasValidProduct()) {
            return false;
        }

        try {
            \DB::beginTransaction();

            if ($this->size_id && $this->hasValidSize()) {
                if ($this->productSize->stock >= $this->kuantitas) {
                    $this->productSize->decrement('stock', $this->kuantitas);
                } else {
                    throw new \Exception("Insufficient stock for {$this->nama_produk} size {$this->size_display}");
                }
            } else {
                if ($this->product->stock >= $this->kuantitas) {
                    $this->product->decrement('stock', $this->kuantitas);
                } else {
                    throw new \Exception("Insufficient stock for {$this->nama_produk}");
                }
            }

            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function restoreStock()
    {
        if (!$this->hasValidProduct()) {
            return false;
        }

        try {
            \DB::beginTransaction();

            if ($this->size_id && $this->hasValidSize()) {
                $this->productSize->increment('stock', $this->kuantitas);
            } else {
                $this->product->increment('stock', $this->kuantitas);
            }

            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    /**
     * Scopes
     */
    public function scopeForPesanan($query, $pesananId)
    {
        return $query->where('pesanan_id', $pesananId);
    }

    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeWithSize($query)
    {
        return $query->whereNotNull('size_id');
    }

    public function scopeWithoutSize($query)
    {
        return $query->whereNull('size_id');
    }

    /**
     * Static Methods
     */
    public static function createFromCartItem($cartItem, $pesananId)
    {
        $product = $cartItem->product;
        $productSize = $cartItem->productSize;

        $itemData = [
            'pesanan_id' => $pesananId,
            'product_id' => $cartItem->product_id,
            'size_id' => $cartItem->size_id,
            'nama_produk' => $product->name,
            'deskripsi_produk' => $product->description,
            'sku_produk' => $product->sku,
            'kuantitas' => $cartItem->quantity,
            'image_url' => $product->image_url,
        ];

        if ($productSize) {
            $itemData['size_display'] = $productSize->name;
            $itemData['harga_satuan'] = $productSize->price ?? $product->price;
        } else {
            $itemData['harga_satuan'] = $product->price;
            $itemData['size_display'] = 'N/A';
        }

        $itemData['subtotal'] = $itemData['harga_satuan'] * $itemData['kuantitas'];

        return self::create($itemData);
    }

    public static function getTotalQuantity($pesananId)
    {
        return self::forPesanan($pesananId)->sum('kuantitas');
    }

    public static function getTotalSubtotal($pesananId)
    {
        return self::forPesanan($pesananId)->sum('subtotal');
    }

    public static function getItemsByProduct($productId)
    {
        return self::forProduct($productId)
                   ->with(['pesanan', 'productSize'])
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Additional helper methods
     */
    public function canBeReturned()
    {
        if (!$this->pesanan) {
            return false;
        }

        // Can only return delivered items within 7 days
        return $this->pesanan->status === 'delivered' &&
               $this->pesanan->tanggal_diterima &&
               $this->pesanan->tanggal_diterima->diffInDays(now()) <= 7;
    }

    public function getProductUrl()
    {
        if ($this->product) {
            return route('products.show', $this->product->slug ?? $this->product->id);
        }
        return null;
    }

    public function toArray()
    {
        $array = parent::toArray();
        
        // Add formatted attributes
        $array['formatted_harga_satuan'] = $this->formatted_harga_satuan;
        $array['formatted_subtotal'] = $this->formatted_subtotal;
        $array['size_display_formatted'] = $this->size_display_formatted;
        $array['total_weight'] = $this->getTotalWeight();
        $array['available_stock'] = $this->getAvailableStock();
        $array['product_url'] = $this->getProductUrl();
        
        return $array;
    }
}