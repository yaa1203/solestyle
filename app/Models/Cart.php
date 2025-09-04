<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'product_id',
        'quantity',
        'size'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get subtotal for this cart item
    public function getSubtotalAttribute()
    {
        return $this->product->price * $this->quantity;
    }

    // Scope untuk cart berdasarkan session atau user
    public function scopeForCurrentUser($query)
    {
        if (auth()->check()) {
            return $query->where('user_id', auth()->id());
        }
        
        return $query->where('session_id', session()->getId());
    }
}