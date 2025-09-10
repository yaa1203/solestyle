@extends('admin.layouts.app')
@section('title', 'Detail Pesanan - Admin SoleStyle')
@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
            Detail Pesanan #{{ $order->order_number }}
        </h1>
        <p class="text-slate-400 mt-2">Informasi lengkap pesanan dari customer</p>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Information -->
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl shadow-xl p-6">
                <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-user text-purple-400 mr-3"></i>
                    Informasi Pembeli
                </h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Nama:</span>
                        <span class="text-white font-medium">{{ $order->customer_name }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-slate-400">Email:</span>
                        <span class="text-white font-medium">{{ $order->customer_email }}</span>
                    </div>
                    
                    @if($order->customer_phone)
                    <div class="flex justify-between">
                        <span class="text-slate-400">Telepon:</span>
                        <span class="text-white font-medium">{{ $order->customer_phone }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between">
                        <span class="text-slate-400">Metode Pembayaran:</span>
                        <span class="text-white font-medium">{{ $order->payment_method_label }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-slate-400">Status:</span>
                        <span class="px-3 py-1 bg-{{ $order->status_color }}-500/20 text-{{ $order->status_color }}-400 rounded-full text-sm font-medium">
                            {{ $order->status_label }}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Shipping Information -->
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl shadow-xl p-6">
                <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-truck text-purple-400 mr-3"></i>
                    Alamat Pengiriman
                </h3>
                
                <div class="space-y-3">
                    <div class="mb-4">
                        <p class="text-white">{{ $order->shipping_address }}</p>
                    </div>
                    
                    <div class="flex flex-wrap gap-4">
                        @if($order->city)
                        <div>
                            <span class="text-slate-400">Kota:</span>
                            <span class="text-white ml-2">{{ $order->city }}</span>
                        </div>
                        @endif
                        
                        @if($order->province)
                        <div>
                            <span class="text-slate-400">Provinsi:</span>
                            <span class="text-white ml-2">{{ $order->province }}</span>
                        </div>
                        @endif
                        
                        @if($order->postal_code)
                        <div>
                            <span class="text-slate-400">Kode Pos:</span>
                            <span class="text-white ml-2">{{ $order->postal_code }}</span>
                        </div>
                        @endif
                    </div>
                    
                    @if($order->tracking_number)
                    <div class="mt-4 pt-4 border-t border-slate-700">
                        <div class="flex items-center">
                            <i class="fas fa-truck text-purple-400 mr-2"></i>
                            <span class="text-slate-400">Nomor Resi:</span>
                            <span class="text-white ml-2 font-mono">{{ $order->tracking_number }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl shadow-xl p-6">
                <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-shopping-bag text-purple-400 mr-3"></i>
                    Item Pesanan
                </h3>
                
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 flex-shrink-0 rounded-lg overflow-hidden">
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
                            <h4 class="font-medium text-white">{{ $item->product_name }}</h4>
                            <p class="text-sm text-slate-400">
                                @if($item->size_display !== 'N/A')
                                    Size: {{ $item->size_display }} • 
                                @endif
                                Qty: {{ $item->quantity }} • {{ $item->formatted_price }}
                            </p>
                        </div>
                        
                        <div class="text-right">
                            <p class="font-medium text-white">{{ $item->formatted_subtotal }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Timeline -->
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl shadow-xl p-6">
                <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-timeline text-purple-400 mr-3"></i>
                    Timeline Pesanan
                </h3>
                
                <div class="space-y-4">
                    @foreach($timeline as $event)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center
                                {{ $event['status'] === 'completed' ? 'bg-green-500/20 text-green-400' : 
                                ($event['status'] === 'current' ? 'bg-purple-500/20 text-purple-400' : 'bg-slate-700/50 text-slate-500') }}">
                                <i class="{{ $event['icon'] }}"></i>
                            </div>
                            <div class="w-0.5 h-full bg-slate-700 mt-2"></div>
                        </div>
                        
                        <div class="flex-1 pb-4">
                            <h4 class="font-medium text-white">{{ $event['title'] }}</h4>
                            <p class="text-sm text-slate-400 mt-1">{{ $event['description'] }}</p>
                            @if($event['date'])
                            <p class="text-xs text-slate-500 mt-1">
                                {{ $event['date']->format('d M Y, H:i') }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Right Column: Order Summary & Actions -->
        <div class="lg:col-span-1">
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl shadow-xl p-6 sticky top-24">
                <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                    <i class="fas fa-receipt text-purple-400 mr-3"></i>
                    Ringkasan Pesanan
                </h3>
                
                <!-- Price Breakdown -->
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-300">Subtotal</span>
                        <span class="text-white">{{ $order->formatted_subtotal }}</span>
                    </div>
                    
                    @if($order->promo_discount > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-300">Diskon</span>
                        <span class="text-green-400">-{{ $order->formatted_promo_discount }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-300">Ongkos Kirim</span>
                        <span class="text-white">{{ $order->formatted_shipping_cost }}</span>
                    </div>
                    
                    <!-- Ganti Pajak dengan Qty -->
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-300">Qty</span>
                        <span class="text-white font-medium">{{ $order->orderItems->sum('quantity') }} item</span>
                    </div>
                    
                    <hr class="border-slate-600">
                    
                    <div class="flex justify-between">
                        <span class="font-bold text-white text-lg">Total</span>
                        <span class="font-bold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">{{ $order->formatted_total }}</span>
                    </div>
                </div>
                
                <!-- Payment Information -->
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-slate-300 mb-3">Informasi Pembayaran</h4>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Metode:</span>
                            <span class="text-white">{{ $order->payment_method_label }}</span>
                        </div>
                        
                        @if($order->payment_date)
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Tanggal Bayar:</span>
                            <span class="text-white">{{ $order->payment_date->format('d M Y, H:i') }}</span>
                        </div>
                        @endif
                        
                        @if($order->payment && $order->payment->receipt_path)
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-400">Bukti Bayar:</span>
                                <button onclick="viewPaymentProof('{{ asset('storage/' . $order->payment->receipt_path) }}')" 
                                        class="text-purple-400 hover:text-purple-300">
                                    <i class="fas fa-eye mr-1"></i>Lihat
                                </button>
                            </div>
                        @endif
                    </div>
                </div>   
                
                <!-- Order Notes -->
                @if($order->admin_notes)
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-slate-300 mb-3">Catatan Admin</h4>
                    <div class="bg-slate-700/30 rounded-lg p-3">
                        <p class="text-sm text-white">{{ $order->admin_notes }}</p>
                    </div>
                </div>
                @endif
                
                <!-- Action Buttons -->
                <div class="space-y-3">
                    <a href="{{ route('order.index') }}" 
                    class="block w-full text-center py-3 border-2 border-slate-500/50 text-slate-300 hover:bg-slate-700/50 hover:border-slate-400 rounded-lg font-medium transition-all">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Daftar Pesanan
                    </a>
                    
                    <!-- Status Update Buttons -->
                    @if($order->status == 'pending_payment')
                    <button onclick="updateStatus({{ $order->id }}, 'paid')" 
                            class="block w-full text-center py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-medium transition-all">
                        <i class="fas fa-check-circle mr-2"></i>
                        Setujui Pembayaran
                    </button>
                    @endif
                    
                    @if($order->status == 'paid')
                    <button onclick="updateStatus({{ $order->id }}, 'processing')" 
                            class="block w-full text-center py-3 bg-purple-600 hover:bg-purple-500 text-white rounded-lg font-medium transition-all">
                        <i class="fas fa-box mr-2"></i>
                        Proses Pesanan
                    </button>
                    @endif
                    
                    @if($order->status == 'processing')
                    <button onclick="updateStatus({{ $order->id }}, 'shipped')" 
                            class="block w-full text-center py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all">
                        <i class="fas fa-truck mr-2"></i>
                        Kirim Pesanan
                    </button>
                    @endif
                    
                    @if($order->status == 'shipped')
                    <button onclick="updateStatus({{ $order->id }}, 'delivered')" 
                            class="block w-full text-center py-3 bg-green-600 hover:bg-green-500 text-white rounded-lg font-medium transition-all">
                        <i class="fas fa-check mr-2"></i>
                        Selesaikan Pesanan
                    </button>
                    @endif
                    
                    @if($order->status == 'cancelled')
                    <div class="block w-full text-center py-3 bg-red-600 text-white rounded-lg font-medium">
                        <i class="fas fa-times-circle mr-2"></i>
                        Pesanan Dibatalkan
                    </div>
                    @endif
                    
                    @if($order->status == 'delivered')
                    <div class="block w-full text-center py-3 bg-emerald-600 text-white rounded-lg font-medium">
                        <i class="fas fa-check-double mr-2"></i>
                        Pesanan Selesai
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Proof Modal -->
<div id="paymentProofModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-slate-800 border border-slate-700 rounded-xl max-w-2xl w-full p-6 relative">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-white">Bukti Pembayaran</h3>
                <button onclick="closePaymentProofModal()" class="text-slate-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="text-center">
                <img id="paymentProofImage" src="" alt="Bukti Pembayaran" 
                     class="max-w-full max-h-[80vh] h-auto rounded-lg shadow-lg mx-auto object-contain">
            </div>
        </div>
    </div>
</div>

<script>
// Update status function
function updateStatus(orderId, newStatus) {
    // Show confirmation
    if (!confirm('Apakah Anda yakin ingin mengubah status pesanan ini?')) {
        return;
    }
    
    // Show loading state
    const button = event.target;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
    
    fetch(`/order/${orderId}/update-status`, {
        method: 'POST',
        body: JSON.stringify({
            status: newStatus,
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }),
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification(data.message || 'Status pesanan berhasil diperbarui', 'success');
            
            // Reload page
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            throw new Error(data.message || 'Gagal memperbarui status');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showNotification('Error: ' + (err.message || 'Terjadi kesalahan server'), 'error');
    })
    .finally(() => {
        // Reset button state (if not reloaded)
        button.disabled = false;
        // Reset button text based on status
        if (newStatus === 'paid') {
            button.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Setujui Pembayaran';
        } else if (newStatus === 'processing') {
            button.innerHTML = '<i class="fas fa-box mr-2"></i>Proses Pesanan';
        } else if (newStatus === 'shipped') {
            button.innerHTML = '<i class="fas fa-truck mr-2"></i>Kirim Pesanan';
        } else if (newStatus === 'delivered') {
            button.innerHTML = '<i class="fas fa-check mr-2"></i>Selesaikan Pesanan';
        }
    });
}

// Payment proof modal
function viewPaymentProof(imageUrl) {
    document.getElementById('paymentProofImage').src = imageUrl;
    document.getElementById('paymentProofModal').classList.remove('hidden');
}

function closePaymentProofModal() {
    document.getElementById('paymentProofModal').classList.add('hidden');
}

// Simple notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-4 py-2 rounded-lg shadow-lg text-white ${
        type === 'success' ? 'bg-green-600' : 
        type === 'error' ? 'bg-red-600' : 'bg-slate-700'
    }`;
    notification.innerHTML = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection