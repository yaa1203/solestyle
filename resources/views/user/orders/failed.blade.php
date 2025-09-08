<!-- resources/views/user/orders/failed.blade.php -->
@extends('user.layouts.app')

@section('title', 'Pesanan Gagal - SoleStyle')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-red-500/20 rounded-full mb-4">
                <i class="fas fa-exclamation-circle text-red-400 text-4xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Pembayaran Gagal</h1>
            <p class="text-slate-400">Terjadi kesalahan saat memproses pembayaran Anda</p>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl shadow-xl p-8">
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-white mb-4">Detail Pesanan</h2>
                <div class="bg-slate-700/30 rounded-lg p-4">
                    <p class="text-slate-300">Nomor Pesanan: <span class="text-white font-medium">{{ $order->order_number }}</span></p>
                    <p class="text-slate-300">Metode Pembayaran: <span class="text-white font-medium">{{ ucfirst($order->payment_method) }}</span></p>
                    <p class="text-slate-300">Total Pembayaran: <span class="text-white font-bold text-lg">Rp {{ number_format($order->total, 0, ',', '.') }}</span></p>
                    <p class="text-slate-300">Status: <span class="text-red-400 font-medium">Gagal</span></p>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-xl font-semibold text-white mb-4">Apa yang harus dilakukan?</h2>
                <div class="bg-slate-700/30 rounded-lg p-6">
                    <div class="flex items-start mb-4">
                        <div class="bg-purple-500/20 p-3 rounded-full mr-4">
                            <i class="fas fa-redo text-purple-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-medium mb-2">Coba Lagi</h3>
                            <p class="text-slate-400 text-sm">Anda dapat mencoba melakukan pembayaran kembali dengan cara yang sama.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start mb-4">
                        <div class="bg-purple-500/20 p-3 rounded-full mr-4">
                            <i class="fas fa-exchange-alt text-purple-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-medium mb-2">Ganti Metode Pembayaran</h3>
                            <p class="text-slate-400 text-sm">Anda dapat memilih metode pembayaran yang berbeda.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-purple-500/20 p-3 rounded-full mr-4">
                            <i class="fas fa-headset text-purple-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-medium mb-2">Hubungi Dukungan</h3>
                            <p class="text-slate-400 text-sm">Jika Anda masih mengalami masalah, silakan hubungi tim dukungan kami.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('payment.e-wallet', ['order_id' => $order->id, 'payment_method' => $order->payment_method]) }}" 
                   class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white py-3 rounded-lg font-medium transition-all">
                    <i class="fas fa-redo mr-2"></i>
                    Coba Lagi
                </a>
                
                <a href="{{ route('checkout') }}" 
                   class="flex-1 bg-slate-700 hover:bg-slate-600 text-white py-3 rounded-lg font-medium transition-all">
                    <i class="fas fa-credit-card mr-2"></i>
                    Ganti Metode Pembayaran
                </a>
                
                <a href="{{ route('home') }}" 
                   class="flex-1 bg-slate-900 hover:bg-slate-800 text-white py-3 rounded-lg font-medium transition-all">
                    <i class="fas fa-home mr-2"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh the page every 10 seconds to check payment status
    setInterval(function() {
        fetch(`/api/payment/check/{{ $order->id }}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'paid') {
                    showNotification('Pembayaran berhasil! Mengalihkan ke halaman pesanan...', 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route('order.success') }}';
                    }, 2000);
                }
            })
            .catch(error => {
                console.error('Error checking payment status:', error);
            });
    }, 10000);
});

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