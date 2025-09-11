<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewImage extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'review_id',
        'image_path'
    ];
    
    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }
    
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}