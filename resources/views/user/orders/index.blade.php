@extends('user.layouts.app')
@section('title', 'Pesanan Saya - SoleStyle')
@section('content')
<div class="container mx-auto px-4 py-8">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
            Pesanan Saya
        </h1>
        <p class="text-slate-400 mt-2">Kelola dan pantau status pesanan Anda</p>
    </div>
    
    <!-- Order Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-white">{{ $orderStats['total'] }}</div>
            <div class="text-sm text-slate-400">Total Pesanan</div>
        </div>
        
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-yellow-400">{{ $orderStats['pending_payment'] }}</div>
            <div class="text-sm text-slate-400">Menunggu Bayar</div>
        </div>
        
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-purple-400">{{ $orderStats['processing'] }}</div>
            <div class="text-sm text-slate-400">Dikemas</div>
        </div>
        
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-blue-400">{{ $orderStats['shipped'] }}</div>
            <div class="text-sm text-slate-400">Dikirim</div>
        </div>
        
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-green-400">{{ $orderStats['delivered'] }}</div>
            <div class="text-sm text-slate-400">Selesai</div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6 mb-8">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <!-- Status Filter -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-300 mb-2">Status</label>
                <select name="status" class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2 text-white">
                    <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending_payment" {{ request('status') === 'pending_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Dibayar</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Dikemas</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Dikirim</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            
            <!-- Search -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-300 mb-2">Cari Pesanan</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Nomor pesanan..."
                       class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2 text-white placeholder-slate-400">
            </div>
            
            <!-- Submit -->
            <div class="flex items-end">
                <button type="submit" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-2 rounded-lg font-medium transition-all">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>
    
    <!-- Orders List -->
    @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl shadow-xl overflow-hidden">
                <!-- Order Header -->
                <div class="p-6 border-b border-slate-700">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-white">
                                #{{ $order->order_number }}
                            </h3>
                            <p class="text-sm text-slate-400">
                                {{ $order->order_date->format('d M Y, H:i') }}
                            </p>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 bg-{{ $order->status_color }}-500/20 text-{{ $order->status_color }}-400 rounded-full text-sm font-medium">
                                {{ $order->status_label }}
                            </span>
                            
                            <div class="flex gap-2">
                                <a href="{{ route('orders.show', $order->id) }}" 
                                   class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                                
                                <a href="{{ route('order.track', $order->order_number) }}" 
                                   class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                                    <i class="fas fa-truck mr-1"></i>Lacak
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Order Items -->
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($order->orderItems->take(2) as $item)
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
                        
                        @if($order->orderItems->count() > 2)
                        <div class="text-center py-2">
                            <span class="text-slate-400 text-sm">
                                +{{ $order->orderItems->count() - 2 }} item lainnya
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Order Footer -->
                <div class="px-6 py-4 bg-slate-700/30 border-t border-slate-700">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-slate-400">
                            {{ $order->orderItems->count() }} item • {{ $order->payment_method_label }}
                        </div>
                        
                        <div class="text-right">
                            <p class="text-xl font-bold text-white">{{ $order->formatted_total }}</p>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-2 mt-4">
                        @if($order->canBeCancelled())
                        <button onclick="cancelOrder({{ $order->id }})" 
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                            <i class="fas fa-times mr-1"></i>Batalkan
                        </button>
                        @endif
                        
                        @if($order->status === 'shipped')
                        <button onclick="confirmDelivery({{ $order->id }})" 
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                            <i class="fas fa-check mr-1"></i>Terima
                        </button>
                        @endif
                        
                        @if(in_array($order->status, ['paid', 'processing', 'shipped', 'delivered']))
                        <a href="{{ route('orders.invoice', $order->id) }}" target="_blank"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                            <i class="fas fa-file-invoice mr-1"></i>Invoice
                        </a>
                        @endif
                        
                        <button onclick="reorder({{ $order->id }})" 
                                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                            <i class="fas fa-redo mr-1"></i>Pesan Lagi
                        </button>
                        
                        @if($order->status === 'pending_payment' && $order->payment_method === 'bank_transfer')
                        <button onclick="uploadPaymentProof({{ $order->id }})" 
                                class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                            <i class="fas fa-upload mr-1"></i>Upload Bukti
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $orders->withQueryString()->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-12 text-center">
            <div class="w-32 h-32 bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-shopping-bag text-4xl text-slate-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">Belum Ada Pesanan</h3>
            <p class="text-slate-400 mb-6">Anda belum memiliki pesanan. Mari mulai berbelanja!</p>
            <a href="{{ route('products.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-lg font-medium transition-all">
                <i class="fas fa-shopping-bag mr-2"></i>
                Mulai Belanja
            </a>
        </div>
    @endif
</div>

<!-- Payment Proof Upload Modal -->
<div id="paymentProofModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-slate-800 border border-slate-700 rounded-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-white">Upload Bukti Pembayaran</h3>
                <button onclick="closePaymentProofModal()" class="text-slate-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="paymentProofForm" enctype="multipart/form-data">
                <input type="hidden" id="paymentOrderId" name="order_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Bukti Pembayaran *</label>
                    <input type="file" name="payment_proof" accept="image/*" required
                           class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-white file:mr-4 file:py-1 file:px-4 file:rounded file:border-0 file:text-sm file:bg-purple-600 file:text-white hover:file:bg-purple-700">
                    <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG. Maksimal 2MB</p>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Catatan (Opsional)</label>
                    <textarea name="payment_notes" rows="3" placeholder="Tambahkan catatan jika diperlukan..."
                              class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 resize-none"></textarea>
                </div>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closePaymentProofModal()" 
                            class="flex-1 bg-slate-600 hover:bg-slate-700 text-white py-2 rounded-lg font-medium transition-all">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white py-2 rounded-lg font-medium transition-all">
                        <span id="paymentSubmitText">Upload</span>
                    </button>
                </div>
            </form>
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

// Reorder
function reorder(orderId) {
    if (confirm('Tambahkan semua item dari pesanan ini ke keranjang?')) {
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
                    setTimeout(() => {
                        window.location.href = '/cart';
                    }, 2000);
                }
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat menambahkan ke keranjang', 'error');
        });
    }
}

// Upload Payment Proof
function uploadPaymentProof(orderId) {
    document.getElementById('paymentOrderId').value = orderId;
    document.getElementById('paymentProofModal').classList.remove('hidden');
}

function closePaymentProofModal() {
    document.getElementById('paymentProofModal').classList.add('hidden');
    document.getElementById('paymentProofForm').reset();
}

// Handle Payment Proof Form Submit
document.getElementById('paymentProofForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const orderId = document.getElementById('paymentOrderId').value;
    const submitButton = document.getElementById('paymentSubmitText');
    
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Uploading...';
    
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
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat mengupload bukti pembayaran', 'error');
    })
    .finally(() => {
        submitButton.innerHTML = 'Upload';
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