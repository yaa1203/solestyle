@extends('user.layouts.app')
@section('title', $product->name . ' - SoleStyle')
@section('content')
<div class="container mx-auto px-4 py-6">
  <!-- Breadcrumb -->
  <nav class="mb-6 text-sm" aria-label="breadcrumb">
    <ol class="flex space-x-2">
      <li><a href="{{ url('produk') }}" class="text-slate-400 hover:text-white">Koleksi</a></li>
      <li><span class="text-slate-500">/</span></li>
      <li><a href="{{ url('produk?category[]=' . $product->category->name) }}" class="text-slate-400 hover:text-white">{{ $product->category->name }}</a></li>
      <li><span class="text-slate-500">/</span></li>
      <li class="text-white">{{ $product->name }}</li>
    </ol>
  </nav>
  
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Product Image -->
    <div>
      <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl overflow-hidden">
        @if($product->image_exists)
          <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-96 object-cover">
        @else
          <div class="w-full h-96 bg-slate-700/50 flex items-center justify-center">
            <div class="text-center text-slate-400">
              <i class="fas fa-image text-6xl mb-2"></i>
              <p class="text-sm">No Image</p>
            </div>
          </div>
        @endif
      </div>
    </div>
    
    <!-- Product Details -->
    <div>
      <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6">
        <div class="flex justify-between items-start mb-4">
          <div>
            @if(isset($product->brand))
              <span class="text-purple-400 text-sm font-semibold uppercase tracking-wide">{{ $product->brand }}</span>
            @endif
            <h1 class="text-2xl font-bold text-white mt-1">{{ $product->name }}</h1>
          </div>
          
          <!-- Wishlist Button -->
          <button onclick="toggleWishlist({{ $product->id }})" class="w-10 h-10 bg-slate-700 rounded-full flex items-center justify-center text-slate-300 hover:text-red-400 hover:bg-slate-600 transition-all">
            <i class="fas fa-heart"></i>
          </button>
        </div>
        
        <p class="text-slate-400 mb-6">{{ $product->category->name }}</p>
        
        <div class="mb-6">
          <span class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">{{ $product->formatted_price }}</span>
        </div>
        
        <!-- Size Selection -->
        <div class="mb-6">
          <h3 class="font-semibold text-white mb-3">Pilih Ukuran</h3>
          @if($product->sizes->count() > 0)
            <div class="grid grid-cols-4 gap-3">
              @foreach($product->sizes as $size)
                <div class="text-center">
                  <div onclick="selectSize('{{ $size->id }}', '{{ $size->size }}', {{ $size->stock }})" 
                       class="size-option py-3 px-4 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-300 text-sm font-medium transition-all
                          {{ $size->stock > 0 ? 'hover:border-purple-500 cursor-pointer' : 'opacity-50 cursor-not-allowed' }}"
                       data-size-id="{{ $size->id }}"
                       data-size="{{ $size->size }}"
                       data-stock="{{ $size->stock }}">
                    {{ $size->size }}
                  </div>
                  <span class="text-xs text-slate-400 block mt-1">
                    @if($size->stock > 0)
                      Stok: {{ $size->stock }}
                    @else
                      <span class="text-red-400">Habis</span>
                    @endif
                  </span>
                </div>
              @endforeach
            </div>
          @else
            <div class="text-slate-400 text-sm">
              <i class="fas fa-info-circle mr-2"></i>
              Belum ada ukuran tersedia untuk produk ini
            </div>
          @endif
          <p id="size-error" class="text-red-400 text-sm mt-2 hidden">Silakan pilih ukuran terlebih dahulu</p>
        </div>
        
        <!-- Quantity Selector -->
        @if($product->total_stock > 0)
        <div class="mb-6">
          <h3 class="font-semibold text-white mb-3">Jumlah</h3>
          <div class="flex items-center gap-3">
            <button onclick="decreaseQuantity()" class="w-10 h-10 bg-slate-700 rounded-lg flex items-center justify-center text-white hover:bg-slate-600 transition-all" disabled id="decrease-btn">
              <i class="fas fa-minus"></i>
            </button>
            <input type="number" id="quantity-input" value="1" min="1" max="1"
                   class="w-20 h-10 bg-slate-700 rounded-lg text-white text-center focus:outline-none focus:ring-2 focus:ring-purple-500"
                   onchange="validateQuantity()" oninput="validateQuantity()" disabled>
            <button onclick="increaseQuantity()" class="w-10 h-10 bg-slate-700 rounded-lg flex items-center justify-center text-white hover:bg-slate-600 transition-all" disabled id="increase-btn">
              <i class="fas fa-plus"></i>
            </button>
          </div>
          <p class="text-xs text-slate-400 mt-1">Maksimal: <span id="max-quantity">0</span> item</p>
        </div>
        @endif
        
        <!-- Stock Status -->
        <div class="mb-6">
          @if($product->total_stock > 0)
            @if($product->total_stock <= 5)
              <div class="flex items-center p-3 bg-yellow-900/20 border border-yellow-500/30 rounded-lg">
                <i class="fas fa-exclamation-triangle text-yellow-400 mr-2"></i>
                <span class="text-yellow-400 text-sm">Stok terbatas! Hanya {{ $product->total_stock }} item tersisa</span>
              </div>
            @else
              <div class="flex items-center p-3 bg-green-900/20 border border-green-500/30 rounded-lg">
                <i class="fas fa-check-circle text-green-400 mr-2"></i>
                <span class="text-green-400 text-sm">Stok tersedia</span>
              </div>
            @endif
          @else
            <div class="flex items-center p-3 bg-red-900/20 border border-red-500/30 rounded-lg">
              <i class="fas fa-times-circle text-red-400 mr-2"></i>
              <span class="text-red-400 text-sm">Stok habis untuk semua ukuran</span>
            </div>
          @endif
        </div>
        
        <!-- Action Buttons -->
        <div class="flex gap-3 mb-6">
          @if($product->total_stock > 0)
            <button onclick="addToCart()" 
                    id="add-to-cart-btn"
                    class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white py-3 rounded-lg font-medium transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                    disabled>
              <i class="fas fa-shopping-cart mr-2"></i>
              <span id="add-to-cart-text">Pilih ukuran terlebih dahulu</span>
            </button>
          @else
            <button class="flex-1 bg-slate-700 text-slate-400 py-3 rounded-lg font-medium cursor-not-allowed">
              <i class="fas fa-ban mr-2"></i>Stok Habis
            </button>
          @endif
          
          <button onclick="buyNow()" id="buy-now-btn" class="flex-1 bg-slate-700 hover:bg-slate-600 text-white py-3 rounded-lg font-medium transition-all disabled:opacity-50 disabled:cursor-not-allowed" disabled>
            <i class="fas fa-bolt mr-2"></i>Beli Sekarang
          </button>
        </div>
        
        <!-- Product Info -->
        <div class="space-y-3 text-sm text-slate-300">
          <div class="flex items-center justify-between py-2 border-b border-slate-600">
            <span>SKU</span>
            <span class="text-slate-400">{{ $product->sku }}</span>
          </div>
          <div class="flex items-center justify-between py-2 border-b border-slate-600">
            <span>Kategori</span>
            <span class="text-slate-400">{{ $product->category->name }}</span>
          </div>
          <div class="flex items-center justify-between py-2 border-b border-slate-600">
            <span>Total Stok</span>
            <span class="text-slate-400">{{ $product->total_stock }} item</span>
          </div>
          <div class="flex items-center justify-between py-2">
            <span>Status</span>
            <span class="text-green-400">{{ $product->status_text }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Description -->
  <div class="mt-8">
    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6">
      <h3 class="font-semibold text-white mb-4">Deskripsi Produk</h3>
      <p class="text-slate-300">{{ $product->description ?? 'Tidak ada deskripsi tersedia.' }}</p>
    </div>
  </div>
  
  <!-- Related Products -->
  @if(isset($relatedProducts) && $relatedProducts->count() > 0)
  <div class="mt-12">
    <h2 class="text-2xl font-bold text-white mb-6">Produk Terkait</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      @foreach($relatedProducts as $relatedProduct)
      <a href="{{ route('produk.show', $relatedProduct) }}" class="block">
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl shadow-xl overflow-hidden group hover:border-purple-500/50 transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
          <div class="relative overflow-hidden">
            @if($relatedProduct->image_exists)
              <img src="{{ $relatedProduct->image_url }}" alt="{{ $relatedProduct->name }}" 
                   class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
            @else
              <div class="w-full h-48 bg-slate-700/50 flex items-center justify-center">
                <div class="text-center text-slate-400">
                  <i class="fas fa-image text-4xl mb-2"></i>
                  <p class="text-sm">No Image</p>
                </div>
              </div>
            @endif
            
            @if($relatedProduct->total_stock <= 5 && $relatedProduct->total_stock > 0)
              <span class="absolute top-3 left-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-xs px-3 py-1 rounded-full font-medium">
                <i class="fas fa-exclamation-triangle mr-1"></i>Stok Terbatas
              </span>
            @elseif($relatedProduct->total_stock == 0)
              <span class="absolute top-3 left-3 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs px-3 py-1 rounded-full font-medium">
                <i class="fas fa-times-circle mr-1"></i>Habis
              </span>
            @endif
          </div>
          
          <div class="p-4">
            <h3 class="font-bold text-lg text-white mb-1 group-hover:text-purple-300 transition-colors">{{ $relatedProduct->name }}</h3>
            <p class="text-slate-400 text-sm mb-3">{{ $relatedProduct->category->name ?? 'Uncategorized' }}</p>
            
            <div class="flex justify-between items-center">
              <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 font-bold">{{ $relatedProduct->formatted_price }}</span>
              @if($relatedProduct->total_stock > 0)
                <span class="text-green-400 text-sm bg-green-400/10 px-2 py-1 rounded-full">
                  <i class="fas fa-check-circle mr-1"></i>Stok: {{ $relatedProduct->total_stock }}
                </span>
              @endif
            </div>
          </div>
        </div>
      </a>
      @endforeach
    </div>
  </div>
  @endif
</div>

<!-- Loading Modal -->
<div id="loading-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center">
  <div class="bg-slate-800 rounded-xl p-6 text-center">
    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-500 mx-auto mb-4"></div>
    <p class="text-white">Menambahkan ke keranjang...</p>
  </div>
</div>

@endsection

@section('scripts')
<script>
// Global variables
let selectedSize = null;
let selectedSizeId = null;
let maxStock = 0;
const productId = {{ $product->id }};
const csrfToken = '{{ csrf_token() }}';

// Size selection
function selectSize(sizeId, sizeName, stock) {
  if (stock <= 0) {
    showNotification('Ukuran ini sedang habis', 'warning');
    return;
  }
  
  // Remove previous selection
  document.querySelectorAll('.size-option').forEach(option => {
    option.classList.remove('border-purple-500', 'bg-purple-500/20', 'text-purple-300');
  });
  
  // Add selection to clicked size
  const clickedOption = document.querySelector(`[data-size-id="${sizeId}"]`);
  clickedOption.classList.add('border-purple-500', 'bg-purple-500/20', 'text-purple-300');
  
  // Update global variables
  selectedSize = sizeName;
  selectedSizeId = sizeId;
  maxStock = stock;
  
  // Update quantity input
  const quantityInput = document.getElementById('quantity-input');
  quantityInput.max = stock;
  quantityInput.value = Math.min(parseInt(quantityInput.value), stock);
  quantityInput.disabled = false;
  
  // Enable quantity buttons
  document.getElementById('decrease-btn').disabled = false;
  document.getElementById('increase-btn').disabled = false;
  
  // Update max quantity display
  document.getElementById('max-quantity').textContent = stock;
  
  // Hide error message
  document.getElementById('size-error').classList.add('hidden');
  
  // Enable add to cart button
  updateAddToCartButton();
  
  showNotification(`Ukuran ${sizeName} dipilih`, 'success');
}

// Quantity management
function increaseQuantity() {
  const quantityInput = document.getElementById('quantity-input');
  const currentValue = parseInt(quantityInput.value);
  const maxValue = parseInt(quantityInput.max);
  
  if (currentValue < maxValue) {
    quantityInput.value = currentValue + 1;
    updateQuantityButtons();
  }
}

function decreaseQuantity() {
  const quantityInput = document.getElementById('quantity-input');
  const currentValue = parseInt(quantityInput.value);
  
  if (currentValue > 1) {
    quantityInput.value = currentValue - 1;
    updateQuantityButtons();
  }
}

function updateQuantityButtons() {
  const quantityInput = document.getElementById('quantity-input');
  const currentValue = parseInt(quantityInput.value);
  const maxValue = parseInt(quantityInput.max);
  
  // Update decrease button
  document.getElementById('decrease-btn').disabled = currentValue <= 1;
  
  // Update increase button
  document.getElementById('increase-btn').disabled = currentValue >= maxValue;
}

function validateQuantity() {
  const quantityInput = document.getElementById('quantity-input');
  const value = parseInt(quantityInput.value);
  const max = parseInt(quantityInput.max);
  const min = parseInt(quantityInput.min);
  
  if (value > max) {
    quantityInput.value = max;
    showNotification(`Maksimal ${max} item untuk ukuran ini`, 'warning');
  } else if (value < min) {
    quantityInput.value = min;
  }
  
  updateQuantityButtons();
}

// Update add to cart button state
function updateAddToCartButton() {
  const addToCartBtn = document.getElementById('add-to-cart-btn');
  const buyNowBtn = document.getElementById('buy-now-btn');
  const addToCartText = document.getElementById('add-to-cart-text');
  
  if (!addToCartBtn) return;
  
  if (selectedSizeId && maxStock > 0) {
    addToCartBtn.disabled = false;
    buyNowBtn.disabled = false;
    addToCartText.textContent = 'Tambah ke Keranjang';
    addToCartBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    buyNowBtn.classList.remove('opacity-50', 'cursor-not-allowed');
  } else {
    addToCartBtn.disabled = true;
    buyNowBtn.disabled = true;
    addToCartText.textContent = 'Pilih ukuran terlebih dahulu';
    addToCartBtn.classList.add('opacity-50', 'cursor-not-allowed');
    buyNowBtn.classList.add('opacity-50', 'cursor-not-allowed');
  }
}

// Add to cart functionality
async function addToCart() {
  // Validate size selection
  if (!selectedSizeId) {
    document.getElementById('size-error').classList.remove('hidden');
    showNotification('Silakan pilih ukuran terlebih dahulu', 'warning');
    return;
  }
  
  const quantity = parseInt(document.getElementById('quantity-input').value);
  
  if (quantity <= 0 || quantity > maxStock) {
    showNotification('Jumlah tidak valid', 'error');
    return;
  }
  
  // Show loading
  showLoading(true);
  
  try {
    const response = await fetch('{{ route("cart.add") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        product_id: productId,
        size_id: selectedSizeId,
        quantity: quantity
      })
    });
    
    const data = await response.json();
    
    if (response.ok && data.success) {
      showNotification(data.message || 'Produk berhasil ditambahkan ke keranjang', 'success');
      
      // Update cart count in header if exists
      updateCartCount(data.cart_count);
      
      // Optionally reset form
      // resetForm();
      
    } else {
      throw new Error(data.message || 'Gagal menambahkan ke keranjang');
    }
    
  } catch (error) {
    console.error('Error adding to cart:', error);
    showNotification(error.message || 'Terjadi kesalahan saat menambahkan ke keranjang', 'error');
  } finally {
    showLoading(false);
  }
}

// Buy now functionality
async function buyNow() {
  // Validate size selection
  if (!selectedSizeId) {
    document.getElementById('size-error').classList.remove('hidden');
    showNotification('Silakan pilih ukuran terlebih dahulu', 'warning');
    return;
  }
  
  const quantity = parseInt(document.getElementById('quantity-input').value);
  
  if (quantity <= 0 || quantity > maxStock) {
    showNotification('Jumlah tidak valid', 'error');
    return;
  }
  
  // Show loading
  showLoading(true);
  
  try {
    // First add to cart
    const addToCartResponse = await fetch('{{ route("cart.add") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        product_id: productId,
        size_id: selectedSizeId,
        quantity: quantity
      })
    });
    
    const addToCartData = await addToCartResponse.json();
    
    if (addToCartResponse.ok && addToCartData.success) {
      // Redirect to cart page
      showNotification('Mengarahkan ke keranjang...', 'info');
      setTimeout(() => {
        window.location.href = '{{ route("cart.index") }}';
      }, 1000);
    } else {
      throw new Error(addToCartData.message || 'Gagal menambahkan ke keranjang');
    }
    
  } catch (error) {
    console.error('Error in buy now:', error);
    showNotification(error.message || 'Terjadi kesalahan', 'error');
    showLoading(false);
  }
}

// Reset form
function resetForm() {
  selectedSize = null;
  selectedSizeId = null;
  maxStock = 0;
  
  document.querySelectorAll('.size-option').forEach(option => {
    option.classList.remove('border-purple-500', 'bg-purple-500/20', 'text-purple-300');
  });
  
  const quantityInput = document.getElementById('quantity-input');
  quantityInput.value = 1;
  quantityInput.max = 1;
  quantityInput.disabled = true;
  
  document.getElementById('decrease-btn').disabled = true;
  document.getElementById('increase-btn').disabled = true;
  document.getElementById('max-quantity').textContent = '0';
  
  updateAddToCartButton();
}

// Update cart count in header
function updateCartCount(count) {
  const cartCountElements = document.querySelectorAll('.cart-count, #cart-count');
  cartCountElements.forEach(element => {
    if (element && count !== undefined) {
      element.textContent = count;
      
      // Add animation
      element.classList.add('animate-bounce');
      setTimeout(() => {
        element.classList.remove('animate-bounce');
      }, 1000);
    }
  });
}

// Show/hide loading modal
function showLoading(show) {
  const modal = document.getElementById('loading-modal');
  if (show) {
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  } else {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }
}

// Wishlist functionality
function toggleWishlist(productId) {
  showNotification('Fitur wishlist akan segera tersedia', 'info');
}

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

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
  updateAddToCartButton();
  
  // Check if there's only one size available and auto-select it
  const availableSizes = document.querySelectorAll('.size-option:not(.opacity-50)');
  if (availableSizes.length === 1) {
    const sizeElement = availableSizes[0];
    const sizeId = sizeElement.dataset.sizeId;
    const size = sizeElement.dataset.size;
    const stock = parseInt(sizeElement.dataset.stock);
    
    if (stock > 0) {
      selectSize(sizeId, size, stock);
    }
  }
  
  // Initialize quantity buttons
  updateQuantityButtons();
});
</script>
@endsection