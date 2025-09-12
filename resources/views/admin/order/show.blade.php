@extends('admin.layouts.app')
@section('title', 'Detail Pesanan - Admin SoleStyle')
@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header with Quick Actions -->
    <div class="mb-8 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                Detail Pesanan #{{ $order->order_number }}
            </h1>
            <p class="text-slate-400 mt-2 text-lg">{{ $order->customer_name }} • {{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        
        <!-- Quick Status Update -->
        <div class="flex flex-wrap gap-3">
            @if($order->status == 'pending_payment')
                <button onclick="updateStatus({{ $order->id }}, 'paid')" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-medium transition-all">
                    <i class="fas fa-check-circle mr-2"></i>Setujui Pembayaran
                </button>
            @elseif($order->status == 'paid')
                <button onclick="updateStatus({{ $order->id }}, 'processing')" 
                        class="px-6 py-3 bg-purple-600 hover:bg-purple-500 text-white rounded-lg font-medium transition-all">
                    <i class="fas fa-box mr-2"></i>Proses Pesanan
                </button>
            @elseif($order->status == 'processing')
                <button onclick="updateStatus({{ $order->id }}, 'shipped')" 
                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-all">
                    <i class="fas fa-truck mr-2"></i>Kirim Pesanan
                </button>
            @elseif($order->status == 'shipped')
                <button onclick="updateStatus({{ $order->id }}, 'delivered')" 
                        class="px-6 py-3 bg-green-600 hover:bg-green-500 text-white rounded-lg font-medium transition-all">
                    <i class="fas fa-check mr-2"></i>Selesaikan
                </button>
            @endif
            
            <a href="{{ route('order.index') }}" 
               class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-lg font-medium transition-all">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Status Overview Card -->
    <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="text-slate-400 text-sm">Total Pesanan</div>
                <div class="text-2xl font-bold text-purple-400">{{ $order->formatted_total }}</div>
            </div>
            <div class="text-center">
                <div class="text-slate-400 text-sm">Total Item</div>
                <div class="text-2xl font-bold text-white">{{ $order->orderItems->sum('quantity') }}</div>
            </div>
            <div class="text-center">
                <div class="text-slate-400 text-sm">Metode Pembayaran</div>
                <div class="text-lg font-medium text-white">{{ $order->payment_method_label }}</div>
            </div>
            <div class="text-center">
                <div class="text-slate-400 text-sm ">Status</div>
                <span class="px-4 py-1 mt-2 bg-{{ $order->status_color }}-500/20 text-{{ $order->status_color }}-400 rounded-full text-sm font-medium">
                    {{ $order->status_label }}
                </span>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Order Details -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Customer & Shipping Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Info -->
                <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6">
                    <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-user text-purple-400 mr-3"></i>
                        Info Pembeli
                    </h3>
                    
                    <div class="space-y-3">
                        <div>
                            <span class="text-slate-400 text-sm">Nama</span>
                            <p class="text-white font-medium">{{ $order->customer_name }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400 text-sm">Email</span>
                            <p class="text-white font-medium">{{ $order->customer_email }}</p>
                        </div>
                        @if($order->customer_phone)
                        <div>
                            <span class="text-slate-400 text-sm">Telepon</span>
                            <p class="text-white font-medium">{{ $order->customer_phone }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Shipping Info -->
                <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6">
                    <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-truck text-purple-400 mr-3"></i>
                        Alamat Kirim
                    </h3>
                    
                    <div class="space-y-3">
                        <div>
                            <span class="text-slate-400 text-sm">Alamat</span>
                            <p class="text-white">{{ $order->shipping_address }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            @if($order->city)
                            <div>
                                <span class="text-slate-400">Kota</span>
                                <p class="text-white">{{ $order->city }}</p>
                            </div>
                            @endif
                            @if($order->postal_code)
                            <div>
                                <span class="text-slate-400">Kode Pos</span>
                                <p class="text-white">{{ $order->postal_code }}</p>
                            </div>
                            @endif
                        </div>
                        @if($order->tracking_number)
                        <div class="pt-3 border-t border-slate-700">
                            <span class="text-slate-400 text-sm">Nomor Resi</span>
                            <p class="text-white font-mono">{{ $order->tracking_number }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                    <i class="fas fa-shopping-bag text-purple-400 mr-3"></i>
                    Item Pesanan ({{ $order->orderItems->count() }})
                </h3>
                
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                    <div class="flex items-center gap-4 p-4 bg-slate-700/30 rounded-lg">
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
                            <h4 class="font-semibold text-white text-lg">{{ $item->product_name }}</h4>
                            <div class="flex items-center gap-4 mt-2">
                                @if($item->size_display !== 'N/A')
                                    <span class="bg-slate-600 px-3 py-1 rounded text-sm text-white">{{ $item->size_display }}</span>
                                @endif
                                <span class="text-slate-400">Qty: {{ $item->quantity }}</span>
                                <span class="text-purple-400 font-medium">{{ $item->formatted_price }}</span>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <p class="font-bold text-white text-xl">{{ $item->formatted_subtotal }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Timeline (Simplified) -->
            <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                    <i class="fas fa-clock text-purple-400 mr-3"></i>
                    Status Timeline
                </h3>
                
                <div class="space-y-4">
                    @foreach($timeline as $event)
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0
                            {{ $event['status'] === 'completed' ? 'bg-green-500/20 text-green-400' : 
                            ($event['status'] === 'current' ? 'bg-purple-500/20 text-purple-400' : 'bg-slate-700/50 text-slate-500') }}">
                            <i class="{{ $event['icon'] }} text-lg"></i>
                        </div>
                        
                        <div class="flex-1">
                            <h4 class="font-medium text-white text-lg">{{ $event['title'] }}</h4>
                            <p class="text-slate-400">{{ $event['description'] }}</p>
                            @if($event['date'])
                            <p class="text-slate-500 text-sm mt-1">
                                {{ $event['date']->format('d M Y, H:i') }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Right Column: Summary & Payment -->
        <div class="lg:col-span-1">
            <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6 sticky top-8">
                <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                    <i class="fas fa-calculator text-purple-400 mr-3"></i>
                    Ringkasan
                </h3>
                
                <!-- Price Breakdown -->
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between text-lg">
                        <span class="text-slate-300">Subtotal</span>
                        <span class="text-white font-medium">{{ $order->formatted_subtotal }}</span>
                    </div>
                    
                    @if($order->promo_discount > 0)
                    <div class="flex justify-between text-lg">
                        <span class="text-slate-300">Diskon</span>
                        <span class="text-green-400 font-medium">-{{ $order->formatted_promo_discount }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between text-lg">
                        <span class="text-slate-300">Ongkir</span>
                        <span class="text-white font-medium">{{ $order->formatted_shipping_cost }}</span>
                    </div>
                    
                    <hr class="border-slate-600">
                    
                    <div class="flex justify-between">
                        <span class="font-bold text-white text-xl">Total</span>
                        <span class="font-bold text-2xl text-purple-400">{{ $order->formatted_total }}</span>
                    </div>
                </div>
                
                <!-- Payment Info -->
                @if($order->payment_method !== 'cod')
                <div class="mb-6 p-4 bg-slate-700/30 rounded-lg">
                    <h4 class="font-medium text-white mb-3 flex items-center">
                        <i class="fas fa-credit-card text-purple-400 mr-2"></i>
                        Pembayaran
                    </h4>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Metode:</span>
                            <span class="text-white">{{ $order->payment_method_label }}</span>
                        </div>
                        
                        @if($order->payment_date)
                        <div class="flex justify-between">
                            <span class="text-slate-400">Tanggal:</span>
                            <span class="text-white">{{ $order->payment_date->format('d M Y') }}</span>
                        </div>
                        @endif
                        
                        @if($order->payment && $order->payment->receipt_path)
                        <div class="pt-3">
                            <button onclick="viewPaymentProof('{{ asset('storage/' . $order->payment->receipt_path) }}')" 
                                    class="w-full bg-blue-600 hover:bg-blue-500 text-white py-2 px-4 rounded-lg transition-all">
                                <i class="fas fa-receipt mr-2"></i>Lihat Bukti Bayar
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                
                <!-- Admin Notes -->
                @if($order->admin_notes)
                <div class="mb-6">
                    <h4 class="font-medium text-white mb-3">Catatan Admin</h4>
                    <div class="bg-slate-700/30 rounded-lg p-3">
                        <p class="text-white">{{ $order->admin_notes }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Payment Proof Modal -->
<div id="paymentProofModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-slate-800 border border-slate-700 rounded-xl max-w-3xl w-full p-6 relative max-h-[90vh] overflow-hidden">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-white">Bukti Pembayaran</h3>
                <button onclick="closePaymentProofModal()" class="text-slate-400 hover:text-white text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="text-center overflow-auto max-h-[70vh]">
                <img id="paymentProofImage" src="" alt="Bukti Pembayaran" 
                     class="max-w-full h-auto rounded-lg shadow-lg mx-auto">
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(orderId, newStatus) {
    const statusNames = {
        'paid': 'menyetujui pembayaran',
        'processing': 'memproses pesanan',
        'shipped': 'mengirim pesanan',
        'delivered': 'menyelesaikan pesanan'
    };
    
    if (!confirm(`Apakah Anda yakin ingin ${statusNames[newStatus]}?`)) return;
    
    const button = event.target;
    const originalHTML = button.innerHTML;
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
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Status berhasil diperbarui!', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            throw new Error(data.message || 'Gagal memperbarui status');
        }
    })
    .catch(err => {
        showNotification('Error: ' + err.message, 'error');
        button.disabled = false;
        button.innerHTML = originalHTML;
    });
}

function viewPaymentProof(imageUrl) {
    document.getElementById('paymentProofImage').src = imageUrl;
    document.getElementById('paymentProofModal').classList.remove('hidden');
}

function closePaymentProofModal() {
    document.getElementById('paymentProofModal').classList.add('hidden');
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-xl text-white transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-600' : 'bg-red-600'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-3"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    setTimeout(() => notification.style.transform = 'translateX(0)', 10);
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Close modal on outside click
document.getElementById('paymentProofModal').addEventListener('click', function(e) {
    if (e.target === this) closePaymentProofModal();
});
</script>
@endsection