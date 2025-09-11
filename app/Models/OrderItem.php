<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'size_id',
        'size',
        'quantity',
        'price',
        'subtotal',
    ];
    
    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];
    
    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function canBeReviewed()
    {
        return $this->order->status === 'delivered' && !$this->review;
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function productSize()
    {
        return $this->belongsTo(ProductSize::class, 'size_id');
    }
    
    // Accessors
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
    
    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
    
    public function getSizeDisplayAttribute()
    {
        return $this->size ?? 'N/A';
    }
    
    public function getImageUrlAttribute()
    {
        if ($this->product && $this->product->image) {
            return asset('storage/' . $this->product->image);
        }
        return null;
    }
}