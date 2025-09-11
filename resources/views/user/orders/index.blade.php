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
            <a href="?status=all" class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-all {{ request('status', 'all') === 'all' ? 'bg-purple-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-700/50' }}">
                Semua ({{ $orderStats['total'] }})
            </a>
            <a href="?status=pending_payment" class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-all {{ request('status') === 'pending_payment' ? 'bg-purple-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-700/50' }}">
                Belum Bayar ({{ $orderStats['pending_payment'] }})
            </a>
            <a href="?status=processing" class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-all {{ request('status') === 'processing' ? 'bg-purple-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-700/50' }}">
                Dikemas ({{ $orderStats['processing'] }})
            </a>
            <a href="?status=shipped" class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-all {{ request('status') === 'shipped' ? 'bg-purple-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-700/50' }}">
                Dikirim ({{ $orderStats['shipped'] }})
            </a>
            <a href="?status=delivered" class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-all {{ request('status') === 'delivered' ? 'bg-purple-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-700/50' }}">
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
                       class="w-full bg-slate-700/50 border border-slate-600 rounded-lg pl-10 pr-20 py-3 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-1.5 rounded text-sm transition-colors">
                    Cari
                </button>
                <!-- Hidden inputs untuk maintain filter -->
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
            </div>
        </form>
    </div>
    
    <!-- Orders List -->
    @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl overflow-hidden hover:border-slate-600 transition-all">
                <!-- Order Header -->
                <div class="p-4 border-b border-slate-700/50 bg-gradient-to-r from-slate-800/80 to-slate-700/80">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <span class="text-white font-semibold text-lg">#{{ $order->order_number }}</span>
                                    @php
                                        $statusConfig = [
                                            'pending_payment' => ['bg' => 'bg-orange-500/20', 'text' => 'text-orange-400', 'icon' => 'fas fa-clock'],
                                            'processing' => ['bg' => 'bg-blue-500/20', 'text' => 'text-blue-400', 'icon' => 'fas fa-box'],
                                            'shipped' => ['bg' => 'bg-yellow-500/20', 'text' => 'text-yellow-400', 'icon' => 'fas fa-truck'],
                                            'delivered' => ['bg' => 'bg-green-500/20', 'text' => 'text-green-400', 'icon' => 'fas fa-check-circle'],
                                            'cancelled' => ['bg' => 'bg-red-500/20', 'text' => 'text-red-400', 'icon' => 'fas fa-times-circle']
                                        ];
                                        $config = $statusConfig[$order->status] ?? $statusConfig['processing'];
                                    @endphp
                                    <span class="px-3 py-1.5 {{ $config['bg'] }} {{ $config['text'] }} rounded-full text-sm font-medium flex items-center gap-2">
                                        <i class="{{ $config['icon'] }} text-xs"></i>
                                        {{ $order->status_label }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-4 text-sm text-slate-400">
                                    <span><i class="fas fa-calendar mr-1"></i>{{ $order->order_date->format('d M Y, H:i') }}</span>
                                    <span><i class="fas fa-credit-card mr-1"></i>{{ $order->payment_method_label }}</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('orders.show', $order->id) }}" 
                           class="text-purple-400 hover:text-purple-300 text-sm font-medium flex items-center gap-2 px-3 py-2 hover:bg-slate-700/50 rounded-lg transition-all">
                            Detail <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Order Items -->
                <div class="p-4">
                    <div class="space-y-3">
                        @foreach($order->orderItems->take(2) as $item)
                        <div class="flex gap-4">
                            <div class="w-16 h-16 bg-slate-700 rounded-lg overflow-hidden flex-shrink-0">
                                @if($item->image_url)
                                    <img src="{{ $item->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-image text-slate-500"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-white font-medium mb-1 line-clamp-2">{{ $item->product_name }}</h4>
                                <div class="flex items-center gap-3 text-sm text-slate-400">
                                    @if($item->size_display !== 'N/A')
                                        <span>Size {{ $item->size_display }}</span>
                                    @endif
                                    <span>Qty: {{ $item->quantity }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-white font-semibold">{{ $item->formatted_subtotal }}</p>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($order->orderItems->count() > 2)
                        <div class="text-center py-2">
                            <span class="text-sm text-slate-400 bg-slate-700/30 px-3 py-1 rounded-full">
                                +{{ $order->orderItems->count() - 2 }} produk lainnya
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Order Footer -->
                <div class="px-4 py-4 bg-slate-700/20 border-t border-slate-700/50">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-slate-400">
                            <span>{{ $order->orderItems->count() }} produk</span>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <p class="text-sm text-slate-400">Total Belanja</p>
                                <p class="text-xl font-bold text-white">{{ $order->formatted_total }}</p>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex items-center gap-2">
                                @if($order->status === 'pending_payment')
                                    <button onclick="uploadPaymentProof({{ $order->id }})" 
                                            class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-colors">
                                        <i class="fas fa-upload"></i>Bayar
                                    </button>
                                @endif
                                
                                @if($order->status === 'shipped')
                                    <button onclick="confirmDelivery({{ $order->id }})" 
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-colors">
                                        <i class="fas fa-check"></i>Terima
                                    </button>
                                @endif
                                
                                @if($order->canBeCancelled())
                                    <button onclick="cancelOrder({{ $order->id }})" 
                                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-colors">
                                        <i class="fas fa-times"></i>Batal
                                    </button>
                                @endif
                                
                                <!-- Review Button - Diperbaiki logikanya -->
                                @if($order->status === 'delivered')
                                    @if(!isset($order->has_review) || !$order->has_review)
                                        <a href="{{ route('review.create', $order->id) }}"
                                           class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-colors">
                                            <i class="fas fa-star"></i>Ulas
                                        </a>
                                    @else
                                        <span class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                                            <i class="fas fa-check"></i>Diulas
                                        </span>
                                    @endif
                                @endif
                                
                                <!-- Always show reorder button -->
                                <button onclick="reorder({{ $order->id }})" 
                                        class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-colors"
                                        title="Beli Lagi">
                                    <i class="fas fa-redo"></i>Beli Lagi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            <div class="flex justify-center">
                {{ $orders->withQueryString()->links() }}
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-12 text-center">
            <div class="w-24 h-24 bg-gradient-to-br from-purple-600/20 to-purple-700/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-shopping-bag text-4xl text-purple-400"></i>
            </div>
            <h3 class="text-2xl font-semibold text-white mb-3">
                @if(request('search'))
                    Pesanan Tidak Ditemukan
                @else
                    Belum Ada Pesanan
                @endif
            </h3>
            <p class="text-slate-400 mb-6 text-lg">
                @if(request('search'))
                    Tidak ada pesanan yang sesuai dengan pencarian "{{ request('search') }}"
                @else
                    Mulai berbelanja sekarang dan temukan produk favorit Anda!
                @endif
            </p>
            @if(request('search'))
                <div class="flex gap-3 justify-center">
                    <a href="{{ request()->url() }}" 
                       class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white rounded-lg font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Lihat Semua Pesanan
                    </a>
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition-colors">
                        <i class="fas fa-shopping-bag mr-2"></i>Mulai Belanja
                    </a>
                </div>
            @else
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white rounded-lg font-medium text-lg transition-all transform hover:scale-105">
                    <i class="fas fa-shopping-bag mr-3"></i>Mulai Belanja
                </a>
            @endif
        </div>
    @endif
</div>

<!-- Payment Modal -->
<div id="paymentProofModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-slate-800 border border-slate-700 rounded-xl max-w-md w-full p-6 transform transition-all">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-white">Upload Bukti Pembayaran</h3>
                <button onclick="closePaymentProofModal()" class="text-slate-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="paymentProofForm" enctype="multipart/form-data">
                <input type="hidden" id="paymentOrderId" name="order_id">
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-300 mb-3">Bukti Pembayaran</label>
                    <div class="relative">
                        <input type="file" name="payment_proof" accept="image/*" required
                               class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-purple-600 file:text-white file:cursor-pointer hover:file:bg-purple-700 transition-all">
                    </div>
                    <p class="text-xs text-slate-400 mt-2">Format: JPG, JPEG, PNG. Maksimal 2MB</p>
                </div>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closePaymentProofModal()" 
                            class="flex-1 bg-slate-600 hover:bg-slate-700 text-white py-3 rounded-lg font-medium transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-lg font-medium transition-colors">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 hidden">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 text-center">
            <div class="animate-spin w-8 h-8 border-4 border-purple-600 border-t-transparent rounded-full mx-auto mb-4"></div>
            <p class="text-white">Memproses...</p>
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
// Show loading overlay
function showLoading() {
    document.getElementById('loadingOverlay').classList.remove('hidden');
}

// Hide loading overlay
function hideLoading() {
    document.getElementById('loadingOverlay').classList.add('hidden');
}

function cancelOrder(orderId) {
    if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
        showLoading();
        fetch(`/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification(data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat membatalkan pesanan', 'error');
        });
    }
}

function confirmDelivery(orderId) {
    if (confirm('Konfirmasi bahwa Anda telah menerima pesanan ini?')) {
        showLoading();
        fetch(`/orders/${orderId}/confirm-delivery`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification(data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat konfirmasi penerimaan', 'error');
        });
    }
}

function reorder(orderId) {
    showLoading();
    fetch(`/orders/${orderId}/reorder`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification(data.message, 'success');
            if (data.added_items > 0) {
                setTimeout(() => window.location.href = '/cart', 1500);
            }
        } else {
            showNotification(data.message || 'Terjadi kesalahan', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat menambah ke keranjang', 'error');
    });
}

function uploadPaymentProof(orderId) {
    document.getElementById('paymentOrderId').value = orderId;
    document.getElementById('paymentProofModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Prevent background scroll
}

function closePaymentProofModal() {
    document.getElementById('paymentProofModal').classList.add('hidden');
    document.getElementById('paymentProofForm').reset();
    document.body.style.overflow = ''; // Restore scroll
}

// Close modal when clicking outside
document.getElementById('paymentProofModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePaymentProofModal();
    }
});

document.getElementById('paymentProofForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const orderId = document.getElementById('paymentOrderId').value;
    
    // Validate file size
    const fileInput = this.querySelector('input[type="file"]');
    if (fileInput.files[0] && fileInput.files[0].size > 2 * 1024 * 1024) {
        showNotification('Ukuran file terlalu besar. Maksimal 2MB.', 'error');
        return;
    }
    
    showLoading();
    fetch(`/orders/${orderId}/payment-proof`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification(data.message, 'success');
            closePaymentProofModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.message || 'Terjadi kesalahan saat upload', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat upload bukti pembayaran', 'error');
    });
});

function showNotification(message, type = 'info') {
    const colors = {
        success: 'from-green-500 to-emerald-500',
        error: 'from-red-500 to-pink-500',
        info: 'from-blue-500 to-cyan-500'
    };
    
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        info: 'fas fa-info-circle'
    };
    
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 bg-gradient-to-r ${colors[type]} text-white px-6 py-4 rounded-lg z-50 flex items-center gap-3 shadow-lg transform transition-all duration-300 translate-x-full`;
    toast.innerHTML = `
        <i class="${icons[type]}"></i>
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" class="ml-2 text-white/80 hover:text-white">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => toast.classList.remove('translate-x-full'), 100);
    
    // Auto remove
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            if (toast.parentElement) {
                document.body.removeChild(toast);
            }
        }, 300);
    }, 5000);
}

// Escape key to close modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePaymentProofModal();
    }
});
</script>
@endsection