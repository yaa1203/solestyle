@extends('user.layouts.app')
@section('title', 'Lacak Pesanan - SoleStyle')
@section('content')
<div class="container mx-auto px-4 py-8">
    
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('orders.index') }}" 
               class="bg-slate-700 hover:bg-slate-600 text-white p-2 rounded-lg transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                    Lacak Pesanan
                </h1>
                <p class="text-slate-400 mt-1">{{ $order->order_number }}</p>
            </div>
        </div>
    </div>
    
    <!-- Order Status Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Order Info -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Current Status -->
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6">
                <div class="text-center">
                    <div class="w-24 h-24 bg-{{ $order->status_color }}-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas 
                            @if($order->status === 'pending_payment') fa-clock
                            @elseif($order->status === 'paid') fa-check-circle
                            @elseif($order->status === 'processing') fa-box
                            @elseif($order->status === 'shipped') fa-truck
                            @elseif($order->status === 'delivered') fa-home
                            @elseif($order->status === 'cancelled') fa-times-circle
                            @else fa-info-circle
                            @endif
                            text-3xl text-{{ $order->status_color }}-400"></i>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-white mb-2">{{ $order->status_label }}</h2>
                    
                    @if($order->status === 'pending_payment')
                        <p class="text-slate-300 mb-4">
                            @if($order->payment_method === 'cod')
                                Pesanan Anda akan segera diproses dan dikirim
                            @else
                                Lakukan pembayaran sesuai instruksi yang diberikan
                            @endif
                        </p>
                    @elseif($order->status === 'paid')
                        <p class="text-slate-300 mb-4">Pembayaran telah diterima, pesanan sedang dipersiapkan</p>
                    @elseif($order->status === 'processing')
                        <p class="text-slate-300 mb-4">Pesanan sedang dikemas dan akan segera dikirim</p>
                    @elseif($order->status === 'shipped')
                        <p class="text-slate-300 mb-4">
                            Pesanan dalam perjalanan ke alamat tujuan
                            @if($order->tracking_number)
                                <br><span class="text-purple-400 font-mono">{{ $order->tracking_number }}</span>
                            @endif
                        </p>
                    @elseif($order->status === 'delivered')
                        <p class="text-slate-300 mb-4">
                            Pesanan telah sampai di tujuan
                            @if($order->delivered_date)
                                pada {{ $order->delivered_date->format('d M Y, H:i') }}
                            @endif
                        </p>
                    @elseif($order->status === 'cancelled')
                        <p class="text-slate-300 mb-4">Pesanan telah dibatalkan</p>
                    @endif
                    
                    <!-- Estimated Delivery for Active Orders -->
                    @if(in_array($order->status, ['paid', 'processing', 'shipped']))
                        <div class="bg-blue-900/20 border border-blue-500/30 rounded-lg p-4 mb-4">
                            <div class="flex items-center justify-center text-blue-200">
                                <i class="fas fa-truck text-blue-400 mr-2"></i>
                                <span class="text-sm">
                                    Estimasi tiba: {{ $order->shipping_estimate }}
                                </span>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        @if($order->status === 'shipped')
                        <button onclick="confirmDelivery({{ $order->id }})" 
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-all">
                            <i class="fas fa-check mr-2"></i>Konfirmasi Terima
                        </button>
                        @endif
                        
                        @if($order->canBeCancelled())
                        <button onclick="cancelOrder({{ $order->id }})" 
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition-all">
                            <i class="fas fa-times mr-2"></i>Batalkan Pesanan
                        </button>
                        @endif
                        
                        <a href="tel:+6281234567890" 
                           class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-medium transition-all">
                            <i class="fas fa-phone mr-2"></i>Hubungi Kurir
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Tracking Timeline -->
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-white mb-6">Riwayat Pelacakan</h3>
                
                <div class="relative">
                    <!-- Timeline line -->
                    <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-slate-600"></div>
                    
                    @foreach($timeline as $index => $step)
                    <div class="relative flex items-start mb-8 last:mb-0">
                        <!-- Timeline dot -->
                        <div class="w-12 h-12 rounded-full flex items-center justify-center z-10
                            @if($step['status'] === 'completed')
                                bg-gradient-to-r from-green-500 to-emerald-500
                            @elseif($step['status'] === 'current')
                                bg-gradient-to-r from-purple-500 to-pink-500 animate-pulse
                            @else
                                bg-slate-600
                            @endif">
                            <i class="{{ $step['icon'] }} text-white
                                @if($step['status'] === 'completed')
                                @elseif($step['status'] === 'current')
                                @else
                                    text-slate-400
                                @endif"></i>
                        </div>
                        
                        <!-- Timeline content -->
                        <div class="ml-6 flex-1">
                            <h4 class="font-semibold 
                                @if($step['status'] === 'completed' || $step['status'] === 'current')
                                    text-white
                                @else
                                    text-slate-300
                                @endif">
                                {{ $step['title'] }}
                            </h4>
                            
                            @if($step['date'])
                                <p class="text-sm text-slate-400 mt-1">
                                    {{ $step['date']->format('d M Y, H:i') }}
                                </p>
                            @endif
                            
                            <p class="text-sm 
                                @if($step['status'] === 'completed')
                                    text-green-400
                                @elseif($step['status'] === 'current')
                                    text-purple-400
                                @else
                                    text-slate-500
                                @endif mt-1">
                                @if($step['status'] === 'completed')
                                    ✓ {{ $step['description'] }}
                                @elseif($step['status'] === 'current')
                                    🔄 {{ $step['description'] }}
                                @else
                                    ⏳ {{ $step['description'] }}
                                @endif
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Right Column: Order Details -->
        <div class="space-y-6">
            
            <!-- Order Summary -->
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Detail Pesanan</h3>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Nomor:</span>
                        <span class="text-white font-mono">#{{ $order->order_number }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-slate-400">Tanggal:</span>
                        <span class="text-white">{{ $order->order_date->format('d M Y') }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-slate-400">Pembayaran:</span>
                        <span class="text-white">{{ $order->payment_method_label }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-slate-400">Total:</span>
                        <span class="text-white font-bold">{{ $order->formatted_total }}</span>
                    </div>
                    
                    @if($order->tracking_number)
                    <div class="flex justify-between">
                        <span class="text-slate-400">Resi:</span>
                        <span class="text-purple-400 font-mono">{{ $order->tracking_number }}</span>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Shipping Address -->
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-map-marker-alt text-purple-400 mr-2"></i>
                    Alamat Pengiriman
                </h3>
                
                <div class="text-sm space-y-2">
                    <div class="font-medium text-white">{{ $order->customer_name }}</div>
                    <div class="text-slate-300">{{ $order->customer_phone }}</div>
                    <div class="text-slate-300 leading-relaxed">{{ $order->shipping_address }}</div>
                    @if($order->city || $order->province)
                    <div class="text-slate-400">
                        @if($order->city){{ $order->city }}@endif
                        @if($order->city && $order->province), @endif
                        @if($order->province){{ $order->province }}@endif
                        @if($order->postal_code) {{ $order->postal_code }}@endif
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">
                    Item Pesanan ({{ $order->orderItems->count() }})
                </h3>
                
                <div class="space-y-4 max-h-64 overflow-y-auto">
                    @foreach($order->orderItems as $item)
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 flex-shrink-0 rounded-lg overflow-hidden">
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
                            <h4 class="font-medium text-white text-sm truncate">{{ $item->product_name }}</h4>
                            <div class="text-xs text-slate-400 space-y-1">
                                @if($item->size_display !== 'N/A')
                                    <div>Size: {{ $item->size_display }}</div>
                                @endif
                                <div>{{ $item->quantity }}x {{ $item->formatted_price }}</div>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <p class="font-medium text-white text-sm">{{ $item->formatted_subtotal }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Contact Support -->
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Butuh Bantuan?</h3>
                
                <div class="space-y-3">
                    <a href="https://wa.me/6281234567890?text=Halo,%20saya%20butuh%20bantuan%20terkait%20pesanan%20{{ $order->order_number }}" 
                       target="_blank"
                       class="flex items-center justify-center w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-medium transition-all">
                        <i class="fab fa-whatsapp mr-2"></i>
                        WhatsApp
                    </a>
                    
                    <a href="mailto:support@solestyle.com?subject=Bantuan Pesanan {{ $order->order_number }}" 
                       class="flex items-center justify-center w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-medium transition-all">
                        <i class="fas fa-envelope mr-2"></i>
                        Email
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Cancel Order
function cancelOrder(orderId) {
    if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
        fetch(`/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
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
            showNotification('Terjadi kesalahan saat membatalkan pesanan', 'error');
        });
    }
}

// Confirm Delivery
function confirmDelivery(orderId) {
    if (confirm('Konfirmasi bahwa Anda telah menerima pesanan ini?')) {
        fetch(`/orders/${orderId}/confirm-delivery`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
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
            showNotification('Terjadi kesalahan saat mengkonfirmasi penerimaan', 'error');
        });
    }
}

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

// Auto refresh untuk status yang sedang dalam proses
@if(in_array($order->status, ['paid', 'processing', 'shipped']))
setInterval(function() {
    // Refresh halaman setiap 2 menit untuk update status
    location.reload();
}, 120000);
@endif
</script>
@endsection