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
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Total</th>
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
                    <td class="px-4 py-3 text-slate-400">{{ $order->order_date->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3 text-white font-bold">{{ number_format($order->total_amount,0,',','.') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs bg-{{ $order->status_color }}-500/20 text-{{ $order->status_color }}-400">
                            {{ $order->status_label }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('orders.show', $order->id) }}"
                           class="bg-slate-600 hover:bg-slate-500 text-white px-3 py-1 rounded text-xs">
                            Detail
                        </a>
                        <button onclick="openUpdateStatus({{ $order->id }}, '{{ $order->status }}')"
                                class="bg-purple-600 hover:bg-purple-500 text-white px-3 py-1 rounded text-xs">
                            Update
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-slate-400">
                        Tidak ada pesanan ditemukan
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

<!-- Modal Update Status -->
<div id="updateStatusModal" class="fixed inset-0 bg-black/50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-slate-800 border border-slate-700 rounded-xl max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Update Status Pesanan</h3>
            <form id="updateStatusForm">
                <input type="hidden" name="order_id" id="statusOrderId">
                <div class="mb-4">
                    <label class="block text-sm text-slate-300 mb-2">Status</label>
                    <select name="status" id="statusSelect"
                            class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm">
                        <option value="pending_payment">Belum Bayar</option>
                        <option value="paid">Sudah Bayar</option>
                        <option value="processing">Dikemas</option>
                        <option value="shipped">Dikirim</option>
                        <option value="delivered">Selesai</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm text-slate-300 mb-2">No Resi (Opsional)</label>
                    <input type="text" name="tracking_number" 
                           class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm text-slate-300 mb-2">Catatan Admin</label>
                    <textarea name="admin_notes" rows="3"
                              class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeUpdateStatus()" 
                            class="flex-1 bg-slate-600 hover:bg-slate-700 text-white py-2 rounded-lg text-sm">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-lg text-sm">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openUpdateStatus(orderId, currentStatus) {
    document.getElementById('statusOrderId').value = orderId;
    document.getElementById('statusSelect').value = currentStatus;
    document.getElementById('updateStatusModal').classList.remove('hidden');
}

function closeUpdateStatus() {
    document.getElementById('updateStatusModal').classList.add('hidden');
}

document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const orderId = document.getElementById('statusOrderId').value;
    const formData = new FormData(this);

    fetch(`/order/${orderId}/update-status`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Gagal memperbarui status');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan server.');
    });
});
</script>
@endsection
