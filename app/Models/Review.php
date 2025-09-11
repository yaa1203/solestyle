<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Review extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_item_id',
        'user_id',
        'rating',
        'comment'
    ];
    
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function images(): HasMany
    {
        return $this->hasMany(ReviewImage::class);
    }
    
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('d M Y, H:i');
    }
    
    public function getIsHelpfulCountAttribute()
    {
        return $this->helpful_count ?? 0;
    }
    
    public function getCanBeEditedAttribute()
    {
        return $this->created_at->diffInHours(now()) < 24;
    }
}