@extends('admin.layouts.app')
@section('title', 'Kelola Pesanan - SoleStyle')
@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Kelola Pesanan</h1>
            <p class="text-slate-400 text-lg">Pantau dan kelola semua pesanan pengguna</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="bg-slate-700 hover:bg-slate-600 text-white px-6 py-3 rounded-lg font-medium transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        @php
            $totalOrders = $orders->total() ?? 0;
            $pendingOrders = $orders->where('status', 'pending_payment')->count();
            $processingOrders = $orders->where('status', 'processing')->count();
            $completedOrders = $orders->where('status', 'delivered')->count();
        @endphp
        <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Total Pesanan</p>
                    <p class="text-2xl font-bold text-white">{{ $totalOrders }}</p>
                </div>
                <div class="bg-blue-500/20 p-3 rounded-lg">
                    <i class="fas fa-shopping-cart text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Menunggu Bayar</p>
                    <p class="text-2xl font-bold text-yellow-400">{{ $pendingOrders }}</p>
                </div>
                <div class="bg-yellow-500/20 p-3 rounded-lg">
                    <i class="fas fa-clock text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Diproses</p>
                    <p class="text-2xl font-bold text-purple-400">{{ $processingOrders }}</p>
                </div>
                <div class="bg-purple-500/20 p-3 rounded-lg">
                    <i class="fas fa-box text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Selesai</p>
                    <p class="text-2xl font-bold text-green-400">{{ $completedOrders }}</p>
                </div>
                <div class="bg-green-500/20 p-3 rounded-lg">
                    <i class="fas fa-check-circle text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Cards (Mobile-Friendly) -->
    <div class="block lg:hidden space-y-4">
        @forelse($orders as $order)
        <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-white">#{{ $order->order_number }}</h3>
                    <p class="text-slate-400 text-sm">{{ $order->customer_name }}</p>
                    <p class="text-slate-500 text-sm">{{ $order->customer_email }}</p>
                </div>
                <div class="text-right">
                    @php
                        $statusConfig = [
                            'pending' => ['label' => 'Pending', 'color' => 'gray'],
                            'pending_payment' => ['label' => 'Belum Bayar', 'color' => 'yellow'],
                            'payment_verification' => ['label' => 'Verifikasi', 'color' => 'amber'],
                            'paid' => ['label' => 'Sudah Bayar', 'color' => 'blue'],
                            'processing' => ['label' => 'Dikemas', 'color' => 'purple'],
                            'shipped' => ['label' => 'Dikirim', 'color' => 'indigo'],
                            'delivered' => ['label' => 'Selesai', 'color' => 'green'],
                            'cancelled' => ['label' => 'Dibatalkan', 'color' => 'red']
                        ];
                        $currentStatus = $statusConfig[$order->status] ?? ['label' => ucfirst($order->status), 'color' => 'gray'];
                    @endphp
                    <span class="px-3 py-2 rounded-lg text-sm font-medium bg-{{ $currentStatus['color'] }}-500/20 text-{{ $currentStatus['color'] }}-400 border border-{{ $currentStatus['color'] }}-500/30">
                        {{ $currentStatus['label'] }}
                    </span>
                </div>
            </div>
            
            <div class="border-t border-slate-700 pt-4">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <p class="text-xl font-bold text-purple-400">
                            Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}
                        </p>
                        <p class="text-slate-500 text-sm">
                            {{ $order->orderItems ? $order->orderItems->sum('quantity') : 0 }} item
                        </p>
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-2">
                    <!-- Payment Proof Button -->
                    @if($order->payment_method !== 'cod' && $order->payment && $order->payment->receipt_path)
                        <button onclick="viewPaymentProof('{{ asset('storage/' . $order->payment->receipt_path) }}')" 
                                class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-receipt mr-2"></i>Bukti Bayar
                        </button>
                    @endif
                    
                    <!-- Status Update Buttons -->
                    @if($order->payment_method === 'cod')
                        @if($order->status == 'pending' || $order->status == 'pending_payment')
                            <button onclick="updateStatus({{ $order->id }}, 'processing')" 
                                    class="bg-purple-600 hover:bg-purple-500 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                <i class="fas fa-box mr-2"></i>Dikemas
                            </button>
                        @elseif($order->status == 'processing')
                            <button onclick="updateStatus({{ $order->id }}, 'shipped')" 
                                    class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                <i class="fas fa-truck mr-2"></i>Kirim
                            </button>
                        @elseif($order->status == 'shipped')
                            <button onclick="updateStatus({{ $order->id }}, 'delivered')" 
                                    class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                <i class="fas fa-check mr-2"></i>Selesai
                            </button>
                        @endif
                    @else
                        @if($order->status == 'pending_payment')
                            <button onclick="updateStatus({{ $order->id }}, 'payment_verification')" 
                                    class="bg-amber-600 hover:bg-amber-500 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                <i class="fas fa-eye mr-2"></i>Verifikasi
                            </button>
                        @elseif($order->status == 'payment_verification')
                            <button onclick="updateStatus({{ $order->id }}, 'paid')" 
                                    class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                <i class="fas fa-check mr-2"></i>Setujui
                            </button>
                        @elseif($order->status == 'paid')
                            <button onclick="updateStatus({{ $order->id }}, 'processing')" 
                                    class="bg-purple-600 hover:bg-purple-500 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                <i class="fas fa-box mr-2"></i>Proses
                            </button>
                        @elseif($order->status == 'processing')
                            <button onclick="updateStatus({{ $order->id }}, 'shipped')" 
                                    class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                <i class="fas fa-truck mr-2"></i>Kirim
                            </button>
                        @elseif($order->status == 'shipped')
                            <button onclick="updateStatus({{ $order->id }}, 'delivered')" 
                                    class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                <i class="fas fa-check mr-2"></i>Selesai
                            </button>
                        @endif
                    @endif
                    
                    <!-- Detail Button -->
                    <a href="{{ route('order.show', $order->id) }}"
                       class="bg-slate-600 hover:bg-slate-500 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-eye mr-2"></i>Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-12 text-center">
            <i class="fas fa-shopping-cart text-6xl text-slate-600 mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-400 mb-2">Tidak ada pesanan ditemukan</h3>
            <p class="text-slate-500">Coba ubah filter pencarian Anda</p>
        </div>
        @endforelse
    </div>
    
    <!-- Orders Table (Desktop) -->
    <div class="hidden lg:block bg-slate-800/50 border border-slate-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-700/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-slate-300 font-semibold">No</th>
                        <th class="px-6 py-4 text-left text-slate-300 font-semibold">Nomor Pesanan</th>
                        <th class="px-6 py-4 text-left text-slate-300 font-semibold">Pelanggan</th>
                        <th class="px-6 py-4 text-left text-slate-300 font-semibold">Barang</th>
                        <th class="px-6 py-4 text-left text-slate-300 font-semibold">Total</th>
                        <th class="px-6 py-4 text-left text-slate-300 font-semibold">Status</th>
                        <th class="px-6 py-4 text-center text-slate-300 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($orders as $order)
                    <tr class="hover:bg-slate-700/30 transition-colors">
                        <td class="px-6 py-4 text-slate-400 font-medium">{{ $order->id }}</td>
                        <td class="px-6 py-4 font-semibold text-white">#{{ $order->order_number }}</td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-white">{{ $order->customer_name }}</p>
                                <p class="text-sm text-slate-400">{{ $order->customer_email }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-2">
                                @if($order->orderItems && $order->orderItems->count() > 0)
                                    @foreach($order->orderItems->take(2) as $item)
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-slate-700 rounded-lg overflow-hidden flex-shrink-0">
                                                @if($item->product && $item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                         alt="{{ $item->product->name ?? 'Product' }}" 
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-slate-600 flex items-center justify-center">
                                                        <i class="fas fa-image text-slate-400"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-medium text-white truncate">
                                                    {{ $item->product_name ?? ($item->product->name ?? 'Produk') }}
                                                </p>
                                                <div class="flex items-center gap-2 text-sm text-slate-400">
                                                    @if($item->size)
                                                        <span class="bg-slate-700 px-2 py-1 rounded">{{ $item->size }}</span>
                                                    @endif
                                                    <span>×{{ $item->quantity }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @if($order->orderItems->count() > 2)
                                        <button onclick="toggleOrderItems({{ $order->id }})" 
                                                class="text-purple-400 hover:text-purple-300 text-sm font-medium">
                                            +{{ $order->orderItems->count() - 2 }} item lainnya
                                        </button>
                                        
                                        <div id="order-items-{{ $order->id }}" class="hidden space-y-2 pt-2 border-t border-slate-600">
                                            @foreach($order->orderItems->skip(2) as $item)
                                                <div class="flex items-center gap-3">
                                                    <div class="w-12 h-12 bg-slate-700 rounded-lg overflow-hidden flex-shrink-0">
                                                        @if($item->product && $item->product->image)
                                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                                 alt="{{ $item->product->name ?? 'Product' }}" 
                                                                 class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full bg-slate-600 flex items-center justify-center">
                                                                <i class="fas fa-image text-slate-400"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="font-medium text-white truncate">
                                                            {{ $item->product_name ?? ($item->product->name ?? 'Produk') }}
                                                        </p>
                                                        <div class="flex items-center gap-2 text-sm text-slate-400">
                                                            @if($item->size)
                                                                <span class="bg-slate-700 px-2 py-1 rounded">{{ $item->size }}</span>
                                                            @endif
                                                            <span>×{{ $item->quantity }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                    <div class="text-slate-500 flex items-center gap-2">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span>Tidak ada detail barang</span>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-lg font-bold text-purple-400">
                                    Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-slate-500">
                                    {{ $order->orderItems ? $order->orderItems->sum('quantity') : 0 }} item
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusConfig = [
                                    'pending' => ['label' => 'Pending', 'color' => 'gray'],
                                    'pending_payment' => ['label' => 'Belum Bayar', 'color' => 'yellow'],
                                    'payment_verification' => ['label' => 'Verifikasi', 'color' => 'amber'],
                                    'paid' => ['label' => 'Sudah Bayar', 'color' => 'blue'],
                                    'processing' => ['label' => 'Dikemas', 'color' => 'purple'],
                                    'shipped' => ['label' => 'Dikirim', 'color' => 'indigo'],
                                    'delivered' => ['label' => 'Selesai', 'color' => 'green'],
                                    'cancelled' => ['label' => 'Dibatalkan', 'color' => 'red']
                                ];
                                $currentStatus = $statusConfig[$order->status] ?? ['label' => ucfirst($order->status), 'color' => 'gray'];
                            @endphp
                            
                            <span class="px-3 py-2 rounded-lg font-medium bg-{{ $currentStatus['color'] }}-500/20 text-{{ $currentStatus['color'] }}-400 border border-{{ $currentStatus['color'] }}-500/30">
                                {{ $currentStatus['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2 justify-center">
                                <!-- Payment Proof Button -->
                                @if($order->payment_method !== 'cod' && $order->payment && $order->payment->receipt_path)
                                    <button onclick="viewPaymentProof('{{ asset('storage/' . $order->payment->receipt_path) }}')" 
                                            class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-2 rounded-lg font-medium transition-colors">
                                        <i class="fas fa-receipt mr-1"></i>Bukti
                                    </button>
                                @endif
                                
                                <!-- Status Update Buttons -->
                                @if($order->payment_method === 'cod')
                                    @if($order->status == 'pending' || $order->status == 'pending_payment')
                                        <button onclick="updateStatus({{ $order->id }}, 'processing')" 
                                                class="bg-purple-600 hover:bg-purple-500 text-white px-3 py-2 rounded-lg font-medium transition-colors">
                                            <i class="fas fa-box mr-1"></i>Kemas
                                        </button>
                                    @elseif($order->status == 'processing')
                                        <button onclick="updateStatus({{ $order->id }}, 'shipped')" 
                                                class="bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-2 rounded-lg font-medium transition-colors">
                                            <i class="fas fa-truck mr-1"></i>Kirim
                                        </button>
                                    @elseif($order->status == 'shipped')
                                        <button onclick="updateStatus({{ $order->id }}, 'delivered')" 
                                                class="bg-green-600 hover:bg-green-500 text-white px-3 py-2 rounded-lg font-medium transition-colors">
                                            <i class="fas fa-check mr-1"></i>Selesai
                                        </button>
                                    @endif
                                @else
                                    @if($order->status == 'pending_payment')
                                        <button onclick="updateStatus({{ $order->id }}, 'payment_verification')" 
                                                class="bg-amber-600 hover:bg-amber-500 text-white px-3 py-2 rounded-lg font-medium transition-colors">
                                            <i class="fas fa-eye mr-1"></i>Verif
                                        </button>
                                    @elseif($order->status == 'payment_verification')
                                        <button onclick="updateStatus({{ $order->id }}, 'paid')" 
                                                class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-2 rounded-lg font-medium transition-colors">
                                            <i class="fas fa-check mr-1"></i>Setuju
                                        </button>
                                    @elseif($order->status == 'paid')
                                        <button onclick="updateStatus({{ $order->id }}, 'processing')" 
                                                class="bg-purple-600 hover:bg-purple-500 text-white px-3 py-2 rounded-lg font-medium transition-colors">
                                            <i class="fas fa-box mr-1"></i>Proses
                                        </button>
                                    @elseif($order->status == 'processing')
                                        <button onclick="updateStatus({{ $order->id }}, 'shipped')" 
                                                class="bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-2 rounded-lg font-medium transition-colors">
                                            <i class="fas fa-truck mr-1"></i>Kirim
                                        </button>
                                    @elseif($order->status == 'shipped')
                                        <button onclick="updateStatus({{ $order->id }}, 'delivered')" 
                                                class="bg-green-600 hover:bg-green-500 text-white px-3 py-2 rounded-lg font-medium transition-colors">
                                            <i class="fas fa-check mr-1"></i>Selesai
                                        </button>
                                    @endif
                                @endif
                                
                                <!-- Detail Button -->
                                <a href="{{ route('order.show', $order->id) }}"
                                   class="bg-slate-600 hover:bg-slate-500 text-white px-3 py-2 rounded-lg font-medium transition-colors">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-shopping-cart text-6xl text-slate-600 mb-4"></i>
                                <h3 class="text-xl font-semibold text-slate-400 mb-2">Tidak ada pesanan ditemukan</h3>
                                <p class="text-slate-500">Coba ubah filter pencarian Anda</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    <div class="mt-8">
        {{ $orders->withQueryString()->links() }}
    </div>
</div>

<!-- Payment Proof Modal -->
<div id="paymentProofModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-slate-800 border border-slate-700 rounded-xl max-w-4xl w-full p-6 relative max-h-[90vh] overflow-hidden">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-white">Bukti Pembayaran</h3>
                <button onclick="closePaymentProofModal()" class="text-slate-400 hover:text-white text-xl p-2">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="text-center overflow-auto max-h-[70vh]">
                <img id="paymentProofImage" src="" alt="Bukti Pembayaran" 
                     class="max-w-full h-auto rounded-lg shadow-lg mx-auto object-contain">
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Functions -->
<script>
function toggleOrderItems(orderId) {
    const element = document.getElementById(`order-items-${orderId}`);
    const button = document.querySelector(`button[onclick="toggleOrderItems(${orderId})"]`);
    
    if (element.classList.contains('hidden')) {
        element.classList.remove('hidden');
        button.innerHTML = button.innerHTML.replace('+', '-') + ' (tutup)';
    } else {
        element.classList.add('hidden');
        button.innerHTML = button.innerHTML.replace('-', '+').replace(' (tutup)', '');
    }
}

function updateStatus(orderId, newStatus) {
    // Show confirmation with more descriptive message
    const statusNames = {
        'payment_verification': 'Verifikasi Pembayaran',
        'paid': 'Setujui Pembayaran',
        'processing': 'Proses Pesanan',
        'shipped': 'Kirim Pesanan',
        'delivered': 'Selesaikan Pesanan'
    };
    
    const actionName = statusNames[newStatus] || 'mengubah status pesanan';
    
    if (!confirm(`Apakah Anda yakin ingin ${actionName.toLowerCase()} ini?`)) {
        return;
    }
    
    // Show loading state
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
            
            // Reload page after a short delay
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Gagal memperbarui status');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showNotification('Error: ' + (err.message || 'Terjadi kesalahan server'), 'error');
        
        // Reset button state
        button.disabled = false;
        button.innerHTML = originalHTML;
    });
}

function viewPaymentProof(imageUrl) {
    document.getElementById('paymentProofImage').src = imageUrl;
    document.getElementById('paymentProofModal').classList.remove('hidden');
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
}

function closePaymentProofModal() {
    document.getElementById('paymentProofModal').classList.add('hidden');
    // Restore body scroll
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('paymentProofModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePaymentProofModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('paymentProofModal').classList.contains('hidden')) {
        closePaymentProofModal();
    }
});

// Enhanced notification system
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(n => n.remove());
    
    const notification = document.createElement('div');
    notification.className = `notification fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-xl text-white transform transition-all duration-300 translate-x-full ${
        type === 'success' ? 'bg-green-600' : 
        type === 'error' ? 'bg-red-600' : 
        'bg-blue-600'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas ${
                    type === 'success' ? 'fa-check-circle' : 
                    type === 'error' ? 'fa-exclamation-circle' : 
                    'fa-info-circle'
                } text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="font-medium">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    // Add to document
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 10);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }
    }, 5000);
}

// Add loading states for buttons
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth transitions to all buttons
    const buttons = document.querySelectorAll('button, a');
    buttons.forEach(button => {
        button.style.transition = 'all 0.2s ease-in-out';
    });
    
    // Add hover effects to cards
    const cards = document.querySelectorAll('.bg-slate-800\\/50');
    cards.forEach(card => {
        card.style.transition = 'all 0.2s ease-in-out';
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 10px 25px rgba(0,0,0,0.2)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
        });
    });
});

// Auto-refresh functionality (optional)
let autoRefreshInterval;
function toggleAutoRefresh() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
        autoRefreshInterval = null;
        showNotification('Auto refresh dimatikan', 'info');
    } else {
        autoRefreshInterval = setInterval(() => {
            location.reload();
        }, 30000); // Refresh every 30 seconds
        showNotification('Auto refresh diaktifkan (30 detik)', 'info');
    }
}

// Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + R for refresh
    if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
        e.preventDefault();
        location.reload();
    }
    
    // Ctrl/Cmd + F for focus search
    if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
        e.preventDefault();
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
    }
});
</script>

@endsection