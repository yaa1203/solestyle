@extends('user.layouts.app')
@section('title', 'Checkout - SoleStyle')
@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">Checkout</h1>
        <p class="text-slate-400 mt-2">Selesaikan pemesanan Anda</p>
    </div>
    <form id="checkout-form" method="POST" action="{{ route('checkout.process') }}">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Customer Info & Shipping -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Customer Information -->
                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl shadow-xl p-6">
                    <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <i class="fas fa-user text-purple-400 mr-3"></i>
                        Informasi Pembeli
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Nama Lengkap *</label>
                            <input type="text" name="customer_name" id="customer_name" required
                                  value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                                  class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white placeholder-slate-400 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all"
                                  placeholder="Masukkan nama lengkap">
                            @error('customer_name')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Email *</label>
                            <input type="email" name="customer_email" id="customer_email" required
                                  value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                                  class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white placeholder-slate-400 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all"
                                  placeholder="email@example.com">
                            @error('customer_email')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Nomor Telepon -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Nomor Telepon *</label>
                            <input type="tel" name="customer_phone" id="customer_phone" required
                                   value="{{ old('customer_phone', auth()->user()->phone ?? '') }}"
                                   class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white placeholder-slate-400 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all"
                                   placeholder="081234567890">
                            @error('customer_phone')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Shipping Address -->
                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl shadow-xl p-6">
                    <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <i class="fas fa-truck text-purple-400 mr-3"></i>
                        Alamat Pengiriman
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Alamat Lengkap *</label>
                            <textarea name="shipping_address" id="shipping_address" rows="3" required
                                      class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all"
                                      placeholder="Masukkan alamat lengkap">{{ old('shipping_address', auth()->user()->address ?? '') }}</textarea>
                            @error('shipping_address')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Kota</label>
                                <input type="text" name="city"
                                    value="{{ old('city', auth()->user()->city ?? '') }}"
                                    class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2 text-white">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Provinsi</label>
                                <input type="text" name="province"
                                     value="{{ old('province', auth()->user()->province ?? '') }}"
                                     class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2 text-white">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Kode Pos</label>
                                <input type="text" name="postal_code"
                                    value="{{ old('postal_code', auth()->user()->postal_code ?? '') }}"
                                    class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2 text-white">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Method -->
                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl shadow-xl p-6">
                    <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <i class="fas fa-credit-card text-purple-400 mr-3"></i>
                        Metode Pembayaran
                    </h3>
                    
                    <div class="space-y-3">
                        @php
                            $payments = [
                                ['value'=>'cod','icon'=>'fas fa-money-bill-wave','title'=>'Cash on Delivery (COD)','desc'=>'Bayar saat barang diterima','color'=>'text-green-400'],
                                ['value'=>'dana','icon'=>'fas fa-wallet','title'=>'DANA','desc'=>'E-wallet terpopuler di Indonesia','color'=>'text-blue-400'],
                                ['value'=>'ovo','icon'=>'fas fa-wallet','title'=>'OVO','desc'=>'E-wallet praktis dan mudah','color'=>'text-purple-400'],
                                ['value'=>'gopay','icon'=>'fas fa-mobile-alt','title'=>'GoPay','desc'=>'E-wallet dari Gojek','color'=>'text-green-400'],
                            ];
                        @endphp
                        
                        @foreach($payments as $payment)
                        <label class="flex items-center p-4 border border-slate-600 rounded-lg hover:border-purple-500/50 transition-all cursor-pointer">
                            <input type="radio" name="payment_method" value="{{ $payment['value'] }}" class="text-purple-600 mr-4">
                            <div class="flex items-center flex-1">
                                <i class="{{ $payment['icon'] }} {{ $payment['color'] }} mr-3"></i>
                                <div>
                                    <p class="text-white font-medium">{{ $payment['title'] }}</p>
                                    <p class="text-slate-400 text-sm">{{ $payment['desc'] }}</p>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl shadow-xl p-6 sticky top-24">
                    <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <i class="fas fa-receipt text-purple-400 mr-3"></i>
                        Ringkasan Pesanan
                    </h3>
                    
                    <!-- Order Items -->
                    <div class="space-y-4 mb-6 max-h-64 overflow-y-auto">
                        @foreach($checkoutItems as $item)
                        <div class="flex items-center gap-3 p-3 bg-slate-700/30 rounded-lg">
                            <input type="hidden" name="items[{{ $loop->index }}][cart_id]" value="{{ $item['cart_id'] }}">
                            <input type="hidden" name="items[{{ $loop->index }}][quantity]" value="{{ $item['quantity'] }}">
                            
                            <div class="w-12 h-12 flex-shrink-0 rounded-lg overflow-hidden">
                                @if($item['image_url'])
                                    <img src="{{ $item['image_url'] }}" alt="{{ $item['product']->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-slate-600 flex items-center justify-center">
                                        <i class="fas fa-image text-slate-400"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-white text-sm truncate">{{ $item['product']->name }}</h4>
                                @if($item['size_display'] !== 'N/A')
                                    <p class="text-xs text-slate-400">Size: {{ $item['size_display'] }}</p>
                                @endif
                                <p class="text-xs text-slate-400">Qty: {{ $item['quantity'] }}</p>
                            </div>
                            
                            <div class="text-right">
                                <p class="font-medium text-white text-sm">{{ $item['formatted_subtotal'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Price Breakdown -->
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-300">Subtotal ({{ $checkoutItems->count() }} item)</span>
                            <span class="text-white">{{ $formattedSubtotal }}</span>
                        </div>
                        
                        @if($promoDiscount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-300">Diskon ({{ $promoCode }})</span>
                            <span class="text-green-400">-{{ $formattedPromoDiscount }}</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-300">Ongkos Kirim</span>
                            <span class="text-green-400">{{ $formattedShipping }} <small>(Gratis)</small></span>
                        </div>
                        
                        <hr class="border-slate-600">
                        
                        <div class="flex justify-between">
                            <span class="font-bold text-white text-lg">Total</span>
                            <span class="font-bold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">{{ $formattedTotal }}</span>
                        </div>
                    </div>
                    
                    <!-- Order Notes -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-300 mb-2">Catatan Pesanan (Opsional)</label>
                        <textarea name="order_notes" rows="3"
                                  class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm placeholder-slate-400 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all"
                                  placeholder="Tulis catatan khusus untuk pesanan ini...">{{ old('order_notes') }}</textarea>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <button type="submit" id="place-order-btn"
                                class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white py-4 rounded-lg font-semibold transition-all transform hover:scale-105 flex items-center justify-center">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            <span id="order-btn-text">Buat Pesanan</span>
                        </button>
                        
                        <a href="{{ route('cart.index') }}" 
                           class="block w-full text-center py-3 border-2 border-slate-500/50 text-slate-300 hover:bg-slate-700/50 hover:border-slate-400 rounded-lg font-medium transition-all">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Keranjang
                        </a>
                    </div>
                    
                    <!-- Security Info -->
                    <div class="mt-6 pt-4 border-t border-slate-600">
                        <div class="flex justify-center items-center space-x-4 text-slate-400 text-xs">
                            <div class="flex items-center">
                                <i class="fas fa-shield-alt text-green-400 mr-1"></i>
                                <span>SSL Secure</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-lock text-green-400 mr-1"></i>
                                <span>Data Aman</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkout-form');
    const submitBtn = document.getElementById('place-order-btn');
    const btnText = document.getElementById('order-btn-text');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const requiredFields = ['customer_name', 'customer_email', 'customer_phone', 'shipping_address'];
        let isValid = true;
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                field.focus();
                field.classList.add('border-red-500');
                isValid = false;
                return false;
            } else {
                field.classList.remove('border-red-500');
            }
        });
        if (!isValid) {
            showNotification('Harap lengkapi semua field yang wajib diisi', 'error');
            return;
        }
        submitBtn.disabled = true;
        btnText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'Accept':'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Pesanan berhasil dibuat!', 'success');
                setTimeout(() => window.location.href = data.redirect_url || '/order/success', 1500);
            } else {
                throw new Error(data.message || 'Gagal memproses pesanan');
            }
        })
        .catch(error => {
            console.error(error);
            showNotification(error.message, 'error');
            submitBtn.disabled = false;
            btnText.innerHTML = '<i class="fas fa-shopping-cart mr-2"></i>Buat Pesanan';
        });
    });
    const phoneInput = document.getElementById('customer_phone');
    phoneInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g,'');
    });
});
function showNotification(message, type='info') {
    const colors = { success:'from-green-500 to-emerald-500', error:'from-red-500 to-pink-500', info:'from-blue-500 to-purple-500' };
    const icons = { success:'fa-check-circle', error:'fa-exclamation-circle', info:'fa-info-circle' };
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 bg-gradient-to-r ${colors[type]} text-white px-6 py-4 rounded-xl shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
    toast.innerHTML = `<div class="flex items-center space-x-3"><i class="fas ${icons[type]}"></i><span>${message}</span></div>`;
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.remove('translate-x-full'), 100);
    setTimeout(() => { toast.classList.add('translate-x-full'); setTimeout(()=>toast.remove(),300); }, 4000);
}
</script>
@endsection