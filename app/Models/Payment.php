<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'payment_method',
        'amount',
        'status',
        'transaction_id',
        'payment_response',
        'receipt_path',
    ];
    
    // Relationship with order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}