@extends('user.layouts.app')

@section('title', 'Keranjang - SoleStyle')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Keranjang Belanja</h1>
        <p class="text-gray-600">Review produk sebelum checkout</p>
    </div>

    @if($cartItems->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="font-semibold mb-4">Item dalam Keranjang ({{ $cartItems->count() }})</h3>
                
                <div id="cart-items">
                    @foreach($cartItems as $item)
                    <div class="flex items-center gap-4 py-4 border-b" id="cart-item-{{ $item->id }}">
                        
                        <!-- Product Image -->
                        <div class="w-20 h-20 flex-shrink-0">
                            @if($item->product->image_url)
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" 
                                     class="w-full h-full object-cover rounded">
                            @else
                                <div class="w-full h-full bg-gray-200 rounded flex items-center justify-center">
                                    <span class="text-xs text-gray-400">No Image</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Product Info -->
                        <div class="flex-grow">
                            <h4 class="font-semibold">{{ $item->product->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $item->product->category->name ?? 'Uncategorized' }}</p>
                            @if($item->size)
                                <p class="text-sm text-gray-500">Ukuran: {{ $item->size }}</p>
                            @endif
                            <p class="font-bold text-blue-600">{{ $item->product->formatted_price }}</p>
                        </div>
                        
                        <!-- Quantity Controls -->
                        <div class="flex items-center gap-2">
                            <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" 
                                    class="bg-gray-200 w-8 h-8 rounded flex items-center justify-center">-</button>
                            
                            <input type="number" value="{{ $item->quantity }}" min="1" 
                                   class="w-12 text-center border rounded" 
                                   onchange="updateQuantity({{ $item->id }}, this.value)"
                                   id="qty-{{ $item->id }}">
                            
                            <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" 
                                    class="bg-gray-200 w-8 h-8 rounded flex items-center justify-center">+</button>
                        </div>
                        
                        <!-- Subtotal & Remove -->
                        <div class="text-right">
                            <p class="font-bold" id="subtotal-{{ $item->id }}">{{ $item->formatted_subtotal }}</p>
                            <button onclick="removeItem({{ $item->id }})" 
                                    class="text-red-500 text-sm hover:underline mt-2">
                                Hapus
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-4">
                    <button onclick="clearCart()" class="text-red-500 hover:underline">
                        Kosongkan Keranjang
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-4 sticky top-4">
                <h3 class="font-semibold mb-4">Ringkasan Pesanan</h3>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span id="order-total">{{ $formattedTotal }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Ongkos Kirim</span>
                        <span>Gratis</span>
                    </div>
                    <hr>
                    <div class="flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span id="final-total">{{ $formattedTotal }}</span>
                    </div>
                </div>
                
                <button onclick="checkout()" class="w-full bg-blue-600 text-white py-3 rounded font-semibold hover:bg-blue-700">
                    Lanjut ke Checkout
                </button>
                
                <a href="{{ url('produk') }}" class="block text-center mt-3 text-blue-600 hover:underline">
                    Lanjut Belanja
                </a>
            </div>
        </div>
    </div>
    
    @else
    <!-- Empty Cart -->
    <div class="text-center py-12">
        <div class="mb-4">
            <svg class="mx-auto w-24 h-24 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
            </svg>
        </div>
        <h3 class="text-xl font-semibold mb-2">Keranjang Kosong</h3>
        <p class="text-gray-500 mb-4">Belum ada produk di keranjang Anda</p>
        <a href="{{ url('produk') }}" class="bg-blue-600 text-white px-6 py-2 rounded">
            Mulai Belanja
        </a>
    </div>
    @endif
</div>

<script>
// Update quantity
function updateQuantity(cartId, newQuantity) {
    if (newQuantity < 1) {
        removeItem(cartId);
        return;
    }
    
    fetch(`/cart/update/${cartId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ quantity: newQuantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`qty-${cartId}`).value = newQuantity;
            document.getElementById(`subtotal-${cartId}`).textContent = 'Rp ' + data.subtotal;
            document.getElementById('order-total').textContent = 'Rp ' + data.total;
            document.getElementById('final-total').textContent = 'Rp ' + data.total;
            updateCartCount(data.cart_count);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate keranjang');
    });
}

// Remove item
function removeItem(cartId) {
    if (!confirm('Yakin ingin menghapus item ini?')) return;
    
    fetch(`/cart/remove/${cartId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`cart-item-${cartId}`).remove();
            document.getElementById('order-total').textContent = 'Rp ' + data.total;
            document.getElementById('final-total').textContent = 'Rp ' + data.total;
            updateCartCount(data.cart_count);
            
            // Reload page if cart empty
            if (data.cart_count === 0) {
                location.reload();
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus item');
    });
}

// Clear cart
function clearCart() {
    if (!confirm('Yakin ingin mengosongkan keranjang?')) return;
    
    fetch('/cart/clear', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
}

// Checkout (placeholder)
function checkout() {
    alert('Mengarahkan ke halaman checkout...');
    // window.location.href = '/checkout';
}

// Update cart count di navbar
function updateCartCount(count) {
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
    }
}
</script>

@endsection