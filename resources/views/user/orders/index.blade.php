@extends('user.layouts.app')
@section('title', 'Pesanan Saya - SoleStyle')
@section('content')
<div class="container mx-auto px-4 py-6">
    
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white mb-2">Pesanan Saya</h1>
        <p class="text-slate-400">Kelola dan pantau pesanan Anda</p>
    </div>
    
    <!-- Tab Status -->
    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-1 mb-6">
        <div class="flex overflow-x-auto">
            <a href="?status=all" class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap {{ request('status', 'all') === 'all' ? 'bg-purple-600 text-white' : 'text-slate-400 hover:text-white' }}">
                Semua ({{ $orderStats['total'] }})
            </a>
            <a href="?status=pending_payment" class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap {{ request('status') === 'pending_payment' ? 'bg-purple-600 text-white' : 'text-slate-400 hover:text-white' }}">
                Belum Bayar ({{ $orderStats['pending_payment'] }})
            </a>
            <a href="?status=processing" class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap {{ request('status') === 'processing' ? 'bg-purple-600 text-white' : 'text-slate-400 hover:text-white' }}">
                Dikemas ({{ $orderStats['processing'] }})
            </a>
            <a href="?status=shipped" class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap {{ request('status') === 'shipped' ? 'bg-purple-600 text-white' : 'text-slate-400 hover:text-white' }}">
                Dikirim ({{ $orderStats['shipped'] }})
            </a>
            <a href="?status=delivered" class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap {{ request('status') === 'delivered' ? 'bg-purple-600 text-white' : 'text-slate-400 hover:text-white' }}">
                Selesai ({{ $orderStats['delivered'] }})
            </a>
        </div>
    </div>
    
    <!-- Search -->
    <div class="mb-6">
        <form method="GET" class="max-w-md">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari pesanan..."
                       class="w-full bg-slate-700/50 border border-slate-600 rounded-lg pl-10 pr-4 py-2 text-white placeholder-slate-400">
                <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                <button type="submit" class="absolute right-2 top-1 bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-sm">
                    Cari
                </button>
            </div>
        </form>
    </div>
    
    <!-- Orders List -->
    @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $order)
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl overflow-hidden">
                <!-- Order Header -->
                <div class="p-4 border-b border-slate-700/50">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-white font-medium">#{{ $order->order_number }}</span>
                                <span class="px-2 py-1 bg-{{ $order->status_color }}-500/20 text-{{ $order->status_color }}-400 rounded text-xs">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-400">{{ $order->order_date->format('d M Y, H:i') }}</p>
                        </div>
                        <a href="{{ route('orders.show', $order->id) }}" 
                           class="text-purple-400 hover:text-purple-300 text-sm">
                            Detail <i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Order Items Preview -->
                <div class="p-4">
                    @foreach($order->orderItems->take(1) as $item)
                    <div class="flex gap-3">
                        <div class="w-12 h-12 bg-slate-700 rounded-lg overflow-hidden flex-shrink-0">
                            @if($item->image_url)
                                <img src="{{ $item->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-image text-slate-500 text-xs"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-white text-sm font-medium truncate">{{ $item->product_name }}</h4>
                            <p class="text-xs text-slate-400">
                                @if($item->size_display !== 'N/A') Size {{ $item->size_display }} • @endif
                                x{{ $item->quantity }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-white text-sm font-medium">{{ $item->formatted_subtotal }}</p>
                        </div>
                    </div>
                    @endforeach
                    
                    @if($order->orderItems->count() > 1)
                    <p class="text-xs text-slate-400 mt-2">+{{ $order->orderItems->count() - 1 }} produk lainnya</p>
                    @endif
                </div>
                
                <!-- Order Footer -->
                <div class="px-4 py-3 bg-slate-700/20 border-t border-slate-700/50 flex justify-between items-center">
                    <div class="text-xs text-slate-400">
                        {{ $order->orderItems->count() }} produk • {{ $order->payment_method_label }}
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <p class="text-xs text-slate-400">Total Belanja</p>
                            <p class="text-white font-bold">{{ $order->formatted_total }}</p>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="flex gap-2">
                            @if($order->status === 'pending_payment')
                                <button onclick="uploadPaymentProof({{ $order->id }})" 
                                        class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1 rounded text-xs">
                                    Bayar
                                </button>
                            @endif
                            
                            @if($order->status === 'shipped')
                                <button onclick="confirmDelivery({{ $order->id }})" 
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs">
                                    Terima
                                </button>
                            @endif
                            
                            @if($order->canBeCancelled())
                                <button onclick="cancelOrder({{ $order->id }})" 
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">
                                    Batal
                                </button>
                            @endif
                            
                            <button onclick="reorder({{ $order->id }})" 
                                    class="bg-slate-600 hover:bg-slate-700 text-white px-3 py-1 rounded text-xs">
                                <i class="fas fa-redo text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->withQueryString()->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-8 text-center">
            <div class="w-16 h-16 bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shopping-bag text-2xl text-slate-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Belum Ada Pesanan</h3>
            <p class="text-slate-400 mb-4">Mulai berbelanja sekarang!</p>
            <a href="{{ route('products.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium">
                <i class="fas fa-shopping-bag mr-2"></i>Mulai Belanja
            </a>
        </div>
    @endif
</div>
<!-- Payment Modal -->
<div id="paymentProofModal" class="fixed inset-0 bg-black/50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-slate-800 border border-slate-700 rounded-xl max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Upload Bukti Pembayaran</h3>
            
            <form id="paymentProofForm" enctype="multipart/form-data">
                <input type="hidden" id="paymentOrderId" name="order_id">
                
                <div class="mb-4">
                    <label class="block text-sm text-slate-300 mb-2">Bukti Pembayaran</label>
                    <input type="file" name="payment_proof" accept="image/*" required
                           class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm">
                </div>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closePaymentProofModal()" 
                            class="flex-1 bg-slate-600 hover:bg-slate-700 text-white py-2 rounded-lg text-sm">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-lg text-sm">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function cancelOrder(orderId) {
    if (confirm('Batalkan pesanan ini?')) {
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
    if (confirm('Konfirmasi pesanan diterima?')) {
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
function uploadPaymentProof(orderId) {
    document.getElementById('paymentOrderId').value = orderId;
    document.getElementById('paymentProofModal').classList.remove('hidden');
}
function closePaymentProofModal() {
    document.getElementById('paymentProofModal').classList.add('hidden');
    document.getElementById('paymentProofForm').reset();
}
document.getElementById('paymentProofForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const orderId = document.getElementById('paymentOrderId').value;
    
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
    toast.className = `fixed top-4 right-4 bg-gradient-to-r ${colors[type]} text-white px-4 py-2 rounded-lg z-50`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    setTimeout(() => document.body.removeChild(toast), 3000);
}
</script>
@endsection