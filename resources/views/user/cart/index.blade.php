@extends('user.layouts.app')
@section('title', 'Keranjang - SoleStyle')
@section('content')
<div class="container mx-auto px-4 py-6">
    
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">Keranjang Belanja</h1>
        <p class="text-slate-400 mt-2">Review produk sebelum checkout</p>
    </div>
    @if($cartItems->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl shadow-xl p-6">
                
                <!-- Header with Select All -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="select-all" 
                                   class="w-5 h-5 text-purple-600 bg-slate-700 border-slate-600 rounded focus:ring-purple-500 focus:ring-2 transition-all"
                                   onchange="toggleSelectAll()">
                            <label for="select-all" class="ml-3 text-white font-semibold">Pilih Semua</label>
                        </div>
                        <h3 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-shopping-cart text-purple-400 mr-3"></i>
                            Item dalam Keranjang (<span id="total-items">{{ $cartItems->count() }}</span>)
                        </h3>
                    </div>
                    <button onclick="clearCart()" class="text-red-400 hover:text-red-300 text-sm flex items-center transition-colors">
                        <i class="fas fa-trash mr-1"></i>Kosongkan Keranjang
                    </button>
                </div>
                
                <!-- Selection Info Bar -->
                <div id="selection-info" class="bg-purple-900/30 border border-purple-500/30 rounded-lg p-4 mb-4 hidden">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-check-circle text-purple-400"></i>
                            <span class="text-white">
                                <span id="selected-count">0</span> item dipilih
                            </span>
                        </div>
                        <button onclick="deselectAll()" class="text-purple-400 hover:text-purple-300 text-sm">
                            Batal Pilih
                        </button>
                    </div>
                </div>
                
                <div id="cart-items" class="space-y-4">
                    @foreach($cartItems as $item)
                    <div class="bg-slate-700/30 border border-slate-600 rounded-lg p-4 hover:border-purple-500/50 transition-all duration-300 cart-item" 
                         id="cart-item-{{ $item->id }}" data-item-id="{{ $item->id }}">
                        <div class="flex items-center gap-4">
                            
                            <!-- Checkbox -->
                            <div class="flex-shrink-0">
                                <input type="checkbox" 
                                       class="item-checkbox w-5 h-5 text-purple-600 bg-slate-700 border-slate-600 rounded focus:ring-purple-500 focus:ring-2 transition-all"
                                       data-item-id="{{ $item->id }}"
                                       data-price="{{ $item->product->price }}"
                                       data-quantity="{{ $item->quantity }}"
                                       onchange="updateSelection()">
                            </div>
                            
                            <!-- Product Image -->
                            <div class="w-20 h-20 flex-shrink-0 relative overflow-hidden rounded-lg">
                                @if($item->product->image_url)
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" 
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full bg-slate-600 rounded-lg flex items-center justify-center">
                                        <div class="text-center text-slate-400">
                                            <i class="fas fa-image text-lg mb-1"></i>
                                            <p class="text-xs">No Image</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Product Info -->
                            <div class="flex-grow min-w-0">
                                <h4 class="font-semibold text-white text-lg truncate">{{ $item->product->name }}</h4>
                                <p class="text-sm text-slate-400 mb-1">{{ $item->product->category->name ?? 'Uncategorized' }}</p>
                                
                                <!-- Size Information -->
                                @if($item->size_display && $item->size_display !== 'N/A')
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-shoe-prints text-purple-400 text-xs mr-2"></i>
                                        <span class="text-sm text-slate-300">Ukuran: {{ $item->size_display }}</span>
                                        @if(isset($item->size_stock))
                                            <span class="text-xs text-slate-500 ml-2">(Stok: {{ $item->size_stock }})</span>
                                        @endif
                                    </div>
                                @endif
                                
                                <p class="font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 text-lg">{{ $item->product->formatted_price }}</p>
                                
                                <!-- Stock warning -->
                                @if(isset($item->size_stock) && $item->size_stock <= 5 && $item->size_stock > 0)
                                <div class="flex items-center mt-2">
                                    <i class="fas fa-exclamation-triangle text-yellow-400 text-xs mr-1"></i>
                                    <span class="text-yellow-400 text-xs">Stok terbatas: {{ $item->size_stock }} tersisa</span>
                                </div>
                                @elseif(isset($item->size_stock) && $item->size_stock <= 0)
                                <div class="flex items-center mt-2">
                                    <i class="fas fa-times-circle text-red-400 text-xs mr-1"></i>
                                    <span class="text-red-400 text-xs">Stok habis untuk ukuran ini</span>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Quantity Controls -->
                            <div class="flex items-center gap-3 bg-slate-800/50 rounded-lg p-2">
                                <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" 
                                        class="w-8 h-8 bg-slate-600 hover:bg-slate-500 rounded-lg flex items-center justify-center text-white transition-colors"
                                        {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                
                                <input type="number" value="{{ $item->quantity }}" min="1" 
                                       max="{{ $item->size_stock ?? $item->product->stock }}"
                                       class="w-16 text-center bg-slate-700 border border-slate-600 rounded-lg py-2 text-white focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all" 
                                       onchange="updateQuantity({{ $item->id }}, this.value)"
                                       id="qty-{{ $item->id }}">
                                
                                <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" 
                                        class="w-8 h-8 bg-slate-600 hover:bg-slate-500 rounded-lg flex items-center justify-center text-white transition-colors"
                                        {{ $item->quantity >= ($item->size_stock ?? $item->product->stock) ? 'disabled' : '' }}>
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>
                            
                            <!-- Subtotal & Actions -->
                            <div class="text-right flex-shrink-0">
                                <p class="font-bold text-xl text-white mb-2" id="subtotal-{{ $item->id }}">{{ $item->formatted_subtotal }}</p>
                                
                                <!-- Action button -->
                                <div class="flex flex-col">
                                    <button onclick="removeItem({{ $item->id }})" 
                                            class="text-red-400 hover:text-red-300 text-sm flex items-center justify-end transition-colors">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Warning if quantity exceeds stock -->
                        @if($item->quantity > ($item->size_stock ?? $item->product->stock))
                        <div class="mt-4 p-3 bg-red-900/20 border border-red-500/30 rounded-lg">
                            <div class="flex items-center text-red-400">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span class="text-sm">Quantity melebihi stok tersedia!</span>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                
                <!-- Bulk Actions -->
                <div id="bulk-actions" class="mt-6 pt-6 border-t border-slate-600 hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-300">Aksi untuk item terpilih:</span>
                        <div>
                            <button onclick="bulkRemove()" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm flex items-center transition-colors">
                                <i class="fas fa-trash mr-2"></i>Hapus Terpilih
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl shadow-xl p-6 sticky top-24">
                <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                    <i class="fas fa-receipt text-purple-400 mr-3"></i>
                    Ringkasan Pesanan
                </h3>
                
                <!-- Selected items info -->
                <div class="mb-4 p-3 bg-purple-900/20 border border-purple-500/30 rounded-lg">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-300">Item dipilih:</span>
                        <span class="text-purple-400 font-semibold" id="selected-items-count">0</span>
                    </div>
                </div>
                
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-slate-300">Subtotal</span>
                        <span class="text-white font-semibold text-lg" id="selected-total">Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-slate-300">Ongkos Kirim</span>
                        <span class="text-green-400 font-medium flex items-center">
                            <i class="fas fa-check-circle mr-1"></i>Gratis
                        </span>
                    </div>
                    
                    <hr class="border-slate-600">
                    
                    <div class="flex justify-between items-center py-3">
                        <span class="text-white font-bold text-lg">Total</span>
                        <span class="font-bold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400" id="final-total">Rp 0</span>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <button onclick="checkout()" id="checkout-btn" 
                            class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white py-4 rounded-lg font-semibold transition-all transform hover:scale-105 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                            disabled>
                        <i class="fas fa-credit-card mr-2"></i>
                        <span id="checkout-text">Pilih item untuk checkout</span>
                    </button>
                    
                    <a href="{{ url('produk') }}" class="block w-full text-center py-3 border-2 border-purple-500/50 text-purple-400 hover:bg-purple-500/10 hover:border-purple-400 rounded-lg font-medium transition-all">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Lanjut Belanja
                    </a>
                </div>
                
                <!-- Security badges -->
                <div class="mt-6 pt-6 border-t border-slate-600">
                    <div class="flex justify-center items-center space-x-4 text-slate-400 text-sm">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-green-400 mr-1"></i>
                            <span>Aman</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-lock text-green-400 mr-1"></i>
                            <span>SSL</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-truck text-green-400 mr-1"></i>
                            <span>Gratis Ongkir</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @else
    <!-- Empty Cart -->
    <div class="text-center py-20">
        <div class="max-w-md mx-auto">
            <div class="w-32 h-32 bg-slate-800/50 rounded-full flex items-center justify-center mx-auto mb-8">
                <i class="fas fa-shopping-cart text-6xl text-slate-400"></i>
            </div>
            <h3 class="text-3xl font-bold text-white mb-4">Keranjang Kosong</h3>
            <p class="text-slate-400 mb-8 text-lg">Belum ada produk di keranjang Anda. Yuk, mulai belanja sekarang!</p>
            
            <!-- Suggested actions -->
            <div class="space-y-4">
                <a href="{{ url('produk') }}" class="inline-flex items-center bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all transform hover:scale-105">
                    <i class="fas fa-shopping-bag mr-3"></i>
                    Mulai Belanja
                </a>
                
                <div class="flex justify-center space-x-6 text-sm">
                    <a href="{{ url('produk?category[]=sneakers') }}" class="text-purple-400 hover:text-purple-300 transition-colors flex items-center">
                        <i class="fas fa-running mr-2"></i>Sneakers
                    </a>
                    <a href="{{ url('produk?category[]=formal') }}" class="text-purple-400 hover:text-purple-300 transition-colors flex items-center">
                        <i class="fas fa-user-tie mr-2"></i>Formal
                    </a>
                    <a href="{{ url('produk?category[]=casual') }}" class="text-purple-400 hover:text-purple-300 transition-colors flex items-center">
                        <i class="fas fa-walking mr-2"></i>Casual
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<script>
// Global variables
let selectedItems = new Set();
// Toast notification function
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
    
    // Slide in
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // Slide out and remove
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
            }
        }, 300);
    }, 3000);
}
// Toggle select all checkbox
function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('select-all');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    
    itemCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
        if (selectAllCheckbox.checked) {
            selectedItems.add(checkbox.dataset.itemId);
        } else {
            selectedItems.delete(checkbox.dataset.itemId);
        }
    });
    
    updateSelection();
}
// Deselect all items
function deselectAll() {
    selectedItems.clear();
    document.getElementById('select-all').checked = false;
    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateSelection();
}
// Update selection state and UI
function updateSelection() {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const checkedCheckboxes = document.querySelectorAll('.item-checkbox:checked');
    const selectionInfo = document.getElementById('selection-info');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const selectedItemsCount = document.getElementById('selected-items-count');
    
    // Update selected items set
    selectedItems.clear();
    checkedCheckboxes.forEach(checkbox => {
        selectedItems.add(checkbox.dataset.itemId);
    });
    
    // Update select all checkbox state
    const selectAllCheckbox = document.getElementById('select-all');
    if (checkedCheckboxes.length === 0) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = false;
    } else if (checkedCheckboxes.length === checkboxes.length) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = true;
    } else {
        selectAllCheckbox.indeterminate = true;
        selectAllCheckbox.checked = false;
    }
    
    // Show/hide selection info
    if (checkedCheckboxes.length > 0) {
        selectionInfo.classList.remove('hidden');
        bulkActions.classList.remove('hidden');
        selectedCount.textContent = checkedCheckboxes.length;
        selectedItemsCount.textContent = checkedCheckboxes.length;
    } else {
        selectionInfo.classList.add('hidden');
        bulkActions.classList.add('hidden');
    }
    
    // Update order summary
    updateOrderSummary();
}
// Calculate and update order summary based on selected items
function updateOrderSummary() {
    const checkedCheckboxes = document.querySelectorAll('.item-checkbox:checked');
    let subtotal = 0;
    
    checkedCheckboxes.forEach(checkbox => {
        const price = parseFloat(checkbox.dataset.price);
        const quantity = parseInt(checkbox.dataset.quantity);
        subtotal += price * quantity;
    });
    
    const total = subtotal; // No tax, discount, or additional fees
    
    // Update UI
    document.getElementById('selected-total').textContent = formatPrice(subtotal);
    document.getElementById('final-total').textContent = formatPrice(total);
    
    // Enable/disable checkout button
    const checkoutBtn = document.getElementById('checkout-btn');
    const checkoutText = document.getElementById('checkout-text');
    
    if (checkedCheckboxes.length > 0) {
        checkoutBtn.disabled = false;
        checkoutText.textContent = `Checkout (${checkedCheckboxes.length} item)`;
        checkoutBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
        checkoutBtn.disabled = true;
        checkoutText.textContent = 'Pilih item untuk checkout';
        checkoutBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }
}
// Format price to Indonesian Rupiah
function formatPrice(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount).replace('IDR', 'Rp');
}
// Update quantity with animation
function updateQuantity(cartId, newQuantity) {
    if (newQuantity < 1) {
        removeItem(cartId);
        return;
    }
    
    fetch(`/cart/update/${cartId}`, {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ quantity: newQuantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update quantity input
            document.getElementById(`qty-${cartId}`).value = newQuantity;
            
            // Update checkbox data
            const checkbox = document.querySelector(`[data-item-id="${cartId}"]`);
            if (checkbox) {
                checkbox.dataset.quantity = newQuantity;
            }
            
            // Update subtotal with animation
            const subtotalElement = document.getElementById(`subtotal-${cartId}`);
            subtotalElement.style.transform = 'scale(1.1)';
            subtotalElement.style.color = '#8b5cf6';
            subtotalElement.textContent = 'Rp ' + data.subtotal;
            
            setTimeout(() => {
                subtotalElement.style.transform = 'scale(1)';
                subtotalElement.style.color = '';
            }, 300);
            
            updateCartCount(data.cart_count);
            updateSelection(); // Recalculate selected totals
            showNotification('Jumlah produk berhasil diperbarui', 'success');
        } else {
            showNotification(data.message || 'Gagal memperbarui jumlah', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat mengupdate keranjang', 'error');
    });
}
// Remove item with confirmation
function removeItem(cartId) {
    if (!confirm('Yakin ingin menghapus item ini dari keranjang?')) return;
    
    fetch(`/cart/remove/${cartId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove from selected items
            selectedItems.delete(cartId.toString());
            
            // Animate removal
            const itemElement = document.getElementById(`cart-item-${cartId}`);
            itemElement.style.transform = 'translateX(-100%)';
            itemElement.style.opacity = '0';
            
            setTimeout(() => {
                itemElement.remove();
                updateSelection();
            }, 300);
            
            updateCartCount(data.cart_count);
            showNotification('Produk berhasil dihapus dari keranjang', 'success');
            
            // Reload page if cart empty
            if (data.cart_count === 0) {
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        } else {
            showNotification(data.message || 'Gagal menghapus item', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat menghapus item', 'error');
    });
}
// Bulk remove selected items
function bulkRemove() {
    if (selectedItems.size === 0) return;
    
    if (!confirm(`Yakin ingin menghapus ${selectedItems.size} item dari keranjang?`)) return;
    
    const promises = Array.from(selectedItems).map(itemId => {
        return fetch(`/cart/remove/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
    });
    
    Promise.all(promises)
        .then(responses => Promise.all(responses.map(r => r.json())))
        .then(results => {
            // Remove items from DOM
            selectedItems.forEach(itemId => {
                const itemElement = document.getElementById(`cart-item-${itemId}`);
                if (itemElement) {
                    itemElement.style.transform = 'translateX(-100%)';
                    itemElement.style.opacity = '0';
                    setTimeout(() => itemElement.remove(), 300);
                }
            });
            
            selectedItems.clear();
            setTimeout(() => {
                updateSelection();
                updateCartCount(results[results.length - 1].cart_count);
            }, 300);
            
            showNotification('Item terpilih berhasil dihapus', 'success');
            
            // Reload if cart empty
            if (results[results.length - 1].cart_count === 0) {
                setTimeout(() => location.reload(), 1000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat menghapus item', 'error');
        });
}
// Clear entire cart
function clearCart() {
    if (!confirm('Yakin ingin mengosongkan seluruh keranjang belanja?')) return;
    
    fetch('/cart/clear', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Keranjang berhasil dikosongkan', 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showNotification('Gagal mengosongkan keranjang', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan', 'error');
    });
}
// Checkout function - Simple and reliable version
function checkout() {
    if (selectedItems.size === 0) {
        showNotification('Pilih minimal satu item untuk checkout', 'warning');
        return;
    }
    
    const checkoutBtn = document.getElementById('checkout-btn');
    const checkoutText = document.getElementById('checkout-text');
    const originalText = checkoutText.textContent;
    
    // Show loading state
    checkoutBtn.disabled = true;
    checkoutText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
    
    try {
        // Create form for submission
        const form = document.createElement('form');
        form.method = 'get';
        form.action = '/checkout';
        form.style.display = 'none';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
        form.appendChild(csrfToken);
        
        // Add selected items
        const selectedItemsArray = Array.from(selectedItems);
        selectedItemsArray.forEach((itemId, index) => {
            const checkbox = document.querySelector(`[data-item-id="${itemId}"]`);
            
            // Item ID
            const itemIdInput = document.createElement('input');
            itemIdInput.type = 'hidden';
            itemIdInput.name = `selected_items[${index}][id]`;
            itemIdInput.value = itemId;
            form.appendChild(itemIdInput);
            
            // Item quantity
            const itemQtyInput = document.createElement('input');
            itemQtyInput.type = 'hidden';
            itemQtyInput.name = `selected_items[${index}][quantity]`;
            itemQtyInput.value = checkbox.dataset.quantity;
            form.appendChild(itemQtyInput);
        });
        
        // Submit form
        document.body.appendChild(form);
        showNotification('Mengarahkan ke halaman checkout...', 'info');
        
        setTimeout(() => {
            form.submit();
        }, 800);
        
    } catch (error) {
        console.error('Checkout error:', error);
        showNotification('Terjadi kesalahan saat memproses checkout', 'error');
        
        // Reset button state
        checkoutBtn.disabled = false;
        checkoutText.textContent = originalText;
    }
}

// Helper function to redirect to checkout with form submission
function redirectToCheckout(checkoutItems) {
    try {
        // Create a temporary form to submit checkout data
        const form = document.createElement('form');
        form.method = 'get';
        form.action = '/checkout';
        form.style.display = 'none';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
        form.appendChild(csrfToken);
        
        // Add selected items
        checkoutItems.forEach((item, index) => {
            const itemIdInput = document.createElement('input');
            itemIdInput.type = 'hidden';
            itemIdInput.name = `selected_items[${index}][id]`;
            itemIdInput.value = item.id;
            form.appendChild(itemIdInput);
            
            const itemQtyInput = document.createElement('input');
            itemQtyInput.type = 'hidden';
            itemQtyInput.name = `selected_items[${index}][quantity]`;
            itemQtyInput.value = item.quantity;
            form.appendChild(itemQtyInput);
        });
        
        // Submit form
        document.body.appendChild(form);
        showNotification('Mengarahkan ke halaman checkout...', 'info');
        
        setTimeout(() => {
            form.submit();
        }, 500);
        
    } catch (error) {
        console.error('Redirect error:', error);
        showNotification('Terjadi kesalahan saat mengarahkan ke checkout', 'error');
        
        // Reset button state
        const checkoutBtn = document.getElementById('checkout-btn');
        const checkoutText = document.getElementById('checkout-text');
        checkoutBtn.disabled = false;
        checkoutText.textContent = `Checkout (${selectedItems.size} item)`;
    }
}

// Alternative: Simple redirect approach (most reliable)
function checkoutSimple() {
    if (selectedItems.size === 0) {
        showNotification('Pilih minimal satu item untuk checkout', 'warning');
        return;
    }
    
    // Store selected items in sessionStorage for checkout page
    const checkoutData = {
        selectedItems: Array.from(selectedItems),
        timestamp: Date.now()
    };
    
    try {
        sessionStorage.setItem('checkout_data', JSON.stringify(checkoutData));
        showNotification('Mengarahkan ke halaman checkout...', 'info');
        
        setTimeout(() => {
            window.location.href = '/checkout';
        }, 800);
    } catch (error) {
        console.error('SessionStorage error:', error);
        // Fallback to simple redirect
        window.location.href = '/checkout';
    }
}
// Update cart count in navbar
function updateCartCount(count) {
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
        
        // Animation
        cartCountElement.style.transform = 'scale(1.3)';
        setTimeout(() => {
            cartCountElement.style.transform = 'scale(1)';
        }, 200);
    }
    
    // Update total items count
    const totalItemsElement = document.getElementById('total-items');
    if (totalItemsElement) {
        totalItemsElement.textContent = count;
    }
}
// Prevent negative quantity input
document.addEventListener('input', function(e) {
    if (e.target.type === 'number' && e.target.value < 1) {
        e.target.value = 1;
    }
});
// Auto-save quantity changes after typing stops
let timeouts = {};
document.addEventListener('input', function(e) {
    if (e.target.type === 'number' && e.target.id.startsWith('qty-')) {
        const cartId = e.target.id.replace('qty-', '');
        
        // Clear existing timeout
        if (timeouts[cartId]) {
            clearTimeout(timeouts[cartId]);
        }
        
        // Set new timeout
        timeouts[cartId] = setTimeout(() => {
            updateQuantity(cartId, e.target.value);
        }, 1000);
    }
});
// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to checkboxes
    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelection);
    });
    
    // Initialize selection state
    updateSelection();
});
// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + A to select all
    if ((e.ctrlKey || e.metaKey) && e.key === 'a' && e.target.tagName !== 'INPUT') {
        e.preventDefault();
        document.getElementById('select-all').checked = true;
        toggleSelectAll();
    }
    
    // Delete key to remove selected items
    if (e.key === 'Delete' && selectedItems.size > 0 && e.target.tagName !== 'INPUT') {
        e.preventDefault();
        bulkRemove();
    }
});
// Handle page visibility change
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        // Optionally refresh cart data when page becomes visible
    }
});
console.log('Cart page initialized');
</script>
@endsection