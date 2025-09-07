<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Pesanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pesanan';
    
    protected $fillable = [
        'nomor_pesanan',
        'user_id',
        'nama_pelanggan',
        'email_pelanggan',
        'nomor_telepon',
        'alamat_lengkap',
        'kota',
        'provinsi',
        'kode_pos',
        'status',
        'metode_pembayaran',
        'subtotal',
        'biaya_pengiriman',
        'total_harga',
        'tanggal_pesanan',
        'tanggal_pembayaran',
        'tanggal_pengiriman',
        'tanggal_diterima',
        'nomor_resi',
        'bukti_pembayaran',
        'catatan_pembayaran',
        'catatan_admin',
        'catatan_pelanggan',
    ];

    protected $dates = [
        'tanggal_pesanan',
        'tanggal_pembayaran',
        'tanggal_pengiriman',
        'tanggal_diterima',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'biaya_pengiriman' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'tanggal_pesanan' => 'datetime',
        'tanggal_pembayaran' => 'datetime',
        'tanggal_pengiriman' => 'datetime',
        'tanggal_diterima' => 'datetime',
    ];

    // Boot method to set default values
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($pesanan) {
            if (empty($pesanan->tanggal_pesanan)) {
                $pesanan->tanggal_pesanan = now();
            }
            if (empty($pesanan->nomor_pesanan)) {
                $pesanan->nomor_pesanan = $pesanan->generateOrderNumber();
            }
        });
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pesananItems()
    {
        return $this->hasMany(PesananItem::class);
    }

    /**
     * Scopes
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('tanggal_pesanan', 'desc');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('tanggal_pesanan', Carbon::today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('tanggal_pesanan', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ]);
    }

    public function scopePaid($query)
    {
        return $query->whereIn('status', ['paid', 'processing', 'shipped', 'delivered']);
    }

    /**
     * Accessors & Mutators
     */
    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            'pending_payment' => 'Menunggu Pembayaran',
            'payment_verification' => 'Verifikasi Pembayaran',
            'paid' => 'Dibayar',
            'processing' => 'Dikemas',
            'shipped' => 'Dikirim',
            'delivered' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return $statusLabels[$this->status] ?? 'Status Tidak Diketahui';
    }

    public function getStatusColorAttribute()
    {
        $statusColors = [
            'pending_payment' => 'yellow',
            'payment_verification' => 'orange',
            'paid' => 'blue',
            'processing' => 'purple',
            'shipped' => 'indigo',
            'delivered' => 'green',
            'cancelled' => 'red',
        ];

        return $statusColors[$this->status] ?? 'gray';
    }

    public function getMetodePembayaranLabelAttribute()
    {
        $paymentMethods = [
            'bank_transfer' => 'Transfer Bank',
            'credit_card' => 'Kartu Kredit',
            'e_wallet' => 'E-Wallet',
            'cod' => 'Bayar di Tempat (COD)',
        ];

        return $paymentMethods[$this->metode_pembayaran] ?? 'Metode Tidak Diketahui';
    }

    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getFormattedBiayaPengirimanAttribute()
    {
        return 'Rp ' . number_format($this->biaya_pengiriman, 0, ',', '.');
    }

    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getTanggalPesananFormattedAttribute()
    {
        return $this->tanggal_pesanan ? $this->tanggal_pesanan->format('d M Y, H:i') : '-';
    }

    public function getTanggalPembayaranFormattedAttribute()
    {
        return $this->tanggal_pembayaran ? $this->tanggal_pembayaran->format('d M Y, H:i') : '-';
    }

    public function getTanggalPengirimanFormattedAttribute()
    {
        return $this->tanggal_pengiriman ? $this->tanggal_pengiriman->format('d M Y, H:i') : '-';
    }

    public function getTanggalDiterimaFormattedAttribute()
    {
        return $this->tanggal_diterima ? $this->tanggal_diterima->format('d M Y, H:i') : '-';
    }

    public function getAlamatLengkapFormattedAttribute()
    {
        $address = $this->alamat_lengkap;
        if ($this->kota) $address .= ', ' . $this->kota;
        if ($this->provinsi) $address .= ', ' . $this->provinsi;
        if ($this->kode_pos) $address .= ' ' . $this->kode_pos;
        
        return $address;
    }

    /**
     * Methods
     */
    public function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $random = mt_rand(1000, 9999);
        
        $orderNumber = $prefix . $date . $random;
        
        // Check if order number exists, if so, generate new one
        while (self::where('nomor_pesanan', $orderNumber)->exists()) {
            $random = mt_rand(1000, 9999);
            $orderNumber = $prefix . $date . $random;
        }
        
        return $orderNumber;
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending_payment', 'payment_verification']);
    }

    public function canBeUpdated()
    {
        return !in_array($this->status, ['delivered', 'cancelled']);
    }

    public function isPaid()
    {
        return in_array($this->status, ['paid', 'processing', 'shipped', 'delivered']);
    }

    public function isCompleted()
    {
        return $this->status === 'delivered';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function cancel()
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        try {
            \DB::beginTransaction();

            // Restore stock for all items
            foreach ($this->pesananItems as $item) {
                if ($item->size_id && $item->productSize) {
                    $item->productSize->increment('stock', $item->kuantitas);
                } elseif ($item->product) {
                    $item->product->increment('stock', $item->kuantitas);
                }
            }

            // Update order status
            $this->update(['status' => 'cancelled']);

            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollback();
            return false;
        }
    }

    public function markAsPaid()
    {
        $this->update([
            'status' => 'paid',
            'tanggal_pembayaran' => now(),
        ]);
    }

    public function markAsProcessing()
    {
        $this->update(['status' => 'processing']);
    }

    public function markAsShipped($trackingNumber = null)
    {
        $updateData = [
            'status' => 'shipped',
            'tanggal_pengiriman' => now(),
        ];

        if ($trackingNumber) {
            $updateData['nomor_resi'] = $trackingNumber;
        }

        $this->update($updateData);
    }

    public function markAsDelivered()
    {
        $this->update([
            'status' => 'delivered',
            'tanggal_diterima' => now(),
        ]);
    }

    public function getTotalItems()
    {
        return $this->pesananItems->sum('kuantitas');
    }

    public function getTotalWeight()
    {
        return $this->pesananItems->sum(function ($item) {
            return ($item->product->weight ?? 0) * $item->kuantitas;
        });
    }

    public function calculateTotal()
    {
        $subtotal = $this->pesananItems->sum(function ($item) {
            return $item->harga_satuan * $item->kuantitas;
        });

        $this->subtotal = $subtotal;
        $this->total_harga = $subtotal + $this->biaya_pengiriman;
        $this->save();

        return $this->total_harga;
    }

    public function hasPaymentProof()
    {
        return !empty($this->bukti_pembayaran);
    }

    public function getPaymentProofUrl()
    {
        return $this->bukti_pembayaran ? \Storage::url($this->bukti_pembayaran) : null;
    }

    public function getDaysOld()
    {
        return $this->tanggal_pesanan ? $this->tanggal_pesanan->diffInDays(now()) : 0;
    }

    public function getStatusBadgeClass()
    {
        $classes = [
            'pending_payment' => 'bg-yellow-100 text-yellow-800',
            'payment_verification' => 'bg-orange-100 text-orange-800',
            'paid' => 'bg-blue-100 text-blue-800',
            'processing' => 'bg-purple-100 text-purple-800',
            'shipped' => 'bg-indigo-100 text-indigo-800',
            'delivered' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];

        return $classes[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Static Methods
     */
    public static function getTotalRevenue($startDate = null, $endDate = null)
    {
        $query = self::paid();

        if ($startDate) {
            $query->whereDate('tanggal_pesanan', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('tanggal_pesanan', '<=', $endDate);
        }

        return $query->sum('total_harga');
    }

    public static function getOrdersCount($status = null, $startDate = null, $endDate = null)
    {
        $query = self::query();

        if ($status) {
            $query->byStatus($status);
        }

        if ($startDate) {
            $query->whereDate('tanggal_pesanan', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('tanggal_pesanan', '<=', $endDate);
        }

        return $query->count();
    }

    public static function getRecentOrders($limit = 10)
    {
        return self::with(['user', 'pesananItems'])
                   ->recent()
                   ->limit($limit)
                   ->get();
    }

    public static function getPendingPaymentOrders()
    {
        return self::byStatus('pending_payment')
                   ->where('tanggal_pesanan', '>=', now()->subDays(7))
                   ->get();
    }

    public static function getStatistics()
    {
        return [
            'total_orders' => self::count(),
            'total_revenue' => self::getTotalRevenue(),
            'pending_orders' => self::byStatus('pending_payment')->count(),
            'processing_orders' => self::byStatus('processing')->count(),
            'shipped_orders' => self::byStatus('shipped')->count(),
            'delivered_orders' => self::byStatus('delivered')->count(),
            'cancelled_orders' => self::byStatus('cancelled')->count(),
            'today_orders' => self::today()->count(),
            'month_orders' => self::thisMonth()->count(),
            'month_revenue' => self::paid()->thisMonth()->sum('total_harga'),
        ];
    }
}