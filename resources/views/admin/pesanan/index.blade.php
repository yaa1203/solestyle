@extends('admin.layouts.app')

@section('title', 'Daftar Pesanan - SoleStyle Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Daftar Pesanan</h1>
        <p class="text-slate-400 mt-2">Kelola dan pantau semua pesanan</p>
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
            
            <!-- Date From -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-300 mb-2">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2 text-white">
            </div>
            
            <!-- Date To -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-300 mb-2">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2 text-white">
            </div>
            
            <!-- Search -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-300 mb-2">Cari Pesanan</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Nomor pesanan, nama, atau email..."
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
                            
                            <div class="text-sm text-slate-400">
                                {{ $order->customer_name }} ({{ $order->customer_email }})
                            </div>
                            
                            <div class="flex gap-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                   class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                                
                                @if($order->status === 'shipped' && $order->tracking_number)
                                <a href="https://tracking.courier.com/{{ $order->tracking_number }}" target="_blank"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                                    <i class="fas fa-truck mr-1"></i>Lacak
                                </a>
                                @endif
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
                            @if($order->tracking_number)
                                • No. Resi: {{ $order->tracking_number }}
                            @endif
                        </div>
                        
                        <div class="text-right">
                            <p class="text-xl font-bold text-white">{{ $order->formatted_total }}</p>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-2 mt-4">
                        <button onclick="updateOrderStatus({{ $order->id }})" 
                                class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                            <i class="fas fa-edit mr-1"></i>Update Status
                        </button>
                        
                        @if($order->admin_notes)
                        <button onclick="viewAdminNotes({{ $order->id }})" 
                                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                            <i class="fas fa-sticky-note mr-1"></i>Catatan Admin
                        </button>
                        @endif
                        
                        @if($order->status === 'pending_payment' && $order->payment_method === 'bank_transfer')
                        <button onclick="viewPaymentProof({{ $order->id }})" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                            <i class="fas fa-file-image mr-1"></i>Bukti Pembayaran
                        </button>
                        @endif
                        
                        <button onclick="printOrder({{ $order->id }})" 
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                            <i class="fas fa-print mr-1"></i>Cetak
                        </button>
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
            <p class="text-slate-400 mb-6">Belum ada pesanan yang masuk.</p>
        </div>
    @endif
</div>

<!-- Update Status Modal -->
<div id="updateStatusModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-slate-800 border border-slate-700 rounded-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-white">Update Status Pesanan</h3>
                <button onclick="closeUpdateStatusModal()" class="text-slate-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="updateStatusForm">
                <input type="hidden" id="updateStatusOrderId" name="order_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Status</label>
                    <select name="status" id="statusSelect" class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2 text-white">
                        <option value="pending_payment">Menunggu Pembayaran</option>
                        <option value="paid">Dibayar</option>
                        <option value="processing">Dikemas</option>
                        <option value="shipped">Dikirim</option>
                        <option value="delivered">Selesai</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Nomor Resi (untuk status Dikirim)</label>
                    <input type="text" name="tracking_number" id="trackingNumber" 
                           placeholder="Masukkan nomor resi pengiriman"
                           class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2 text-white placeholder-slate-400">
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Catatan Admin</label>
                    <textarea name="admin_notes" rows="3" placeholder="Tambahkan catatan jika diperlukan..."
                              class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 resize-none"></textarea>
                </div>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closeUpdateStatusModal()" 
                            class="flex-1 bg-slate-600 hover:bg-slate-700 text-white py-2 rounded-lg font-medium transition-all">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white py-2 rounded-lg font-medium transition-all">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Admin Notes Modal -->
<div id="adminNotesModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-slate-800 border border-slate-700 rounded-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-white">Catatan Admin</h3>
                <button onclick="closeAdminNotesModal()" class="text-slate-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="adminNotesContent" class="bg-slate-700/50 rounded-lg p-4 mb-6 min-h-[100px]">
                <!-- Notes will be loaded here -->
            </div>
            
            <div class="flex justify-end">
                <button onclick="closeAdminNotesModal()" 
                        class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg font-medium transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Proof Modal -->
<div id="paymentProofModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-slate-800 border border-slate-700 rounded-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-white">Bukti Pembayaran</h3>
                <button onclick="closePaymentProofModal()" class="text-slate-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="paymentProofContent" class="bg-slate-700/50 rounded-lg p-4 mb-6 min-h-[200px] flex items-center justify-center">
                <!-- Payment proof will be loaded here -->
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-300 mb-2">Catatan Pembayaran</label>
                <div id="paymentNotesContent" class="bg-slate-700/50 rounded-lg p-4 min-h-[60px]">
                    <!-- Payment notes will be loaded here -->
                </div>
            </div>
            
            <div class="flex justify-end">
                <button onclick="closePaymentProofModal()" 
                        class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg font-medium transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Update Order Status
function updateOrderStatus(orderId) {
    document.getElementById('updateStatusOrderId').value = orderId;
    document.getElementById('updateStatusModal').classList.remove('hidden');
    
    // Reset form
    document.getElementById('updateStatusForm').reset();
    
    // Show/hide tracking number field based on status
    const statusSelect = document.getElementById('statusSelect');
    const trackingNumberField = document.getElementById('trackingNumber');
    
    statusSelect.addEventListener('change', function() {
        if (this.value === 'shipped') {
            trackingNumberField.style.display = 'block';
        } else {
            trackingNumberField.style.display = 'none';
        }
    });
    
    // Initialize
    if (statusSelect.value === 'shipped') {
        trackingNumberField.style.display = 'block';
    } else {
        trackingNumberField.style.display = 'none';
    }
}
function closeUpdateStatusModal() {
    document.getElementById('updateStatusModal').classList.add('hidden');
}
// Handle Update Status Form Submit
document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const orderId = document.getElementById('updateStatusOrderId').value;
    
    fetch(`/admin/orders/${orderId}/update-status`, {
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
            closeUpdateStatusModal();
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
// View Admin Notes
function viewAdminNotes(orderId) {
    fetch(`/admin/orders/${orderId}/admin-notes`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('adminNotesContent').innerHTML = data.notes || '<p class="text-slate-400">Tidak ada catatan</p>';
            document.getElementById('adminNotesModal').classList.remove('hidden');
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat memuat catatan', 'error');
    });
}
function closeAdminNotesModal() {
    document.getElementById('adminNotesModal').classList.add('hidden');
}
// View Payment Proof
function viewPaymentProof(orderId) {
    fetch(`/admin/orders/${orderId}/payment-proof`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const content = document.getElementById('paymentProofContent');
            if (data.image_url) {
                content.innerHTML = `<img src="${data.image_url}" alt="Bukti Pembayaran" class="max-w-full h-auto rounded-lg">`;
            } else {
                content.innerHTML = '<p class="text-slate-400">Tidak ada bukti pembayaran</p>';
            }
            
            const notesContent = document.getElementById('paymentNotesContent');
            notesContent.innerHTML = data.payment_notes || '<p class="text-slate-400">Tidak ada catatan</p>';
            
            document.getElementById('paymentProofModal').classList.remove('hidden');
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat memuat bukti pembayaran', 'error');
    });
}
function closePaymentProofModal() {
    document.getElementById('paymentProofModal').classList.add('hidden');
}
// Print Order
function printOrder(orderId) {
    window.open(`/admin/orders/${orderId}/print`, '_blank');
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