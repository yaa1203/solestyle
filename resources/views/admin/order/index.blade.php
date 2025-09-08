<!-- resources/views/admin/order/index.blade.php -->
@extends('admin.layouts.app')

@section('title', 'Kelola Pesanan - Admin SoleStyle')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
            Kelola Pesanan
        </h1>
        <p class="text-slate-400 mt-2">Kelola dan pantau semua pesanan dari customer</p>
    </div>
    
    <!-- Order Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-8">
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-white">{{ $orderStats['total'] }}</div>
            <div class="text-sm text-slate-400">Total Pesanan</div>
        </div>
        
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-yellow-400">{{ $orderStats['pending_payment'] }}</div>
            <div class="text-sm text-slate-400">Menunggu Bayar</div>
        </div>
        
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-blue-400">{{ $orderStats['paid'] }}</div>
            <div class="text-sm text-slate-400">Dibayar</div>
        </div>
        
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-purple-400">{{ $orderStats['processing'] }}</div>
            <div class="text-sm text-slate-400">Dikemas</div>
        </div>
        
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-indigo-400">{{ $orderStats['shipped'] }}</div>
            <div class="text-sm text-slate-400">Dikirim</div>
        </div>
        
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-green-400">{{ $orderStats['delivered'] }}</div>
            <div class="text-sm text-slate-400">Selesai</div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6 mb-8">
        <form method="GET" class="flex flex-col lg:flex-row gap-4">
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
                       placeholder="Nomor pesanan, nama, email..."
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
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-white">
                                #{{ $order->order_number }}
                            </h3>
                            <p class="text-sm text-slate-400">
                                {{ $order->order_date->format('d M Y, H:i') }}
                            </p>
                            <div class="mt-2">
                                <p class="text-sm text-white">
                                    <i class="fas fa-user mr-1"></i>
                                    {{ $order->customer_name }}
                                </p>
                                <p class="text-sm text-slate-400">
                                    <i class="fas fa-envelope mr-1"></i>
                                    {{ $order->customer_email }}
                                </p>
                                @if($order->customer_phone)
                                <p class="text-sm text-slate-400">
                                    <i class="fas fa-phone mr-1"></i>
                                    {{ $order->customer_phone }}
                                </p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                            <div class="text-center lg:text-right">
                                <p class="text-xl font-bold text-white">{{ $order->formatted_total }}</p>
                                <p class="text-sm text-slate-400">{{ $order->payment_method_label }}</p>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <!-- Status Badge -->
                                <span class="px-3 py-1 bg-{{ $order->status_color }}-500/20 text-{{ $order->status_color }}-400 rounded-full text-sm font-medium">
                                    {{ $order->status_label }}
                                </span>
                                
                                <!-- Action Dropdown -->
                                <div class="relative">
                                    <button onclick="toggleDropdown({{ $order->id }})" 
                                            class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    
                                    <!-- resources/views/admin/order/index.blade.php -->
                                    <!-- Di dalam dropdown action -->
                                    <div id="dropdown-{{ $order->id }}" class="absolute right-0 mt-2 w-48 bg-slate-700 border border-slate-600 rounded-lg shadow-xl z-10 hidden">
                                        <div class="py-1">
                                            <a href="{{ route('order.show', $order->id) }}" 
                                            class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-slate-600">
                                                <i class="fas fa-eye mr-2"></i>Lihat Detail
                                            </a>
                                            <button onclick="updateStatus({{ $order->id }})" 
                                                    class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-slate-600">
                                                <i class="fas fa-edit mr-2"></i>Update Status
                                            </button>
                                            @if($order->payment_proof || ($order->payment && $order->payment->receipt_path))
                                            <button onclick="viewPaymentProof({{ $order->id }})" 
                                                    class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-slate-600">
                                                <i class="fas fa-image mr-2"></i>Lihat Bukti Bayar
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
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
                                    Qty: {{ $item->quantity }} • {{ $item->formatted_price }}
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
                
                <!-- Shipping Info -->
                <div class="px-6 py-4 bg-slate-700/30 border-t border-slate-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h5 class="text-sm font-medium text-slate-300 mb-2">Alamat Pengiriman</h5>
                            <p class="text-sm text-white">{{ $order->shipping_address }}</p>
                            @if($order->tracking_number)
                            <p class="text-sm text-slate-400 mt-1">
                                <i class="fas fa-truck mr-1"></i>
                                Resi: {{ $order->tracking_number }}
                            </p>
                            @endif
                        </div>
                        
                        <div>
                            <h5 class="text-sm font-medium text-slate-300 mb-2">Informasi Tambahan</h5>
                            <p class="text-sm text-slate-400">
                                {{ $order->orderItems->count() }} item • 
                                {{ $order->payment_method_label }}
                            </p>
                            @if($order->admin_notes)
                            <p class="text-sm text-yellow-400 mt-1">
                                <i class="fas fa-sticky-note mr-1"></i>
                                {{ $order->admin_notes }}
                            </p>
                            @endif
                        </div>
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
            <p class="text-slate-400">Belum ada pesanan yang masuk.</p>
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
                <input type="hidden" id="updateOrderId" name="order_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Status Baru *</label>
                    <select name="status" id="newStatus" required
                            class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2 text-white">
                        <option value="">Pilih Status</option>
                        <option value="pending_payment">Menunggu Pembayaran</option>
                        <option value="paid">Dibayar</option>
                        <option value="processing">Dikemas</option>
                        <option value="shipped">Dikirim</option>
                        <option value="delivered">Selesai</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                </div>
                
                <div class="mb-4" id="trackingNumberDiv" style="display: none;">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Nomor Resi</label>
                    <input type="text" name="tracking_number" id="trackingNumber"
                           class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2 text-white placeholder-slate-400"
                           placeholder="Masukkan nomor resi pengiriman">
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Catatan Admin</label>
                    <textarea name="admin_notes" rows="3" placeholder="Catatan tambahan..."
                              class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 resize-none"></textarea>
                </div>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closeUpdateStatusModal()" 
                            class="flex-1 bg-slate-600 hover:bg-slate-700 text-white py-2 rounded-lg font-medium transition-all">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white py-2 rounded-lg font-medium transition-all">
                        <span id="updateSubmitText">Update</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Payment Proof Modal -->
<div id="paymentProofModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-slate-800 border border-slate-700 rounded-xl max-w-2xl w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-white">Bukti Pembayaran</h3>
                <button onclick="closePaymentProofModal()" class="text-slate-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="text-center">
                <img id="paymentProofImage" src="" alt="Bukti Pembayaran" 
                     class="max-w-full h-auto rounded-lg shadow-lg mx-auto">
            </div>
        </div>
    </div>
</div>

<script>
// Toggle dropdown
function toggleDropdown(orderId) {
    const dropdown = document.getElementById(`dropdown-${orderId}`);
    const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
    
    // Close all other dropdowns
    allDropdowns.forEach(d => {
        if (d.id !== `dropdown-${orderId}`) {
            d.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('[onclick^="toggleDropdown"]')) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
            d.classList.add('hidden');
        });
    }
});

// Update status modal
function updateStatus(orderId) {
    document.getElementById('updateOrderId').value = orderId;
    document.getElementById('updateStatusModal').classList.remove('hidden');
    
    // Reset form
    document.getElementById('updateStatusForm').reset();
    document.getElementById('trackingNumberDiv').style.display = 'none';
}

function closeUpdateStatusModal() {
    document.getElementById('updateStatusModal').classList.add('hidden');
}

// Show/hide tracking number field based on status
document.getElementById('newStatus').addEventListener('change', function() {
    const trackingDiv = document.getElementById('trackingNumberDiv');
    if (this.value === 'shipped') {
        trackingDiv.style.display = 'block';
    } else {
        trackingDiv.style.display = 'none';
    }
});

// Handle update status form
document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const orderId = document.getElementById('updateOrderId').value;
    const submitButton = document.getElementById('updateSubmitText');
    
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
    
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
        showNotification('Gagal mengupdate status pesanan', 'error');
    })
    .finally(() => {
        submitButton.innerHTML = 'Update';
    });
});

// Improved JavaScript for Payment Proof functionality

// View payment proof with better error handling
function viewPaymentProof(orderId) {
    console.log('Viewing payment proof for order ID:', orderId);
    
    // Show loading state
    showNotification('Memuat bukti pembayaran...', 'info');
    
    fetch(`/admin/orders/${orderId}/payment-proof`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', [...response.headers.entries()]);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Payment proof response:', data);
        
        if (data.success) {
            // Set the image source
            const imgElement = document.getElementById('paymentProofImage');
            
            // Handle image load error
            imgElement.onerror = function() {
                console.error('Failed to load image:', data.image_url);
                showNotification('Gagal memuat gambar bukti pembayaran', 'error');
                closePaymentProofModal();
            };
            
            // Handle image load success
            imgElement.onload = function() {
                console.log('Image loaded successfully');
            };
            
            imgElement.src = data.image_url;
            imgElement.alt = `Bukti Pembayaran - ${data.order_number}`;
            
            // Show the modal
            document.getElementById('paymentProofModal').classList.remove('hidden');
            
            // Add additional info if available
            if (data.source) {
                console.log('Payment proof source:', data.source);
            }
            
        } else {
            console.error('Payment proof error:', data.message);
            showNotification(data.message || 'Gagal memuat bukti pembayaran', 'error');
            
            // Show debug info if available
            if (data.debug_info) {
                console.error('Debug info:', data.debug_info);
            }
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showNotification('Terjadi kesalahan saat memuat bukti pembayaran', 'error');
    });
}

// Debug function to check payment proof
function debugPaymentProof(orderId) {
    console.log('Debugging payment proof for order ID:', orderId);
    
    fetch(`/admin/orders/${orderId}/debug-payment-proof`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Debug payment proof data:', data);
        
        // You can display this info in console or create a debug modal
        alert('Debug info logged to console. Check browser developer tools.');
    })
    .catch(error => {
        console.error('Debug error:', error);
    });
}

// Close payment proof modal
function closePaymentProofModal() {
    const modal = document.getElementById('paymentProofModal');
    const imgElement = document.getElementById('paymentProofImage');
    
    // Clear the image source to stop loading
    imgElement.src = '';
    imgElement.alt = '';
    
    // Hide the modal
    modal.classList.add('hidden');
}

// Enhanced notification function with better styling
function showNotification(message, type = 'info') {
    // Remove any existing notifications
    const existingNotifications = document.querySelectorAll('.notification-toast');
    existingNotifications.forEach(notification => notification.remove());
    
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
    toast.className = `notification-toast fixed top-4 right-4 bg-gradient-to-r ${colors[type]} text-white px-6 py-4 rounded-xl shadow-lg z-50 transform translate-x-full transition-transform duration-300 max-w-md`;
    toast.innerHTML = `
        <div class="flex items-center space-x-3">
            <i class="fas ${icons[type]} flex-shrink-0"></i>
            <span class="break-words">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => toast.classList.remove('translate-x-full'), 100);
    
    // Auto-remove after 5 seconds (unless it's an error, keep it longer)
    const autoRemoveTime = type === 'error' ? 8000 : 5000;
    setTimeout(() => {
        if (toast.parentElement) {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 300);
        }
    }, autoRemoveTime);
}

// Improved modal handling
document.addEventListener('DOMContentLoaded', function() {
    // Close modal when clicking outside
    document.getElementById('paymentProofModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closePaymentProofModal();
        }
    });
    
    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('paymentProofModal');
            if (modal && !modal.classList.contains('hidden')) {
                closePaymentProofModal();
            }
        }
    });
});

// Test function to check if the payment proof feature is working
function testPaymentProofFeature() {
    console.log('Testing payment proof feature...');
    
    // Check if required elements exist
    const modal = document.getElementById('paymentProofModal');
    const img = document.getElementById('paymentProofImage');
    
    if (!modal) {
        console.error('Payment proof modal not found');
        return false;
    }
    
    if (!img) {
        console.error('Payment proof image element not found');
        return false;
    }
    
    console.log('Payment proof elements found successfully');
    return true;
}
</script>
@endsection