@extends('user.layouts.app')
@section('title', 'Detail Pesanan - SoleStyle')
@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('orders.index') }}" class="text-purple-400 hover:text-purple-300 text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Pesanan
        </a>
    </div>
    
    <!-- Order Header -->
    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-xl font-bold text-white mb-1">Pesanan #{{ $order->order_number }}</h1>
                <p class="text-slate-400 text-sm">{{ $order->order_date->format('d M Y, H:i') }}</p>
                <span class="inline-block mt-2 px-3 py-1 bg-{{ $order->status_color }}-500/20 text-{{ $order->status_color }}-400 rounded-full text-sm">
                    {{ $order->status_label }}
                </span>
            </div>
            <div class="text-right">
                <p class="text-slate-400 text-sm">Total Pesanan</p>
                <p class="text-2xl font-bold text-white">{{ $order->formatted_total }}</p>
            </div>
        </div>
    </div>
    
    <!-- Order Progress -->
    @if($order->status !== 'cancelled')
    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6 mb-6">
        <h3 class="text-white font-semibold mb-4">Status Pesanan</h3>
        <div class="flex items-center justify-between">
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 rounded-full {{ in_array($order->status, ['paid', 'processing', 'shipped', 'delivered']) ? 'bg-green-500' : 'bg-slate-600' }} flex items-center justify-center mb-2">
                    <i class="fas fa-credit-card text-white text-sm"></i>
                </div>
                <span class="text-xs text-slate-400">Dibayar</span>
            </div>
            <div class="flex-1 h-1 mx-2 {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'bg-green-500' : 'bg-slate-600' }}"></div>
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 rounded-full {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'bg-green-500' : 'bg-slate-600' }} flex items-center justify-center mb-2">
                    <i class="fas fa-box text-white text-sm"></i>
                </div>
                <span class="text-xs text-slate-400">Dikemas</span>
            </div>
            <div class="flex-1 h-1 mx-2 {{ in_array($order->status, ['shipped', 'delivered']) ? 'bg-green-500' : 'bg-slate-600' }}"></div>
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 rounded-full {{ in_array($order->status, ['shipped', 'delivered']) ? 'bg-green-500' : 'bg-slate-600' }} flex items-center justify-center mb-2">
                    <i class="fas fa-truck text-white text-sm"></i>
                </div>
                <span class="text-xs text-slate-400">Dikirim</span>
            </div>
            <div class="flex-1 h-1 mx-2 {{ $order->status === 'delivered' ? 'bg-green-500' : 'bg-slate-600' }}"></div>
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 rounded-full {{ $order->status === 'delivered' ? 'bg-green-500' : 'bg-slate-600' }} flex items-center justify-center mb-2">
                    <i class="fas fa-check text-white text-sm"></i>
                </div>
                <span class="text-xs text-slate-400">Selesai</span>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Order Items -->
    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6 mb-6">
        <h3 class="text-white font-semibold mb-4">Produk Dipesan</h3>
        <div class="space-y-4">
            @foreach($order->orderItems as $item)
            <div class="flex items-center gap-4 pb-4 {{ !$loop->last ? 'border-b border-slate-700/50' : '' }}">
                <div class="w-16 h-16 bg-slate-700 rounded-lg overflow-hidden flex-shrink-0">
                    @if($item->image_url)
                        <img src="{{ $item->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-image text-slate-500"></i>
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <h4 class="text-white font-medium mb-1">{{ $item->product_name }}</h4>
                    <p class="text-sm text-slate-400">
                        @if($item->size_display !== 'N/A')
                            Size: {{ $item->size_display }}
                        @endif
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-white font-medium">{{ $item->formatted_price }}</p>
                    <p class="text-sm text-slate-400">x{{ $item->quantity }}</p>
                    <p class="text-white font-bold mt-1">{{ $item->formatted_subtotal }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Order Summary -->
    <div class="grid md:grid-cols-2 gap-6 mb-6">
        <!-- Payment Info -->
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6">
            <h3 class="text-white font-semibold mb-4">Info Pembayaran</h3>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Metode Pembayaran</span>
                    <span class="text-white">{{ $order->payment_method_label }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Subtotal</span>
                    <span class="text-white">{{ $order->formatted_subtotal }}</span>
                </div>
                @if($order->shipping_cost > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Ongkos Kirim</span>
                    <span class="text-white">{{ $order->formatted_shipping }}</span>
                </div>
                @endif
                <div class="border-t border-slate-700 pt-2 mt-2">
                    <div class="flex justify-between font-bold">
                        <span class="text-white">Total</span>
                        <span class="text-white">{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Shipping Info -->
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6">
            <h3 class="text-white font-semibold mb-4">Info Pengiriman</h3>
            <div class="space-y-2">
                <div class="text-sm">
                    <p class="text-slate-400 mb-1">Alamat</p>
                    <p class="text-white">{{ $order->shipping_address }} <br> {{ $order->city }}, {{ $order->province }} {{ $order->postal_code }}</p>
                </div>
                @if($order->tracking_number)
                <div class="text-sm">
                    <p class="text-slate-400 mb-1">No. Resi</p>
                    <p class="text-white font-mono">{{ $order->tracking_number }}</p>
                </div>
                @endif
                @if($order->estimated_delivery)
                <div class="text-sm">
                    <p class="text-slate-400 mb-1">Estimasi Tiba</p>
                    <p class="text-white">{{ $order->estimated_delivery->format('d M Y') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-3 justify-center">
        @if($order->status === 'pending_payment')
            <button onclick="uploadPaymentProof({{ $order->id }})" 
                    class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg font-medium">
                <i class="fas fa-upload mr-2"></i>Upload Bukti Bayar
            </button>
        @endif
        
        @if($order->status === 'shipped')
            <button onclick="confirmDelivery({{ $order->id }})" 
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium">
                <i class="fas fa-check mr-2"></i>Pesanan Diterima
            </button>
        @endif
        
        @if($order->canBeCancelled())
            <button onclick="cancelOrder({{ $order->id }})" 
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium">
                <i class="fas fa-times mr-2"></i>Batalkan Pesanan
            </button>
        @endif
        
        @if($order->tracking_number)
            <a href="{{ route('order.track', $order->order_number) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                <i class="fas fa-truck mr-2"></i>Lacak Paket
            </a>
        @endif
        
        @if(in_array($order->status, ['paid', 'processing', 'shipped', 'delivered']))
            <a href="{{ route('orders.invoice', $order->id) }}" target="_blank"
               class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-2 rounded-lg font-medium">
                <i class="fas fa-file-invoice mr-2"></i>Invoice
            </a>
        @endif
        
        <button onclick="reorder({{ $order->id }})" 
                class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-medium">
            <i class="fas fa-redo mr-2"></i>Pesan Lagi
        </button>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentProofModal" class="fixed inset-0 bg-black/50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-slate-800 border border-slate-700 rounded-xl max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Upload Bukti Pembayaran</h3>
            
            <form id="paymentProofForm" enctype="multipart/form-data">
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                
                <div class="mb-4">
                    <label class="block text-sm text-slate-300 mb-2">Bukti Pembayaran</label>
                    <input type="file" name="payment_proof" accept="image/*" required
                           class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm">
                    <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG. Max 2MB</p>
                </div>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closePaymentProofModal()" 
                            class="flex-1 bg-slate-600 hover:bg-slate-700 text-white py-2 rounded-lg">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-orange-600 hover:bg-orange-700 text-white py-2 rounded-lg">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function cancelOrder(orderId) {
    if (confirm('Yakin ingin membatalkan pesanan ini?')) {
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
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message, 'error');
            }
        });
    }
}

function confirmDelivery(orderId) {
    if (confirm('Konfirmasi bahwa pesanan telah diterima?')) {
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
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message, 'error');
            }
        });
    }
}

function reorder(orderId) {
    if (confirm('Tambahkan semua item ke keranjang?')) {
        fetch(`/orders/${orderId}/reorder`, {
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
                if (data.added_items > 0) {
                    setTimeout(() => window.location.href = '/cart', 1500);
                }
            } else {
                showNotification(data.message, 'error');
            }
        });
    }
}

function uploadPaymentProof(orderId) {
    document.getElementById('paymentProofModal').classList.remove('hidden');
}

function closePaymentProofModal() {
    document.getElementById('paymentProofModal').classList.add('hidden');
    document.getElementById('paymentProofForm').reset();
}

document.getElementById('paymentProofForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const orderId = formData.get('order_id');
    
    fetch(`/orders/${orderId}/payment-proof`, {
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
            closePaymentProofModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message, 'error');
        }
    });
});

function showNotification(message, type = 'info') {
    const colors = {
        success: 'from-green-500 to-emerald-500',
        error: 'from-red-500 to-pink-500'
    };
    
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 bg-gradient-to-r ${colors[type]} text-white px-4 py-2 rounded-lg shadow-lg z-50`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    setTimeout(() => document.body.removeChild(toast), 3000);
}
</script>
@endsection