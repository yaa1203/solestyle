@extends('admin.layouts.app')

@section('title', 'Detail Pesanan - SoleStyle Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
            Detail Pesanan
        </h1>
        <p class="text-slate-400 mt-2">Informasi lengkap pesanan #{{ $order->order_number }}</p>
    </div>
    
    <!-- Order Info Card -->
    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Order Details -->
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">Detail Pesanan</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Nomor Pesanan:</span>
                        <span class="text-white font-medium">#{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Tanggal Pesanan:</span>
                        <span class="text-white">{{ $order->order_date->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Status:</span>
                        <span class="px-3 py-1 bg-{{ $order->status_color }}-500/20 text-{{ $order->status_color }}-400 rounded-full text-sm font-medium">
                            {{ $order->status_label }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Metode Pembayaran:</span>
                        <span class="text-white">{{ $order->payment_method_label }}</span>
                    </div>
                    @if($order->payment_date)
                    <div class="flex justify-between">
                        <span class="text-slate-400">Tanggal Pembayaran:</span>
                        <span class="text-white">{{ $order->payment_date->format('d M Y, H:i') }}</span>
                    </div>
                    @endif
                    @if($order->shipped_date)
                    <div class="flex justify-between">
                        <span class="text-slate-400">Tanggal Pengiriman:</span>
                        <span class="text-white">{{ $order->shipped_date->format('d M Y, H:i') }}</span>
                    </div>
                    @endif
                    @if($order->delivered_date)
                    <div class="flex justify-between">
                        <span class="text-slate-400">Tanggal Selesai:</span>
                        <span class="text-white">{{ $order->delivered_date->format('d M Y, H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Customer Details -->
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">Detail Pelanggan</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Nama:</span>
                        <span class="text-white">{{ $order->customer_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Email:</span>
                        <span class="text-white">{{ $order->customer_email }}</span>
                    </div>
                    @if($order->customer_phone)
                    <div class="flex justify-between">
                        <span class="text-slate-400">Telepon:</span>
                        <span class="text-white">{{ $order->customer_phone }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-slate-400">Alamat:</span>
                        <span class="text-white">{{ $order->customer_address }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Kota:</span>
                        <span class="text-white">{{ $order->customer_city }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Provinsi:</span>
                        <span class="text-white">{{ $order->customer_province }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Kode Pos:</span>
                        <span class="text-white">{{ $order->customer_postal_code }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Admin Notes -->
        @if($order->admin_notes)
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-white mb-2">Catatan Admin</h3>
            <div class="bg-slate-700/50 rounded-lg p-4">
                <p class="text-white">{{ nl2br(e($order->admin_notes)) }}</p>
            </div>
        </div>
        @endif
        
        <!-- Payment Proof -->
        @if($order->payment_proof)
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-white mb-2">Bukti Pembayaran</h3>
            <div class="bg-slate-700/50 rounded-lg p-4">
                <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Pembayaran" class="max-w-full h-auto rounded-lg mb-2">
                @if($order->payment_notes)
                <p class="text-white text-sm">{{ nl2br(e($order->payment_notes)) }}</p>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Tracking Number -->
        @if($order->tracking_number)
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-white mb-2">Nomor Resi</h3>
            <div class="bg-slate-700/50 rounded-lg p-4">
                <p class="text-white">{{ $order->tracking_number }}</p>
                <a href="https://tracking.courier.com/{{ $order->tracking_number }}" target="_blank" 
                   class="text-blue-400 hover:text-blue-300 text-sm mt-2 inline-block">
                    <i class="fas fa-external-link-alt mr-1"></i>Lacak Pengiriman
                </a>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Order Items -->
    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6 mb-8">
        <h3 class="text-lg font-semibold text-white mb-4">Item Pesanan</h3>
        <div class="space-y-4">
            @foreach($order->orderItems as $item)
            <div class="flex items-center gap-4 bg-slate-700/50 rounded-lg p-4">
                <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden">
                    @if($item->image_url)
                        <img src="{{ $item->image_url }}" alt="{{ $item->product_name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-slate-600 flex items-center justify-center">
                            <i class="fas fa-image text-slate-400"></i>
                        </div>
                    @endif
                </div>
                
                <div class="flex-1 min-w-0">
                    <h4 class="font-medium text-white truncate">{{ $item->product_name }}</h4>
                    <p class="text-sm text-slate-400">
                        @if($item->size_display !== 'N/A')
                            Size: {{ $item->size_display }} • 
                        @endif
                        Qty: {{ $item->quantity }}
                    </p>
                </div>
                
                <div class="text-right">
                    <p class="font-medium text-white">{{ $item->formatted_subtotal }}</p>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Order Summary -->
        <div class="mt-6 pt-6 border-t border-slate-700">
            <div class="flex justify-between items-center">
                <div class="text-sm text-slate-400">
                    {{ $order->orderItems->count() }} item
                </div>
                <div class="text-right">
                    <p class="text-xl font-bold text-white">{{ $order->formatted_total }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Update Status Form -->
    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Update Status</h3>
        <form id="updateStatusForm" class="space-y-4">
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Status</label>
                <select name="status" id="statusSelect" class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2 text-white">
                    <option value="pending_payment" {{ $order->status === 'pending_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Dibayar</option>
                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Dikemas</option>
                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Dikirim</option>
                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            
            <div id="trackingNumberField" class="hidden">
                <label class="block text-sm font-medium text-slate-300 mb-2">Nomor Resi (untuk status Dikirim)</label>
                <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" 
                       placeholder="Masukkan nomor resi pengiriman"
                       class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2 text-white placeholder-slate-400">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Catatan Admin</label>
                <textarea name="admin_notes" rows="3" placeholder="Tambahkan catatan jika diperlukan..."
                          class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 resize-none">{{ $order->admin_notes }}</textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="button" onclick="window.history.back()" 
                        class="flex-1 bg-slate-600 hover:bg-slate-700 text-white py-2 rounded-lg font-medium transition-all">
                    Kembali
                </button>
                <button type="submit" 
                        class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white py-2 rounded-lg font-medium transition-all">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Show/hide tracking number field based on status
document.getElementById('statusSelect').addEventListener('change', function() {
    const trackingField = document.getElementById('trackingNumberField');
    if (this.value === 'shipped') {
        trackingField.classList.remove('hidden');
    } else {
        trackingField.classList.add('hidden');
    }
});

// Handle Update Status Form Submit
document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(`/admin/orders/{{ $order->id }}/update-status`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat mengupdate status', 'error');
    });
});

// Notification function
function showNotification(message, type = 'info') {
    const colors = {
        success: 'from-green-500 to-emerald-500',
        error: 'from-red-500 to-pink-500',
        warning: 'from-yellow-500 to-orange-500',
        info: 'from-blue-500 to-purple-500'
    };
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 bg-gradient-to-r ${colors[type]} text-white px-6 py-4 rounded-xl shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
    toast.innerHTML = `
        <div class="flex items-center space-x-3">
            <i class="fas ${icons[type]}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.remove('translate-x-full'), 100);
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 4000);
}
</script>
@endsection