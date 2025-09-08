<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'city',
        'province',
        'postal_code',
        'payment_method',
        'subtotal',
        'tax',
        'shipping_cost',
        'promo_discount',
        'promo_code',
        'total',
        'status',
        'order_notes',
        'order_date',
        'payment_date',
        'shipped_date',
        'delivered_date',
        'payment_proof',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'tax' => 'integer',
        'shipping_cost' => 'integer',
        'promo_discount' => 'integer',
        'total' => 'integer',
        'order_date' => 'datetime',
        'payment_date' => 'datetime',
        'shipped_date' => 'datetime',
        'delivered_date' => 'datetime',
    ];

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessors
    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getFormattedTaxAttribute()
    {
        return 'Rp ' . number_format($this->tax, 0, ',', '.');
    }

    public function getFormattedShippingCostAttribute()
    {
        return 'Rp ' . number_format($this->shipping_cost, 0, ',', '.');
    }

    public function getFormattedPromoDiscountAttribute()
    {
        return 'Rp ' . number_format($this->promo_discount, 0, ',', '.');
    }

    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending_payment' => 'Menunggu Pembayaran',
            'paid' => 'Dibayar',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'delivered' => 'Diterima',
            'cancelled' => 'Dibatalkan',
            'refunded' => 'Dikembalikan',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending_payment' => 'yellow',
            'paid' => 'blue',
            'processing' => 'purple',
            'shipped' => 'indigo',
            'delivered' => 'green',
            'cancelled' => 'red',
            'refunded' => 'gray',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    public function getPaymentMethodLabelAttribute()
    {
        $labels = [
            'cod' => 'Cash on Delivery',
            'bank_transfer' => 'Transfer Bank',
            'credit_card' => 'Kartu Kredit',
            'dana' => 'DANA',
            'ovo' => 'OVO',
            'gopay' => 'GoPay',
        ];
        
        return $labels[$this->payment_method] ?? $this->payment_method;
    }

    public function getShippingEstimateAttribute()
    {
        // Default shipping estimate
        return $this->payment_method === 'cod' ? '3-5 Hari Kerja' : '2-3 Hari Kerja';
    }

    public function getCourierNameAttribute()
    {
        // You can customize this based on payment method or other factors
        return 'SoleStyle Express';
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Methods
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending_payment', 'paid']);
    }

    public function markAsPaid()
    {
        $this->update([
            'status' => 'paid',
            'payment_date' => now(),
        ]);
    }

    public function markAsProcessing()
    {
        $this->update([
            'status' => 'processing',
        ]);
    }

    public function markAsShipped()
    {
        $this->update([
            'status' => 'shipped',
            'shipped_date' => now(),
        ]);
    }

    public function markAsDelivered()
    {
        $this->update([
            'status' => 'delivered',
            'delivered_date' => now(),
        ]);
    }

    public function cancel()
    {
        if ($this->canBeCancelled()) {
            $this->update(['status' => 'cancelled']);
            
            // Restore product stock
            foreach ($this->orderItems as $item) {
                if ($item->size_id) {
                    ProductSize::where('id', $item->size_id)->increment('stock', $item->quantity);
                } else {
                    Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                }
            }
            
            return true;
        }
        
        return false;
    }
}