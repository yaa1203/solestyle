@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Page Title -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h2 class="text-2xl font-bold">Dashboard</h2>
        <p class="text-slate-400">Selamat datang di panel admin SoleStyle</p>
    </div>
    <div class="flex items-center gap-4">
        <button class="glass-effect hover:bg-slate-700/50 px-4 py-2 rounded-xl flex items-center gap-2">
            <i class="fas fa-download"></i>
            <span>Ekspor Laporan</span>
        </button>
        <button class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 px-4 py-2 rounded-xl font-semibold transition-all transform hover:scale-105 flex items-center gap-2">
            <i class="fas fa-plus"></i>
            <span>Tambah Produk</span>
        </button>
    </div>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @php
    $stats = [
        ['icon' => 'shopping-bag', 'color' => 'purple', 'value' => '542', 'label' => 'Total Pesanan', 'trend' => '+12.5%', 'width' => '70%'],
        ['icon' => 'users', 'color' => 'pink', 'value' => '1,248', 'label' => 'Total Pelanggan', 'trend' => '+8.3%', 'width' => '60%'],
        ['icon' => 'credit-card', 'color' => 'blue', 'value' => 'Rp 82.5Jt', 'label' => 'Pendapatan Bulanan', 'trend' => '+15.2%', 'width' => '85%'],
        ['icon' => 'chart-line', 'color' => 'green', 'value' => '4.2%', 'label' => 'Rasio Penolakan', 'trend' => '-3.6%', 'width' => '42%', 'negative' => true]
    ]
    @endphp

    @foreach($stats as $stat)
    <div class="dashboard-card glass-effect rounded-2xl p-6 transition-all">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-{{ $stat['color'] }}-500/20 rounded-xl">
                <i class="fas fa-{{ $stat['icon'] }} text-{{ $stat['color'] }}-400 text-xl"></i>
            </div>
            <div class="text-{{ isset($stat['negative']) ? 'red' : 'green' }}-400 text-sm font-semibold flex items-center">
                <i class="fas fa-arrow-{{ isset($stat['negative']) ? 'down' : 'up' }} mr-1"></i> {{ $stat['trend'] }}
            </div>
        </div>
        <div class="mb-2">
            <div class="text-3xl font-bold">{{ $stat['value'] }}</div>
            <div class="text-slate-400 text-sm">{{ $stat['label'] }}</div>
        </div>
        <div class="w-full bg-slate-700 rounded-full h-2">
            <div class="bg-{{ $stat['color'] }}-600 h-2 rounded-full" style="width: {{ $stat['width'] }}"></div>
        </div>
    </div>
    @endforeach
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="glass-effect rounded-2xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-semibold">Pendapatan Bulanan</h3>
            <div class="flex items-center gap-2">
                <button class="px-3 py-1 bg-slate-700 rounded-lg text-xs font-semibold">Bulanan</button>
                <button class="px-3 py-1 bg-slate-700/50 rounded-lg text-xs font-semibold hover:bg-slate-700">Tahunan</button>
            </div>
        </div>
        <canvas id="revenueChart" height="300"></canvas>
    </div>
    
    <div class="glass-effect rounded-2xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-semibold">Kategori Terlaris</h3>
            <button class="text-slate-400 hover:text-white">
                <i class="fas fa-ellipsis-h"></i>
            </button>
        </div>
        <canvas id="categoryChart" height="300"></canvas>
    </div>
</div>

<!-- Recent Orders & Top Products -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Orders -->
    <div class="glass-effect rounded-2xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-semibold">Pesanan Terbaru</h3>
            <button class="text-purple-400 text-sm font-semibold">Lihat Semua</button>
        </div>
        
        <div class="space-y-4">
            @php
            $orders = [
                ['id' => '#ORD-7842', 'customer' => 'Budi Santoso', 'amount' => 'Rp 1.200.000', 'status' => 'Selesai', 'status_color' => 'green'],
                ['id' => '#ORD-7841', 'customer' => 'Sari Dewi', 'amount' => 'Rp 2.500.000', 'status' => 'Proses', 'status_color' => 'yellow'],
                ['id' => '#ORD-7840', 'customer' => 'Ahmad Rahman', 'amount' => 'Rp 850.000', 'status' => 'Pengemasan', 'status_color' => 'blue'],
                ['id' => '#ORD-7839', 'customer' => 'Rina Wijaya', 'amount' => 'Rp 3.100.000', 'status' => 'Selesai', 'status_color' => 'green']
            ]
            @endphp

            @foreach($orders as $order)
            <div class="flex items-center justify-between p-4 hover:bg-slate-700/30 rounded-xl transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-slate-700 rounded-xl flex items-center justify-center">
                        <i class="fas fa-shopping-bag text-purple-400"></i>
                    </div>
                    <div>
                        <div class="font-semibold">{{ $order['id'] }}</div>
                        <div class="text-slate-400 text-sm">{{ $order['customer'] }}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">{{ $order['amount'] }}</div>
                    <div class="text-{{ $order['status_color'] }}-400 text-sm">{{ $order['status'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Top Products -->
    <div class="glass-effect rounded-2xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-semibold">Produk Terlaris</h3>
            <button class="text-purple-400 text-sm font-semibold">Lihat Semua</button>
        </div>
        
        <div class="space-y-4">
            @php
            $products = [
                ['name' => 'Nike Air Max Pro', 'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80', 'rating' => '4.8', 'sold' => '142', 'price' => 'Rp 1.200.000', 'stock' => '45', 'stock_color' => 'green'],
                ['name' => 'Adidas Ultraboost 22', 'image' => 'https://images.unsplash.com/photo-1556906781-9a412961c28c?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80', 'rating' => '4.9', 'sold' => '98', 'price' => 'Rp 2.000.000', 'stock' => '12', 'stock_color' => 'yellow'],
                ['name' => 'Converse Chuck Taylor', 'image' => 'https://images.unsplash.com/photo-1607522370275-f14206abe5d3?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80', 'rating' => '4.7', 'sold' => '76', 'price' => 'Rp 850.000', 'stock' => '32', 'stock_color' => 'green'],
                ['name' => 'Nike Jordan Air 1', 'image' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80', 'rating' => '4.8', 'sold' => '63', 'price' => 'Rp 1.500.000', 'stock' => '5', 'stock_color' => 'red']
            ]
            @endphp

            @foreach($products as $product)
            <div class="flex items-center gap-4 p-4 hover:bg-slate-700/30 rounded-xl transition-all">
                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-16 h-16 object-cover rounded-xl">
                <div class="flex-1">
                    <h4 class="font-semibold">{{ $product['name'] }}</h4>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="text-yellow-400 text-sm">
                            <i class="fas fa-star"></i> {{ $product['rating'] }}
                        </div>
                        <div class="text-slate-400 text-sm">• {{ $product['sold'] }} terjual</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-semibold text-purple-400">{{ $product['price'] }}</div>
                    <div class="text-{{ $product['stock_color'] }}-400 text-sm">Stok: {{ $product['stock'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Activity Timeline -->
<div class="glass-effect rounded-2xl p-6 mb-8">
    <div class="flex items-center justify-between mb-6">
        <h3 class="font-semibold">Aktivitas Terbaru</h3>
        <button class="text-purple-400 text-sm font-semibold">Lihat Semua</button>
    </div>
    
    <div class="space-y-4">
        @php
        $activities = [
            ['type' => 'check', 'color' => 'green', 'title' => 'Pesanan #ORD-7842 telah selesai', 'desc' => 'Pesanan dari Budi Santoso telah berhasil dikirim dan diterima', 'time' => '2 jam yang lalu'],
            ['type' => 'sync-alt', 'color' => 'blue', 'title' => 'Stok produk diperbarui', 'desc' => 'Stok Nike Air Max Pro diperbarui dari 32 menjadi 45', 'time' => '5 jam yang lalu'],
            ['type' => 'plus', 'color' => 'purple', 'title' => 'Produk baru ditambahkan', 'desc' => 'Adidas Ultraboost 22 telah ditambahkan ke katalog', 'time' => 'Kemarin, 15:42'],
            ['type' => 'exclamation-triangle', 'color' => 'yellow', 'title' => 'Peringatan stok rendah', 'desc' => 'Nike Jordan Air 1 hampir habis, hanya tersisa 5 unit', 'time' => '2 hari yang lalu', 'last' => true]
        ]
        @endphp

        @foreach($activities as $activity)
        <div class="flex gap-4">
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 bg-{{ $activity['color'] }}-500/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-{{ $activity['type'] }} text-{{ $activity['color'] }}-400"></i>
                </div>
                @if(!isset($activity['last']))
                <div class="flex-1 w-1 bg-slate-700 my-1"></div>
                @endif
            </div>
            <div class="flex-1 {{ !isset($activity['last']) ? 'pb-4' : '' }}">
                <div class="font-semibold">{{ $activity['title'] }}</div>
                <p class="text-slate-400 text-sm">{{ $activity['desc'] }}</p>
                <div class="text-slate-500 text-xs mt-1">{{ $activity['time'] }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        datasets: [{
            label: 'Pendapatan (Juta Rupiah)',
            data: [45, 52, 38, 45, 59, 62, 69, 71, 82, 76, 68, 74],
            borderColor: '#9333ea',
            backgroundColor: 'rgba(147, 51, 234, 0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#9333ea',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: '#9333ea',
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(255, 255, 255, 0.1)' },
                ticks: { color: '#94a3b8' }
            },
            x: {
                grid: { color: 'rgba(255, 255, 255, 0.1)' },
                ticks: { color: '#94a3b8' }
            }
        }
    }
});

// Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
const categoryChart = new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: ['Sneakers', 'Olahraga', 'Boots', 'Formal', 'Lainnya'],
        datasets: [{
            data: [35, 25, 15, 10, 15],
            backgroundColor: ['#9333ea', '#ec4899', '#3b82f6', '#10b981', '#6366f1'],
            borderWidth: 0,
            hoverOffset: 15
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'right',
                labels: { color: '#94a3b8', font: { size: 12 } }
            }
        },
        cutout: '70%'
    }
});
</script>
@endsection