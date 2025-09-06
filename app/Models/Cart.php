<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'size_id',
        'size',
        'quantity'
    ];

    protected $casts = [
        'quantity' => 'integer'
    ];

    // Relationship ke Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship ke ProductSize
    public function productSize()
    {
        return $this->belongsTo(ProductSize::class, 'size_id');
    }

    // Relationship ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk current user atau session
    public function scopeForCurrentUser($query)
    {
        if (auth()->check()) {
            return $query->where('user_id', auth()->id());
        } else {
            return $query->where('session_id', session()->getId());
        }
    }

    // Accessor untuk subtotal
    public function getSubtotalAttribute()
    {
        return $this->product->price * $this->quantity;
    }

    // Accessor untuk formatted subtotal
    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    // Get available stock for this cart item
    public function getAvailableStockAttribute()
    {
        if ($this->productSize) {
            return $this->productSize->stock;
        }
        
        // Fallback to product stock if no size
        return $this->product->stock ?? 0;
    }

    // Get size display name
    public function getSizeDisplayAttribute()
    {
        if ($this->productSize) {
            return $this->productSize->size;
        }
        
        return $this->size ?? 'N/A';
    }

    // Check if quantity exceeds available stock
    public function exceedsStock()
    {
        return $this->quantity > $this->available_stock;
    }

    // Boot method untuk auto-cleanup
    protected static function boot()
    {
        parent::boot();

        // Auto-delete cart items when product is deleted
        static::creating(function ($cart) {
            // Set session ID jika tidak ada user_id
            if (!$cart->user_id && !$cart->session_id) {
                $cart->session_id = session()->getId();
            }
        });
    }
}