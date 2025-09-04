<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * Scope kategori aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope kategori nonaktif
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Warna badge status
     */
    public function getStatusBadgeColorAttribute()
    {
        return $this->status === 'active' ? 'green' : 'red';
    }

    /**
     * Text status
     */
    public function getStatusTextAttribute()
    {
        return $this->status === 'active' ? 'Aktif' : 'Nonaktif';
    }
}
