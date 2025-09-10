@extends('user.layouts.app')
@section('title', 'Pesanan Berhasil - SoleStyle')
@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto text-center">
        
        <!-- Success Icon -->
        <div class="w-32 h-32 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-8 animate-pulse">
            <i class="fas fa-check text-6xl text-white"></i>
        </div>
        
        <!-- Success Message -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-4">Pesanan Berhasil!</h1>
            <p class="text-xl text-slate-300 mb-2">Terima kasih telah berbelanja di SoleStyle</p>
            <p class="text-slate-400">Pesanan Anda sedang diproses dan akan segera dikirimkan</p>
        </div>
        
        <!-- Order Details Card -->
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl shadow-xl p-8 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                
                <!-- Order Info -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-receipt text-purple-400 mr-2"></i>
                        Detail Pesanan
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Nomor Pesanan:</span>
                            <span class="text-white font-mono">#{{ $order->order_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Tanggal:</span>
                            <span class="text-white">{{ $order->order_date->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Status:</span>
                            <span class="bg-{{ $order->status_color }}-500/20 text-{{ $order->status_color }}-400 px-2 py-1 rounded text-sm">
                                <i class="fas fa-clock mr-1"></i>{{ $order->status_label }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Metode Pembayaran:</span>
                            <span class="text-white">{{ $order->payment_method_label }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Shipping Info -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-truck text-purple-400 mr-2"></i>
                        Pengiriman
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Estimasi:</span>
                            <span class="text-white">{{ $order->shipping_estimate }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Ongkos Kirim:</span>
                            <span class="text-green-400">{{ $order->formatted_shipping_cost }} @if($order->shipping_cost == 0)<small>(Gratis)</small>@endif</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Kurir:</span>
                            <span class="text-white">{{ $order->courier_name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Items Summary -->
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl shadow-xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-shopping-bag text-purple-400 mr-2"></i>
                Item Pesanan ({{ $order->orderItems->count() }})
            </h3>
            
            <div class="space-y-4 max-h-64 overflow-y-auto">
                @foreach($order->orderItems as $item)
                <div class="flex items-center gap-3 p-3 bg-slate-700/30 rounded-lg">
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
                        @if($item->size_display !== 'N/A')
                            <p class="text-xs text-slate-400">Size: {{ $item->size_display }}</p>
                        @endif
                        <p class="text-xs text-slate-400">Qty: {{ $item->quantity }}</p>
                    </div>
                    
                    <div class="text-right">
                        <p class="font-medium text-white text-sm">{{ $item->formatted_subtotal }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Price Summary -->
            <div class="border-t border-slate-600 mt-6 pt-4 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-300">Subtotal</span>
                    <span class="text-white">{{ $order->formatted_subtotal }}</span>
                </div>
                @if($order->promo_discount > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-slate-300">Diskon @if($order->promo_code)({{ $order->promo_code }})@endif</span>
                    <span class="text-green-400">-{{ $order->formatted_promo_discount }}</span>
                </div>
                @endif
                <div class="flex justify-between text-sm">
                    <span class="text-slate-300">Ongkos Kirim</span>
                    <span class="text-green-400">{{ $order->formatted_shipping_cost }} @if($order->shipping_cost == 0)<small>(Gratis)</small>@endif</span>
                </div>
                <hr class="border-slate-600">
                <div class="flex justify-between">
                    <span class="font-bold text-white text-lg">Total</span>
                    <span class="font-bold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">{{ $order->formatted_total }}</span>
                </div>
            </div>
        </div>
        
        <!-- Payment Instructions -->
        @if($order->payment_method === 'bank_transfer' && $order->status === 'pending_payment')
        <div class="bg-blue-900/20 border border-blue-500/30 rounded-xl p-6 mb-8 text-left">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                Instruksi Pembayaran
            </h3>
            
            <div class="space-y-4">
                <div class="bg-slate-800/50 rounded-lg p-4">
                    <h4 class="font-semibold text-white mb-2 flex items-center">
                        <i class="fas fa-university text-blue-400 mr-2"></i>
                        Transfer ke Rekening Bank
                    </h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Bank BCA:</span>
                            <span class="text-white font-mono cursor-pointer" onclick="copyToClipboard('1234567890')" title="Klik untuk copy">1234567890</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Bank Mandiri:</span>
                            <span class="text-white font-mono cursor-pointer" onclick="copyToClipboard('0987654321')" title="Klik untuk copy">0987654321</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Atas Nama:</span>
                            <span class="text-white">SoleStyle Indonesia</span>
                        </div>
                        <div class="flex justify-between border-t border-slate-600 pt-2 mt-2">
                            <span class="text-slate-400 font-semibold">Total Transfer:</span>
                            <span class="text-purple-400 font-bold text-lg cursor-pointer" onclick="copyToClipboard('{{ $order->total }}')" title="Klik untuk copy">{{ $order->formatted_total }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-yellow-900/20 border border-yellow-500/30 rounded-lg p-4">
                    <p class="text-yellow-200 text-sm flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-400 mr-2 mt-0.5 flex-shrink-0"></i>
                        <span>
                            <strong>Penting:</strong> Harap transfer sesuai dengan nominal yang tertera. 
                            Konfirmasi pembayaran akan dikirim melalui email setelah transfer berhasil.
                        </span>
                    </p>
                </div>
            </div>
        </div>
        @endif
        
        <!-- COD Information -->
        @if($order->payment_method === 'cod')
        <div class="bg-green-900/20 border border-green-500/30 rounded-xl p-6 mb-8 text-left">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-money-bill-wave text-green-400 mr-2"></i>
                Cash on Delivery (COD)
            </h3>
            
            <div class="space-y-4">
                <div class="bg-slate-800/50 rounded-lg p-4">
                    <p class="text-white mb-2">
                        <i class="fas fa-info-circle text-green-400 mr-2"></i>
                        Pembayaran dilakukan saat barang diterima
                    </p>
                    <div class="text-sm text-slate-300 space-y-1">
                        <p>• Siapkan uang pas sejumlah {{ $order->formatted_total }}</p>
                        <p>• Periksa kondisi barang sebelum melakukan pembayaran</p>
                        <p>• Pembayaran hanya dalam bentuk tunai</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Credit Card Information -->
        @if($order->payment_method === 'credit_card')
        <div class="bg-purple-900/20 border border-purple-500/30 rounded-xl p-6 mb-8 text-left">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-credit-card text-purple-400 mr-2"></i>
                Pembayaran Kartu Kredit/Debit
            </h3>
            
            <div class="bg-slate-800/50 rounded-lg p-4">
                <p class="text-white mb-2">
                    <i class="fas fa-check-circle text-green-400 mr-2"></i>
                    Pembayaran telah berhasil diproses
                </p>
                <p class="text-sm text-slate-300">
                    Email konfirmasi pembayaran telah dikirim ke {{ $order->customer_email }}
                </p>
            </div>
        </div>
        @endif
        
        <!-- Action Buttons -->
        <div class="space-y-4 sm:space-y-0 sm:flex sm:gap-4 sm:justify-center">
            <a href="{{ route('order.track', $order->order_number) }}" 
               class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-lg font-semibold transition-all transform hover:scale-105">
                <i class="fas fa-search mr-2"></i>
                Lacak Pesanan
            </a>
            
            <a href="{{ route('produk.index') }}" 
               class="inline-flex items-center justify-center px-8 py-4 border-2 border-purple-500/50 text-purple-400 hover:bg-purple-500/10 hover:border-purple-400 rounded-lg font-semibold transition-all">
                <i class="fas fa-shopping-bag mr-2"></i>
                Lanjut Belanja
            </a>
            
            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center justify-center px-8 py-4 border-2 border-slate-500/50 text-slate-300 hover:bg-slate-700/50 hover:border-slate-400 rounded-lg font-semibold transition-all">
                <i class="fas fa-home mr-2"></i>
                Ke Beranda
            </a>
        </div>
        
        <!-- Order Timeline -->
        <div class="mt-12 text-left">
            <h3 class="text-lg font-semibold text-white mb-6 text-center">Proses Selanjutnya</h3>
            
            <div class="relative">
                <!-- Timeline line -->
                <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-slate-600"></div>
                
                <!-- Step 1 - Order Created (Always completed) -->
                <div class="relative flex items-center mb-8">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center z-10">
                        <i class="fas fa-check text-white"></i>
                    </div>
                    <div class="ml-6">
                        <h4 class="font-semibold text-white">Pesanan Dibuat</h4>
                        <p class="text-slate-400 text-sm">{{ $order->order_date->format('d M Y, H:i') }}</p>
                        <p class="text-green-400 text-sm">✓ Selesai</p>
                    </div>
                </div>
                
                <!-- Step 2 - Payment -->
                <div class="relative flex items-center mb-8">
                    @if(in_array($order->status, ['pending_payment']))
                        <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center z-10 animate-pulse">
                            <i class="fas fa-credit-card text-white"></i>
                        </div>
                        <div class="ml-6">
                            <h4 class="font-semibold text-white">
                                @if($order->payment_method === 'cod')
                                    Menunggu Pengiriman
                                @else
                                    Menunggu Pembayaran
                                @endif
                            </h4>
                            <p class="text-slate-400 text-sm">
                                @if($order->payment_method === 'cod')
                                    Pesanan akan dikirim segera
                                @else
                                    Lakukan pembayaran dalam 24 jam
                                @endif
                            </p>
                            <p class="text-yellow-400 text-sm">⏳ Dalam Proses</p>
                        </div>
                    @else
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center z-10">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div class="ml-6">
                            <h4 class="font-semibold text-white">
                                @if($order->payment_method === 'cod')
                                    Siap Dikirim
                                @else
                                    Pembayaran Diterima
                                @endif
                            </h4>
                            <p class="text-slate-400 text-sm">
                                @if($order->payment_date)
                                    {{ $order->payment_date->format('d M Y, H:i') }}
                                @else
                                    {{ $order->order_date->format('d M Y, H:i') }}
                                @endif
                            </p>
                            <p class="text-green-400 text-sm">✓ Selesai</p>
                        </div>
                    @endif
                </div>
                
                <!-- Step 3 - Processing -->
                <div class="relative flex items-center mb-8">
                    @if($order->status === 'processing')
                        <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center z-10 animate-pulse">
                            <i class="fas fa-box text-white"></i>
                        </div>
                        <div class="ml-6">
                            <h4 class="font-semibold text-white">Persiapan Pengiriman</h4>
                            <p class="text-slate-400 text-sm">Pesanan sedang dikemas</p>
                            <p class="text-purple-400 text-sm">📦 Dalam Proses</p>
                        </div>
                    @elseif(in_array($order->status, ['shipped', 'delivered']))
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center z-10">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div class="ml-6">
                            <h4 class="font-semibold text-white">Persiapan Pengiriman</h4>
                            <p class="text-slate-400 text-sm">Pesanan telah dikemas</p>
                            <p class="text-green-400 text-sm">✓ Selesai</p>
                        </div>
                    @else
                        <div class="w-12 h-12 bg-slate-600 rounded-full flex items-center justify-center z-10">
                            <i class="fas fa-box text-slate-400"></i>
                        </div>
                        <div class="ml-6">
                            <h4 class="font-semibold text-slate-300">Persiapan Pengiriman</h4>
                            <p class="text-slate-400 text-sm">
                                @if($order->payment_method === 'cod')
                                    Siap untuk dikemas
                                @else
                                    Setelah pembayaran dikonfirmasi
                                @endif
                            </p>
                            <p class="text-slate-500 text-sm">⌛ Menunggu</p>
                        </div>
                    @endif
                </div>
                
                <!-- Step 4 - Shipped -->
                <div class="relative flex items-center mb-8">
                    @if($order->status === 'shipped')
                        <div class="w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center z-10 animate-pulse">
                            <i class="fas fa-truck text-white"></i>
                        </div>
                        <div class="ml-6">
                            <h4 class="font-semibold text-white">Pengiriman</h4>
                            <p class="text-slate-400 text-sm">
                                @if($order->shipped_date)
                                    Dikirim pada {{ $order->shipped_date->format('d M Y, H:i') }}
                                @else
                                    Pesanan dalam perjalanan
                                @endif
                            </p>
                            <p class="text-indigo-400 text-sm">🚚 Dalam Perjalanan</p>
                        </div>
                    @elseif($order->status === 'delivered')
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center z-10">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div class="ml-6">
                            <h4 class="font-semibold text-white">Pengiriman</h4>
                            <p class="text-slate-400 text-sm">
                                @if($order->shipped_date)
                                    Dikirim pada {{ $order->shipped_date->format('d M Y, H:i') }}
                                @else
                                    Pesanan telah dikirim
                                @endif
                            </p>
                            <p class="text-green-400 text-sm">✓ Selesai</p>
                        </div>
                    @else
                        <div class="w-12 h-12 bg-slate-600 rounded-full flex items-center justify-center z-10">
                            <i class="fas fa-truck text-slate-400"></i>
                        </div>
                        <div class="ml-6">
                            <h4 class="font-semibold text-slate-300">Pengiriman</h4>
                            <p class="text-slate-400 text-sm">Pesanan dalam perjalanan</p>
                            <p class="text-slate-500 text-sm">⌛ Menunggu</p>
                        </div>
                    @endif
                </div>
                
                <!-- Step 5 - Delivered -->
                <div class="relative flex items-center">
                    @if($order->status === 'delivered')
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center z-10">
                            <i class="fas fa-home text-white"></i>
                        </div>
                        <div class="ml-6">
                            <h4 class="font-semibold text-white">Pesanan Diterima</h4>
                            <p class="text-slate-400 text-sm">
                                @if($order->delivered_date)
                                    Diterima pada {{ $order->delivered_date->format('d M Y, H:i') }}
                                @else
                                    Barang telah sampai di tujuan
                                @endif
                            </p>
                            <p class="text-green-400 text-sm">✓ Selesai</p>
                        </div>
                    @else
                        <div class="w-12 h-12 bg-slate-600 rounded-full flex items-center justify-center z-10">
                            <i class="fas fa-home text-slate-400"></i>
                        </div>
                        <div class="ml-6">
                            <h4 class="font-semibold text-slate-300">Pesanan Diterima</h4>
                            <p class="text-slate-400 text-sm">Barang sampai di tujuan</p>
                            <p class="text-slate-500 text-sm">⌛ Menunggu</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Email Reminder -->
        <div class="mt-12 p-4 bg-purple-900/20 border border-purple-500/30 rounded-lg">
            <div class="flex items-center justify-center text-purple-200">
                <i class="fas fa-envelope text-purple-400 mr-2"></i>
                <span class="text-sm">
                    Email konfirmasi telah dikirim ke {{ $order->customer_email }}
                </span>
            </div>
        </div>
        
        @if($order->order_notes)
        <!-- Order Notes -->
        <div class="mt-8 p-4 bg-slate-800/30 border border-slate-700 rounded-lg">
            <h4 class="text-sm font-semibold text-white mb-2">Catatan Pesanan:</h4>
            <p class="text-slate-300 text-sm">{{ $order->order_notes }}</p>
        </div>
        @endif
        
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add some celebration animation
    setTimeout(() => {
        // Create confetti effect (optional)
        if (typeof confetti !== 'undefined') {
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 }
            });
        }
    }, 500);
});

// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        showNotification('Berhasil dicopy: ' + text, 'success');
    }).catch(function() {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showNotification('Berhasil dicopy: ' + text, 'success');
    });
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
</script>
@endsection