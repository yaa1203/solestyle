@extends('admin.layouts.app')
@section('title', 'Kelola Pesanan - SoleStyle')
@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-white mb-2">Kelola Pesanan</h1>
            <p class="text-slate-400">Pantau dan kelola semua pesanan pengguna</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
    <!-- Filter & Search -->
    <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-4 mb-6">
        <form method="GET" class="grid md:grid-cols-4 gap-4">
            <div>
                <label class="text-slate-300 text-sm mb-1 block">Status</label>
                <select name="status" class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm">
                    <option value="all" {{ request('status','all')==='all'?'selected':'' }}>Semua</option>
                    <option value="pending_payment" {{ request('status')==='pending_payment'?'selected':'' }}>Belum Bayar</option>
                    <option value="payment_verification" {{ request('status')==='payment_verification'?'selected':'' }}>Verifikasi Pembayaran</option>
                    <option value="paid" {{ request('status')==='paid'?'selected':'' }}>Sudah Bayar</option>
                    <option value="processing" {{ request('status')==='processing'?'selected':'' }}>Dikemas</option>
                    <option value="shipped" {{ request('status')==='shipped'?'selected':'' }}>Dikirim</option>
                    <option value="delivered" {{ request('status')==='delivered'?'selected':'' }}>Selesai</option>
                    <option value="cancelled" {{ request('status')==='cancelled'?'selected':'' }}>Dibatalkan</option>
                </select>
            </div>
            <div>
                <label class="text-slate-300 text-sm mb-1 block">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm">
            </div>
            <div>
                <label class="text-slate-300 text-sm mb-1 block">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm">
            </div>
            <div>
                <label class="text-slate-300 text-sm mb-1 block">Cari</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nomor pesanan / nama / email"
                           class="w-full bg-slate-700/50 border border-slate-600 rounded-lg pl-10 pr-3 py-2 text-white text-sm">
                    <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                </div>
            </div>
            <div class="md:col-span-4 flex justify-end">
                <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm">
                    Terapkan
                </button>
            </div>
        </form>
    </div>
    <!-- Orders Table -->
    <div class="bg-slate-800/50 border border-slate-700 rounded-xl overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-700/50 text-slate-300 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Nomor Pesanan</th>
                    <th class="px-4 py-3">Pelanggan</th>
                    <th class="px-4 py-3">Barang Dipesan</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                @forelse($orders as $order)
                <tr class="hover:bg-slate-700/30">
                    <td class="px-4 py-3 text-slate-400">{{ $order->id }}</td>
                    <td class="px-4 py-3 font-medium text-white">#{{ $order->order_number }}</td>
                    <td class="px-4 py-3 text-slate-300">
                        {{ $order->customer_name }} <br>
                        <span class="text-xs text-slate-500">{{ $order->customer_email }}</span>
                    </td>
                    <td class="px-4 py-3 text-slate-300">
                        <div class="space-y-2">
                            @if($order->orderItems && $order->orderItems->count() > 0)
                                @foreach($order->orderItems->take(2) as $item)
                                    <div class="flex items-center gap-3">
                                        <!-- Product Image -->
                                        <div class="w-8 h-8 bg-slate-700 rounded overflow-hidden flex-shrink-0">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                     alt="{{ $item->product->name ?? 'Product' }}" 
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-slate-600 flex items-center justify-center">
                                                    <i class="fas fa-image text-slate-400 text-xs"></i>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Product Info -->
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-white text-xs truncate">
                                                {{ $item->product_name ?? ($item->product->name ?? 'Produk tidak diketahui') }}
                                            </p>
                                            <div class="flex items-center gap-2 text-xs text-slate-400 mt-1">
                                                @if($item->size)
                                                    <span class="bg-slate-700 px-1.5 py-0.5 rounded text-xs">{{ $item->size }}</span>
                                                @endif
                                                <span>×{{ $item->quantity }}</span>
                                                <span class="text-purple-400 font-medium">
                                                    Rp {{ number_format($item->price ?? 0, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                
                                @if($order->orderItems->count() > 2)
                                    <div class="text-xs text-slate-400 mt-1">
                                        <button onclick="toggleOrderItems({{ $order->id }})" 
                                                class="text-purple-400 hover:text-purple-300">
                                            +{{ $order->orderItems->count() - 2 }} item lainnya
                                        </button>
                                    </div>
                                    
                                    <!-- Hidden additional items -->
                                    <div id="order-items-{{ $order->id }}" class="hidden space-y-2 pt-2 border-t border-slate-600">
                                        @foreach($order->orderItems->skip(2) as $item)
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 bg-slate-700 rounded overflow-hidden flex-shrink-0">
                                                    @if($item->product && $item->product->image)
                                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                             alt="{{ $item->product->name ?? 'Product' }}" 
                                                             class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full bg-slate-600 flex items-center justify-center">
                                                            <i class="fas fa-image text-slate-400 text-xs"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-medium text-white text-xs truncate">
                                                        {{ $item->product_name ?? ($item->product->name ?? 'Produk tidak diketahui') }}
                                                    </p>
                                                    <div class="flex items-center gap-2 text-xs text-slate-400 mt-1">
                                                        @if($item->size)
                                                            <span class="bg-slate-700 px-1.5 py-0.5 rounded text-xs">{{ $item->size }}</span>
                                                        @endif
                                                        <span>×{{ $item->quantity }}</span>
                                                        <span class="text-purple-400 font-medium">
                                                            Rp {{ number_format($item->price ?? 0, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <div class="text-slate-500 text-xs flex items-center gap-2">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>Tidak ada detail barang</span>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 text-slate-300">
                        <div class="text-sm font-semibold text-purple-400">
                            Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="text-xs text-slate-500">
                            {{ $order->orderItems ? $order->orderItems->sum('quantity') : 0 }} item
                        </div>
                    </td>
                    <td class="px-4 py-3 text-slate-400">{{ $order->order_date->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3">
                        @php
                            $statusConfig = [
                                'pending_payment' => ['label' => 'Belum Bayar', 'color' => 'yellow'],
                                'payment_verification' => ['label' => 'Verifikasi Pembayaran', 'color' => 'amber'],
                                'paid' => ['label' => 'Sudah Bayar', 'color' => 'blue'],
                                'processing' => ['label' => 'Dikemas', 'color' => 'purple'],
                                'shipped' => ['label' => 'Dikirim', 'color' => 'indigo'],
                                'delivered' => ['label' => 'Selesai', 'color' => 'green'],
                                'cancelled' => ['label' => 'Dibatalkan', 'color' => 'red']
                            ];
                            $currentStatus = $statusConfig[$order->status] ?? ['label' => ucfirst($order->status), 'color' => 'gray'];
                        @endphp
                        
                        @if($currentStatus['color'] == 'yellow')
                            <span class="px-2 py-1 rounded text-xs bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">
                                {{ $currentStatus['label'] }}
                            </span>
                        @elseif($currentStatus['color'] == 'amber')
                            <span class="px-2 py-1 rounded text-xs bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                {{ $currentStatus['label'] }}
                            </span>
                        @elseif($currentStatus['color'] == 'blue')
                            <span class="px-2 py-1 rounded text-xs bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                {{ $currentStatus['label'] }}
                            </span>
                        @elseif($currentStatus['color'] == 'purple')
                            <span class="px-2 py-1 rounded text-xs bg-purple-500/20 text-purple-400 border border-purple-500/30">
                                {{ $currentStatus['label'] }}
                            </span>
                        @elseif($currentStatus['color'] == 'indigo')
                            <span class="px-2 py-1 rounded text-xs bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
                                {{ $currentStatus['label'] }}
                            </span>
                        @elseif($currentStatus['color'] == 'green')
                            <span class="px-2 py-1 rounded text-xs bg-green-500/20 text-green-400 border border-green-500/30">
                                {{ $currentStatus['label'] }}
                            </span>
                        @elseif($currentStatus['color'] == 'red')
                            <span class="px-2 py-1 rounded text-xs bg-red-500/20 text-red-400 border border-red-500/30">
                                {{ $currentStatus['label'] }}
                            </span>
                        @else
                            <span class="px-2 py-1 rounded text-xs bg-gray-500/20 text-gray-400 border border-gray-500/30">
                                {{ $currentStatus['label'] }}
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('order.show', $order->id) }}"
                           class="bg-slate-600 hover:bg-slate-500 text-white px-3 py-1 rounded text-xs transition-colors">
                            Detail
                        </a>
                        
                        @if($order->status == 'pending_payment')
                            <button onclick="updateStatus({{ $order->id }}, 'payment_verification')" 
                                    class="bg-amber-600 hover:bg-amber-500 text-white px-3 py-1 rounded text-xs transition-colors">
                                Verifikasi Pembayaran
                            </button>
                        @endif
                        
                        @if($order->status == 'payment_verification')
                            <button onclick="updateStatus({{ $order->id }}, 'paid')" 
                                    class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-1 rounded text-xs transition-colors">
                                Konfirmasi Pembayaran
                            </button>
                        @endif
                        
                        @if($order->status == 'paid')
                            <button onclick="updateStatus({{ $order->id }}, 'processing')" 
                                    class="bg-purple-600 hover:bg-purple-500 text-white px-3 py-1 rounded text-xs transition-colors">
                                Proses Pesanan
                            </button>
                        @endif
                        
                        @if($order->status == 'processing')
                            <button onclick="updateStatus({{ $order->id }}, 'shipped')" 
                                    class="bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-1 rounded text-xs transition-colors">
                                Kirim Pesanan
                            </button>
                        @endif
                        
                        @if($order->status == 'shipped')
                            <button onclick="updateStatus({{ $order->id }}, 'delivered')" 
                                    class="bg-green-600 hover:bg-green-500 text-white px-3 py-1 rounded text-xs transition-colors">
                                Selesaikan Pesanan
                            </button>
                        @endif
                        
                        @if($order->status == 'cancelled')
                            <span class="text-xs text-rose-400">Pesanan Dibatalkan</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-6 text-center text-slate-400">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-shopping-cart text-4xl text-slate-600 mb-4"></i>
                            <p class="text-lg">Tidak ada pesanan ditemukan</p>
                            <p class="text-sm text-slate-500 mt-1">Coba ubah filter pencarian Anda</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <div class="mt-6">
        {{ $orders->withQueryString()->links() }}
    </div>
</div>
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
    // Show confirmation
    if (!confirm(`Apakah Anda yakin ingin mengubah status pesanan ini menjadi ${getStatusLabel(newStatus)}?`)) {
        return;
    }
    
    // Show loading state
    const button = event.target;
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
            
            // Reload page
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            throw new Error(data.message || 'Gagal memperbarui status');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showNotification('Error: ' + (err.message || 'Terjadi kesalahan server'), 'error');
    })
    .finally(() => {
        // Reset button state (if not reloaded)
        button.disabled = false;
        button.innerHTML = getButtonLabel(newStatus);
    });
}
function getStatusLabel(status) {
    const labels = {
        'pending_payment': 'Belum Bayar',
        'payment_verification': 'Verifikasi Pembayaran',
        'paid': 'Sudah Bayar',
        'processing': 'Dikemas',
        'shipped': 'Dikirim',
        'delivered': 'Selesai',
        'cancelled': 'Dibatalkan'
    };
    return labels[status] || status;
}
function getButtonLabel(status) {
    const labels = {
        'payment_verification': 'Verifikasi Pembayaran',
        'paid': 'Konfirmasi Pembayaran',
        'processing': 'Proses Pesanan',
        'shipped': 'Kirim Pesanan',
        'delivered': 'Selesaikan Pesanan'
    };
    return labels[status] || 'Update';
}

// Simple notification system
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-600' : 
        type === 'error' ? 'bg-red-600' : 
        'bg-blue-600'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${
                type === 'success' ? 'fa-check-circle' : 
                type === 'error' ? 'fa-exclamation-circle' : 
                'fa-info-circle'
            } mr-3"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Add to document
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}
</script>
<style>
/* Custom styles for better status visibility */
.status-badge {
    display: inline-flex !important;
    align-items: center !important;
    gap: 4px !important;
    font-weight: 500 !important;
    white-space: nowrap !important;
    min-width: fit-content !important;
    visibility: visible !important;
    opacity: 1 !important;
    position: static !important;
    transform: none !important;
    transition: none !important;
}
.status-container {
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
    min-height: 32px !important;
    visibility: visible !important;
    opacity: 1 !important;
}
/* Ensure status column doesn't shrink */
table th:nth-child(7),
table td:nth-child(7) {
    min-width: 140px !important;
    width: 140px !important;
    white-space: nowrap !important;
}
/* Product items column styling */
table th:nth-child(4),
table td:nth-child(4) {
    min-width: 300px !important;
    max-width: 350px !important;
}
/* Prevent any auto-hide scripts from affecting status badges */
.status-badge,
.status-container,
.status-badge *,
.status-container * {
    animation: none !important;
    transition: none !important;
}
/* Override any potential conflicting styles */
[class*="bg-green-500/20"] .status-badge,
[class*="bg-red-500/20"] .status-badge,
[class*="bg-yellow-500/20"] .status-badge,
[class*="bg-blue-500/20"] .status-badge {
    visibility: visible !important;
    opacity: 1 !important;
    display: inline-flex !important;
}
/* Specific color overrides to ensure visibility */
.bg-amber-500\/20 { background-color: rgba(245, 158, 11, 0.2) !important; }
.text-amber-300 { color: rgb(252, 211, 77) !important; }
.border-amber-500\/40 { border-color: rgba(245, 158, 11, 0.4) !important; }
.bg-cyan-500\/20 { background-color: rgba(6, 182, 212, 0.2) !important; }
.text-cyan-300 { color: rgb(103, 232, 249) !important; }
.border-cyan-500\/40 { border-color: rgba(6, 182, 212, 0.4) !important; }
.bg-violet-500\/20 { background-color: rgba(139, 92, 246, 0.2) !important; }
.text-violet-300 { color: rgb(196, 181, 253) !important; }
.border-violet-500\/40 { border-color: rgba(139, 92, 246, 0.4) !important; }
.bg-blue-500\/20 { background-color: rgba(59, 130, 246, 0.2) !important; }
.text-blue-300 { color: rgb(147, 197, 253) !important; }
.border-blue-500\/40 { border-color: rgba(59, 130, 246, 0.4) !important; }
.bg-emerald-500\/20 { background-color: rgba(16, 185, 129, 0.2) !important; }
.text-emerald-300 { color: rgb(110, 231, 183) !important; }
.border-emerald-500\/40 { border-color: rgba(16, 185, 129, 0.4) !important; }
.bg-rose-500\/20 { background-color: rgba(244, 63, 94, 0.2) !important; }
.text-rose-300 { color: rgb(253, 164, 175) !important; }
.border-rose-500\/40 { border-color: rgba(244, 63, 94, 0.4) !important; }
/* Responsive adjustments */
@media (max-width: 1024px) {
    table th:nth-child(4),
    table td:nth-child(4) {
        min-width: 250px !important;
        max-width: 280px !important;
    }
}
@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }
    
    table {
        font-size: 0.75rem;
    }
    
    .status-badge {
        font-size: 0.6875rem !important;
        padding: 0.25rem 0.5rem !important;
    }
    
    table th:nth-child(4),
    table td:nth-child(4) {
        min-width: 200px !important;
        max-width: 220px !important;
    }
    
    table th:nth-child(7),
    table td:nth-child(7) {
        min-width: 120px !important;
        width: 120px !important;
    }
}
/* Product item hover effects */
.product-item:hover {
    background-color: rgba(51, 65, 85, 0.3);
    border-radius: 0.375rem;
}
/* Smooth transitions for expand/collapse */
.order-items-toggle {
    transition: all 0.3s ease-in-out;
}
</style>
@endsection