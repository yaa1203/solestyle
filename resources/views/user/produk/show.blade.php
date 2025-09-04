@extends('layouts.app')

@section('title', $product->name)

@section('styles')
<style>
  .product-image {
    width: 100%;
    height: 500px;
    object-fit: cover;
    border-radius: 1rem;
    transition: transform 0.3s ease;
  }
  
  .product-image:hover {
    transform: scale(1.02);
  }
  
  .thumbnail {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    opacity: 0.7;
  }
  
  .thumbnail:hover,
  .thumbnail.active {
    opacity: 1;
    border: 2px solid #8b5cf6;
  }
  
  .image-placeholder {
    width: 100%;
    height: 500px;
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    border: 2px dashed #d1d5db;
  }
  
  .price-gradient {
    background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  
  .quantity-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #d1d5db;
    background: white;
    color: #374151;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  
  .quantity-btn:hover:not(:disabled) {
    background: #f3f4f6;
    border-color: #8b5cf6;
  }
  
  .quantity-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
  
  .quantity-input {
    width: 80px;
    height: 40px;
    text-align: center;
    border-top: 1px solid #d1d5db;
    border-bottom: 1px solid #d1d5db;
    border-left: 0;
    border-right: 0;
    outline: none;
  }
  
  .btn-primary {
    background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
    border: none;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
  }
  
  .btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(139, 92, 246, 0.3);
  }
  
  .btn-secondary {
    border: 2px solid #8b5cf6;
    color: #8b5cf6;
    background: white;
    font-weight: 600;
    transition: all 0.3s ease;
  }
  
  .btn-secondary:hover:not(:disabled) {
    background: #8b5cf6;
    color: white;
    transform: translateY(-1px);
  }
  
  .stock-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 0.75rem;
    font-weight: 600;
    font-size: 0.875rem;
  }
  
  .stock-available {
    background: #dcfce7;
    color: #16a34a;
    border: 1px solid #bbf7d0;
  }
  
  .stock-low {
    background: #fef3c7;
    color: #d97706;
    border: 1px solid #fed7aa;
  }
  
  .stock-out {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
  }
  
  .breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 2rem;
  }
  
  .breadcrumb a {
    color: #8b5cf6;
    text-decoration: none;
  }
  
  .breadcrumb a:hover {
    text-decoration: underline;
  }
  
  .product-meta {
    background: #f9fafb;
    border-radius: 1rem;
    padding: 1.5rem;
  }
  
  .feature-list {
    list-style: none;
    padding: 0;
  }
  
  .feature-list li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 0;
    color: #374151;
  }
  
  .feature-list li i {
    color: #10b981;
    width: 20px;
  }
  
  @media (max-width: 768px) {
    .product-image {
      height: 300px;
    }
    
    .image-placeholder {
      height: 300px;
    }
    
    .quantity-controls {
      flex-direction: column;
      gap: 1rem;
    }
    
    .btn-actions {
      flex-direction: column;
    }
  }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
  <!-- Breadcrumb -->
  <nav class="breadcrumb">
    <a href="{{ route('home') }}">
      <i class="fas fa-home"></i>
      Beranda
    </a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('products.index') }}">Produk</a>
    <i class="fas fa-chevron-right"></i>
    <span>{{ $product->name }}</span>
  </nav>

  <!-- Flash Messages -->
  @if(session('success'))
  <div class="mb-6 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2" id="success-alert">
    <i class="fas fa-check-circle"></i>
    <span>{{ session('success') }}</span>
  </div>
  @endif

  @if(session('error'))
  <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl flex items-center gap-2" id="error-alert">
    <i class="fas fa-exclamation-circle"></i>
    <span>{{ session('error') }}</span>
  </div>
  @endif

  <!-- Product Details -->
  <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-8">
      <!-- Product Images -->
      <div>
        <!-- Main Image -->
        <div class="mb-4">
          @if($product->image_exists)
            <img id="main-image" src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                class="product-image shadow-lg"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="image-placeholder" style="display: none;">
              <div class="text-center">
                <i class="fas fa-exclamation-triangle text-6xl mb-4 text-gray-400"></i>
                <p class="text-gray-500">Gambar tidak dapat dimuat</p>
              </div>
            </div>
          @else
            <div class="image-placeholder">
              <div class="text-center">
                <i class="fas fa-image text-6xl mb-4"></i>
                <p>Tidak ada gambar</p>
              </div>
            </div>
          @endif
        </div>
        
        <!-- Image Thumbnails -->
        @if($product->image_exists)
        <div class="flex gap-3 overflow-x-auto pb-2">
          <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
               class="thumbnail active" 
               onclick="changeMainImage(this.src)">
          <!-- Add more thumbnails here if you have multiple images -->
        </div>
        @endif
      </div>

      <!-- Product Information -->
      <div>
        <!-- Category Badge -->
        <div class="mb-3">
          <span class="inline-block bg-purple-100 text-purple-600 text-sm font-semibold px-3 py-1 rounded-full">
            {{ $product->category->name ?? $product->category_id }}
          </span>
        </div>

        <!-- Product Name -->
        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

        <!-- Price -->
        <div class="mb-6">
          <div class="text-4xl font-bold price-gradient mb-2">
            Rp {{ number_format($product->price, 0, ',', '.') }}
          </div>
          @if($product->weight)
          <div class="text-gray-500">
            <i class="fas fa-weight-hanging mr-1"></i>
            Berat: {{ $product->weight }}g
          </div>
          @endif
        </div>

        <!-- Stock Status -->
        <div class="mb-6">
          @if($product->stock > 10)
            <div class="stock-badge stock-available">
              <i class="fas fa-check-circle"></i>
              <span>Stok Tersedia ({{ $product->stock }} item)</span>
            </div>
          @elseif($product->stock > 0)
            <div class="stock-badge stock-low">
              <i class="fas fa-exclamation-triangle"></i>
              <span>Stok Terbatas ({{ $product->stock }} item)</span>
            </div>
          @else
            <div class="stock-badge stock-out">
              <i class="fas fa-times-circle"></i>
              <span>Stok Habis</span>
            </div>
          @endif
        </div>

        <!-- Product Description -->
        @if($product->description)
        <div class="mb-8">
          <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi Produk</h3>
          <div class="text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-xl">
            {!! nl2br(e($product->description)) !!}
          </div>
        </div>
        @endif

        <!-- Quantity & Actions -->
        @if($product->stock > 0 && $product->status === 'active')
        <form id="add-to-cart-form" action="{{ route('cart.add', $product) }}" method="POST">
          @csrf
          <div class="mb-6">
            <!-- Quantity Selector -->
            <div class="flex items-center gap-4 mb-4">
              <span class="text-gray-700 font-medium">Jumlah:</span>
              <div class="flex items-center">
                <button type="button" onclick="decreaseQuantity()" class="quantity-btn rounded-l-lg">
                  <i class="fas fa-minus"></i>
                </button>
                <input type="number" name="quantity" id="quantity" value="1" 
                       min="1" max="{{ $product->stock }}" 
                       class="quantity-input" readonly>
                <button type="button" onclick="increaseQuantity()" class="quantity-btn rounded-r-lg">
                  <i class="fas fa-plus"></i>
                </button>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="btn-actions flex gap-3">
              <button type="submit" class="btn-primary flex-1 py-4 rounded-xl text-lg">
                <i class="fas fa-shopping-cart mr-2"></i>
                Tambah ke Keranjang
              </button>
              <button type="button" onclick="buyNow()" class="btn-secondary flex-1 py-4 rounded-xl text-lg">
                <i class="fas fa-bolt mr-2"></i>
                Beli Sekarang
              </button>
            </div>
          </div>
        </form>
        @else
        <div class="mb-6">
          <button disabled class="w-full bg-gray-300 text-gray-500 py-4 rounded-xl text-lg cursor-not-allowed">
            <i class="fas fa-times mr-2"></i>
            {{ $product->status === 'inactive' ? 'Produk Tidak Tersedia' : 'Stok Habis' }}
          </button>
        </div>
        @endif

        <!-- Product Features -->
        <div class="mb-8">
          <h3 class="text-lg font-semibold text-gray-900 mb-3">Keunggulan Produk</h3>
          <ul class="feature-list">
            <li>
              <i class="fas fa-shipping-fast"></i>
              <span>Pengiriman Cepat & Aman</span>
            </li>
            <li>
              <i class="fas fa-shield-alt"></i>
              <span>Garansi Kualitas Produk</span>
            </li>
            <li>
              <i class="fas fa-headset"></i>
              <span>Customer Service 24/7</span>
            </li>
            <li>
              <i class="fas fa-undo-alt"></i>
              <span>Easy Return Policy</span>
            </li>
          </ul>
        </div>

        <!-- Product Meta Information -->
        <div class="product-meta">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Produk</h3>
          <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
              <span class="text-gray-500 block">SKU:</span>
              <span class="font-medium">{{ $product->sku }}</span>
            </div>
            <div>
              <span class="text-gray-500 block">Kategori:</span>
              <span class="font-medium">{{ $product->category->name ?? $product->category_id }}</span>
            </div>
            @if($product->weight)
            <div>
              <span class="text-gray-500 block">Berat:</span>
              <span class="font-medium">{{ $product->weight }}g</span>
            </div>
            @endif
            <div>
              <span class="text-gray-500 block">Stok:</span>
              <span class="font-medium {{ $product->stock <= 5 ? 'text-red-600' : 'text-green-600' }}">
                {{ $product->stock }} unit
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Related Products Section (Optional) -->
  <div class="mt-12">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Produk Serupa</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <!-- Related products will be loaded here -->
      <div class="bg-gray-100 rounded-xl p-4 flex items-center justify-center h-48">
        <span class="text-gray-400">Produk Serupa</span>
      </div>
    </div>
  </div>
</div>

<!-- Image Modal -->
<div id="image-modal" class="fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center hidden" onclick="closeImageModal()">
  <div class="max-w-4xl max-h-screen p-4 relative" onclick="event.stopPropagation()">
    <img id="modal-image" src="" alt="" class="max-w-full max-h-full object-contain rounded-xl">
    <button onclick="closeImageModal()" class="absolute -top-2 -right-2 bg-white text-gray-800 rounded-full p-2 hover:bg-gray-100">
      <i class="fas fa-times"></i>
    </button>
  </div>
</div>
@endsection

@section('scripts')
<script>
// Auto hide alerts
setTimeout(() => {
  const alerts = document.querySelectorAll('#success-alert, #error-alert');
  alerts.forEach(alert => {
    alert.style.opacity = '0';
    alert.style.transition = 'opacity 0.3s';
    setTimeout(() => alert.remove(), 300);
  });
}, 5000);

// Quantity controls
let maxQuantity = {{ $product->stock }};

function decreaseQuantity() {
  const input = document.getElementById('quantity');
  const current = parseInt(input.value);
  if (current > 1) {
    input.value = current - 1;
  }
}

function increaseQuantity() {
  const input = document.getElementById('quantity');
  const current = parseInt(input.value);
  if (current < maxQuantity) {
    input.value = current + 1;
  }
}

// Image functions
function changeMainImage(src) {
  const mainImage = document.getElementById('main-image');
  if (mainImage) {
    mainImage.src = src;
  }
  
  // Update active thumbnail
  document.querySelectorAll('.thumbnail').forEach(thumb => {
    thumb.classList.remove('active');
  });
  event.target.classList.add('active');
}

function openImageModal() {
  @if($product->image_exists)
  const modal = document.getElementById('image-modal');
  const modalImage = document.getElementById('modal-image');
  const mainImage = document.getElementById('main-image');
  
  if (modalImage && mainImage) {
    modalImage.src = mainImage.src;
    modalImage.alt = mainImage.alt;
    modal.classList.remove('hidden');
  }
  @endif
}

function closeImageModal() {
  document.getElementById('image-modal').classList.add('hidden');
}

// Buy now function
function buyNow() {
  const quantity = document.getElementById('quantity').value;
  // Redirect to checkout with this product
  window.location.href = `{{ route('checkout.index') }}?product={{ $product->id }}&quantity=${quantity}`;
}

// Add click to zoom on main image
@if($product->image_exists)
document.getElementById('main-image')?.addEventListener('click', openImageModal);
@endif

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeImageModal();
  }
});

// Form validation
document.getElementById('add-to-cart-form')?.addEventListener('submit', function(e) {
  const quantity = parseInt(document.getElementById('quantity').value);
  if (quantity < 1 || quantity > maxQuantity) {
    e.preventDefault();
    alert(`Jumlah harus antara 1 dan ${maxQuantity}`);
    return;
  }
  
  // Show loading state
  const submitBtn = this.querySelector('button[type="submit"]');
  if (submitBtn) {
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menambahkan...';
    submitBtn.disabled = true;
  }
});

// Smooth scroll to sections
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      target.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
    }
  });
});

// Product view tracking (optional)
fetch('{{ route("products.track-view", $product) }}', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
  }
}).catch(() => {
  // Silent fail for view tracking
});
</script>
@endsection