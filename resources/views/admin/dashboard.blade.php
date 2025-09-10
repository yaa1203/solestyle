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
        <a href="{{ route('products.create') }}" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 px-4 py-2 rounded-xl font-semibold transition-all transform hover:scale-105 flex items-center gap-2">
            <i class="fas fa-plus"></i>
            <span>Tambah Produk</span>
        </a>
    </div>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="dashboard-card glass-effect rounded-2xl p-6 transition-all">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-purple-500/20 rounded-xl">
                <i class="fas fa-shopping-bag text-purple-400 text-xl"></i>
            </div>
            <div class="text-green-400 text-sm font-semibold flex items-center">
                <i class="fas fa-arrow-up mr-1"></i> +12.5%
            </div>
        </div>
        <div class="mb-2">
            <div class="text-3xl font-bold">{{ number_format($stats['total_orders']) }}</div>
            <div class="text-slate-400 text-sm">Total Pesanan</div>
        </div>
        <div class="w-full bg-slate-700 rounded-full h-2">
            <div class="bg-purple-600 h-2 rounded-full" style="width: 70%"></div>
        </div>
    </div>

    <div class="dashboard-card glass-effect rounded-2xl p-6 transition-all">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-pink-500/20 rounded-xl">
                <i class="fas fa-users text-pink-400 text-xl"></i>
            </div>
            <div class="text-green-400 text-sm font-semibold flex items-center">
                <i class="fas fa-arrow-up mr-1"></i> +8.3%
            </div>
        </div>
        <div class="mb-2">
            <div class="text-3xl font-bold">{{ number_format($stats['total_customers']) }}</div>
            <div class="text-slate-400 text-sm">Total Pelanggan</div>
        </div>
        <div class="w-full bg-slate-700 rounded-full h-2">
            <div class="bg-pink-600 h-2 rounded-full" style="width: 60%"></div>
        </div>
    </div>

    <div class="dashboard-card glass-effect rounded-2xl p-6 transition-all">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-blue-500/20 rounded-xl">
                <i class="fas fa-credit-card text-blue-400 text-xl"></i>
            </div>
            <div class="text-green-400 text-sm font-semibold flex items-center">
                <i class="fas fa-arrow-up mr-1"></i> +15.2%
            </div>
        </div>
        <div class="mb-2">
            <div class="text-3xl font-bold">Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}</div>
            <div class="text-slate-400 text-sm">Pendapatan Bulanan</div>
        </div>
        <div class="w-full bg-slate-700 rounded-full h-2">
            <div class="bg-blue-600 h-2 rounded-full" style="width: 85%"></div>
        </div>
    </div>

    <div class="dashboard-card glass-effect rounded-2xl p-6 transition-all">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-green-500/20 rounded-xl">
                <i class="fas fa-chart-line text-green-400 text-xl"></i>
            </div>
            <div class="text-red-400 text-sm font-semibold flex items-center">
                <i class="fas fa-arrow-down mr-1"></i> -3.6%
            </div>
        </div>
        <div class="mb-2">
            <div class="text-3xl font-bold">{{ number_format($stats['cancellation_rate'], 1) }}%</div>
            <div class="text-slate-400 text-sm">Rasio Penolakan</div>
        </div>
        <div class="w-full bg-slate-700 rounded-full h-2">
            <div class="bg-green-600 h-2 rounded-full" style="width: 42%"></div>
        </div>
    </div>
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
            <a href="{{ route('order.index') }}" class="text-purple-400 text-sm font-semibold">Lihat Semua</a>
        </div>
        
        <div class="space-y-4">
            @if($recentOrders->count() > 0)
                @foreach($recentOrders as $order)
                <div class="flex items-center justify-between p-4 hover:bg-slate-700/30 rounded-xl transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-slate-700 rounded-xl flex items-center justify-center">
                            <i class="fas fa-shopping-bag text-purple-400"></i>
                        </div>
                        <div>
                            <div class="font-semibold">#{{ $order['order_number'] }}</div>
                            <div class="text-slate-400 text-sm">{{ $order['customer_name'] }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-semibold">{{ $order['formatted_total'] }}</div>
                        <div class="text-{{ $order['status_color'] }}-400 text-sm">{{ $order['status_label'] }}</div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-8 text-slate-500">
                    <i class="fas fa-inbox text-3xl mb-2"></i>
                    <p>Tidak ada pesanan terbaru</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Top Products -->
    <div class="glass-effect rounded-2xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-semibold">Produk Terlaris</h3>
            <a href="{{ route('products.index') }}" class="text-purple-400 text-sm font-semibold">Lihat Semua</a>
        </div>
        
        <div class="space-y-4">
            @if($topProducts->count() > 0)
                @foreach($topProducts as $product)
                <div class="flex items-center gap-4 p-4 hover:bg-slate-700/30 rounded-xl transition-all">
                    @if($product['image_url'])
                        <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}" class="w-16 h-16 object-cover rounded-xl">
                    @else
                        <div class="w-16 h-16 bg-slate-700 rounded-xl flex items-center justify-center">
                            <i class="fas fa-image text-slate-500"></i>
                        </div>
                    @endif
                    <div class="flex-1">
                        <h4 class="font-semibold">{{ $product['name'] }}</h4>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="text-yellow-400 text-sm">
                                <i class="fas fa-star"></i> {{ $product['rating'] }}
                            </div>
                            <div class="text-slate-400 text-sm">• {{ $product['sold_count'] }} terjual</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-semibold text-purple-400">{{ $product['formatted_price'] }}</div>
                        <div class="text-{{ $product['stock_color'] }}-400 text-sm">Stok: {{ $product['stock'] }}</div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-8 text-slate-500">
                    <i class="fas fa-box-open text-3xl mb-2"></i>
                    <p>Tidak ada produk terlaris</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Activity Timeline -->
<div class="glass-effect rounded-2xl p-6 mb-8">
    <div class="flex items-center justify-between mb-6">
        <h3 class="font-semibold">Aktivitas Terbaru</h3>
        <a href="#" class="text-purple-400 text-sm font-semibold">Lihat Semua</a>
    </div>
    
    <div class="space-y-4">
        @foreach($activities as $activity)
        <div class="flex gap-4">
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 bg-{{ $activity['color'] }}-500/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-{{ $activity['type'] }} text-{{ $activity['color'] }}-400"></i>
                </div>
                @if(!$loop->last)
                <div class="flex-1 w-1 bg-slate-700 my-1"></div>
                @endif
            </div>
            <div class="flex-1 {{ !$loop->last ? 'pb-4' : '' }}">
                <div class="font-semibold">{{ $activity['title'] }}</div>
                <p class="text-slate-400 text-sm">{{ $activity['description'] }}</p>
                <div class="text-slate-500 text-xs mt-1">{{ $activity['time'] }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        plugins: { 
            legend: { 
                display: false,
                labels: {
                    color: '#94a3b8'
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(255, 255, 255, 0.1)' },
                ticks: { 
                    color: '#94a3b8',
                    callback: function(value) {
                        return 'Rp ' + value + 'Jt';
                    }
                }
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
                labels: { 
                    color: '#94a3b8', 
                    font: { size: 12 },
                    padding: 15
                }
            }
        },
        cutout: '70%'
    }
});

// Add smooth transitions to dashboard cards
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.dashboard-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Add hover effects to table rows
document.querySelectorAll('.glass-effect.rounded-2xl').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
    });
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});
</script>

<style>
/* Glass effect for dashboard cards */
.dashboard-card {
    background: rgba(30, 41, 59, 0.4);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(148, 163, 184, 0.1);
    transition: all 0.3s ease;
}

/* Chart container styling */
canvas {
    max-height: 300px;
}

/* Activity timeline styling */
.timeline-line {
    position: absolute;
    left: 20px;
    top: 40px;
    bottom: 0;
    width: 1px;
    background: rgba(148, 163, 184, 0.2);
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    .grid-cols-1.lg\\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .flex.items-center.justify-between.mb-8 {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .grid.grid-cols-1.lg\\:grid-cols-2.gap-6 {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection