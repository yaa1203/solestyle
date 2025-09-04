@extends('admin.layouts.app')

@section('title', 'Detail Produk')
@section('products-active', 'active')

@section('styles')
<style>
  .glass-effect {
    background: rgba(30, 41, 59, 0.4);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(148, 163, 184, 0.1);
  }
  .product-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 0.75rem;
    background-color: #374151;
  }
  .product-image-placeholder {
    width: 100%;
    height: 400px;
    background-color: #374151;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    border: 2px dashed #4b5563;
  }
  .swal2-popup {
    background-color: #1e293b !important;
    color: #f1f5f9 !important;
    border: 1px solid rgba(148, 163, 184, 0.1) !important;
  }
  .customer-view-toggle {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  }
  .admin-view {
    background: rgba(139, 92, 246, 0.1);
    border: 1px solid rgba(139, 92, 246, 0.2);
  }
  .stat-card {
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(148, 163, 184, 0.1);
  }
  .preview-mode {
    background: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.2);
  }
</style>
@endsection

@section('content')
<!-- Flash Messages -->
@if(session('success'))
<div class="mb-6 bg-green-500/20 border border-green-500/30 text-green-400 px-4 py-3 rounded-xl" id="success-alert">
  <i class="fas fa-check-circle mr-2"></i>
  {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl" id="error-alert">
  <i class="fas fa-exclamation-circle mr-2"></i>
  {{ session('error') }}
</div>
@endif

<!-- Header -->
<div class="flex items-center justify-between mb-8">
  <div>
    <h2 class="text-2xl font-bold">Detail Produk</h2>
    <p class="text-slate-400">Preview produk sebelum dilihat pembeli</p>
  </div>
  <div class="flex items-center gap-4">
    <!-- View Mode Toggle -->
    <div class="glass-effect rounded-xl p-1 flex">
      <button onclick="toggleView('admin')" id="admin-view-btn" 
              class="admin-view px-4 py-2 rounded-lg text-sm font-medium transition-all">
        <i class="fas fa-cog mr-2"></i>Admin View
      </button>
      <button onclick="toggleView('customer')" id="customer-view-btn" 
              class="px-4 py-2 rounded-lg text-sm font-medium transition-all">
        <i class="fas fa-eye mr-2"></i>Customer View
      </button>
    </div>
    
    <a href="{{ route('products.edit', $product) }}" class="glass-effect hover:bg-slate-700/50 px-4 py-2 rounded-xl flex items-center gap-2">
      <i class="fas fa-edit"></i>
      <span>Edit</span>
    </a>
    <a href="{{ route('products.index') }}" class="glass-effect hover:bg-slate-700/50 px-4 py-2 rounded-xl flex items-center gap-2">
      <i class="fas fa-arrow-left"></i>
      <span>Kembali</span>
    </a>
  </div>
</div>

<!-- Admin View -->
<div id="admin-view">
  <!-- Product Status Alert -->
  @if($product->status === 'inactive')
  <div class="mb-6 bg-yellow-500/20 border border-yellow-500/30 text-yellow-400 px-4 py-3 rounded-xl">
    <i class="fas fa-exclamation-triangle mr-2"></i>
    Produk ini tidak aktif dan tidak akan terlihat oleh pembeli
  </div>
  @endif

  @if($product->stock <= 5)
  <div class="mb-6 bg-orange-500/20 border border-orange-500/30 text-orange-400 px-4 py-3 rounded-xl">
    <i class="fas fa-exclamation-circle mr-2"></i>
    Stok produk rendah ({{ $product->stock }} tersisa)
  </div>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Product Image & Quick Actions -->
    <div class="lg:col-span-1">
      <div class="glass-effect rounded-2xl p-6 mb-6">
        <h3 class="font-semibold mb-4">Gambar Produk</h3>
        @if($product->image_exists)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                class="product-image mb-4"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="product-image-placeholder mb-4" style="display: none;">
            <div class="text-center">
                <i class="fas fa-exclamation-triangle text-6xl mb-4 text-red-400"></i>
                <p>Gambar tidak dapat dimuat</p>
                <p class="text-sm text-slate-500">File mungkin rusak atau terhapus</p>
            </div>
            </div>
            <div class="text-center">
            <button onclick="openImageModal()" class="glass-effect hover:bg-slate-700/50 px-4 py-2 rounded-xl text-sm mr-2">
                <i class="fas fa-expand mr-2"></i>Lihat Penuh
            </button>
            <button onclick="checkImageStatus()" class="glass-effect hover:bg-slate-700/50 px-4 py-2 rounded-xl text-sm">
                <i class="fas fa-info mr-2"></i>Info Gambar
            </button>
            </div>
        @else
            <div class="product-image-placeholder mb-4">
            <div class="text-center">
                <i class="fas fa-image text-6xl mb-4"></i>
                <p>Tidak ada gambar</p>
                <p class="text-sm text-slate-500">Upload gambar di halaman edit</p>
            </div>
            </div>
            <div class="text-center">
            <a href="{{ route('products.edit', $product) }}" class="glass-effect hover:bg-slate-700/50 px-4 py-2 rounded-xl text-sm">
                <i class="fas fa-plus mr-2"></i>Tambah Gambar
            </a>
            </div>
        @endif
        </div>

      <!-- Quick Actions -->
      <div class="glass-effect rounded-2xl p-6">
        <h3 class="font-semibold mb-4">Aksi Cepat</h3>
        <div class="space-y-3">
          <button onclick="toggleStatus()" 
                  class="w-full glass-effect hover:bg-slate-700/50 px-4 py-3 rounded-xl flex items-center gap-3">
            <i class="fas fa-toggle-{{ $product->status === 'active' ? 'on' : 'off' }} text-{{ $product->status === 'active' ? 'green' : 'red' }}-400"></i>
            <span>{{ $product->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }} Produk</span>
          </button>
          
          <button onclick="editStock()" 
                  class="w-full glass-effect hover:bg-slate-700/50 px-4 py-3 rounded-xl flex items-center gap-3">
            <i class="fas fa-boxes text-blue-400"></i>
            <span>Edit Stok</span>
          </button>
          
          <button onclick="duplicateProduct()" 
                  class="w-full glass-effect hover:bg-slate-700/50 px-4 py-3 rounded-xl flex items-center gap-3">
            <i class="fas fa-copy text-yellow-400"></i>
            <span>Duplikasi Produk</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Product Details -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Basic Info -->
      <div class="glass-effect rounded-2xl p-6">
        <div class="flex items-start justify-between mb-6">
          <div>
            <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>
            <div class="flex items-center gap-4 text-sm text-slate-400">
              <span><i class="fas fa-tag mr-1"></i>SKU: {{ $product->sku }}</span>
              <span><i class="fas fa-folder mr-1"></i>{{ $product->category_id }}</span>
              <span><i class="fas fa-clock mr-1"></i>{{ $product->created_at->diffForHumans() }}</span>
            </div>
          </div>
          <span class="{{ $product->status === 'active' ? 'bg-green-500/20 text-green-400 border-green-500/30' : 'bg-red-500/20 text-red-400 border-red-500/30' }} px-3 py-1 rounded-full text-sm border">
            {{ $product->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
          </span>
        </div>

        <!-- Price & Stock Info -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
          <div class="stat-card rounded-xl p-4">
            <div class="text-2xl font-bold text-green-400">
              Rp {{ number_format($product->price, 0, ',', '.') }}
            </div>
            <div class="text-sm text-slate-400">Harga Jual</div>
          </div>
          <div class="stat-card rounded-xl p-4">
            <div class="text-2xl font-bold {{ $product->stock <= 5 ? 'text-red-400' : 'text-blue-400' }}">{{ $product->stock }}</div>
            <div class="text-sm text-slate-400">Stok Tersedia</div>
          </div>
          <div class="stat-card rounded-xl p-4">
            <div class="text-2xl font-bold text-purple-400">{{ $product->weight ?? '-' }}</div>
            <div class="text-sm text-slate-400">Berat (gram)</div>
          </div>
        </div>

        <!-- Description -->
        @if($product->description)
        <div>
          <h3 class="font-semibold mb-3">Deskripsi Produk</h3>
          <div class="bg-slate-800/30 rounded-xl p-4 text-slate-300 leading-relaxed">
            {{ $product->description }}
          </div>
        </div>
        @endif
      </div>

      <!-- Product Analytics (if available) -->
      <div class="glass-effect rounded-2xl p-6">
        <h3 class="font-semibold mb-4">Analitik Produk</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="stat-card rounded-xl p-4 text-center">
            <div class="text-xl font-bold text-green-400">{{ $product->total_views ?? 0 }}</div>
            <div class="text-sm text-slate-400">Total Views</div>
          </div>
          <div class="stat-card rounded-xl p-4 text-center">
            <div class="text-xl font-bold text-blue-400">{{ $product->total_sold ?? 0 }}</div>
            <div class="text-sm text-slate-400">Terjual</div>
          </div>
          <div class="stat-card rounded-xl p-4 text-center">
            <div class="text-xl font-bold text-purple-400">{{ $product->total_revenue ?? 0 }}</div>
            <div class="text-sm text-slate-400">Revenue</div>
          </div>
          <div class="stat-card rounded-xl p-4 text-center">
            <div class="text-xl font-bold text-yellow-400">{{ $product->rating ?? 'N/A' }}</div>
            <div class="text-sm text-slate-400">Rating</div>
          </div>
        </div>
      </div>

      <!-- SEO & Visibility -->
      <div class="glass-effect rounded-2xl p-6">
        <h3 class="font-semibold mb-4">SEO & Visibilitas</h3>
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <span class="text-slate-400">Visible to customers:</span>
            <span class="{{ $product->status === 'active' ? 'text-green-400' : 'text-red-400' }}">
              {{ $product->status === 'active' ? 'Ya' : 'Tidak' }}
            </span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-slate-400">In stock:</span>
            <span class="{{ $product->stock > 0 ? 'text-green-400' : 'text-red-400' }}">
              {{ $product->stock > 0 ? 'Ya' : 'Habis' }}
            </span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-slate-400">Product URL:</span>
            <span class="text-slate-500 text-sm">
              <i class="fas fa-link mr-1"></i>
              /products/{{ $product->slug ?? $product->id }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Customer View -->
<div id="customer-view" class="hidden">
  <div class="preview-mode rounded-xl p-4 mb-6">
    <div class="flex items-center gap-3">
      <i class="fas fa-eye text-green-400"></i>
      <div>
        <h3 class="font-semibold text-green-400">Mode Preview Pembeli</h3>
        <p class="text-sm text-slate-400">Ini adalah tampilan yang akan dilihat pembeli di toko online</p>
      </div>
    </div>
  </div>

  <!-- Customer Product View -->
  <div class="bg-white text-gray-900 rounded-2xl overflow-hidden shadow-2xl">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-8">
      <!-- Product Image -->
      <div>
        @if($product->image_exists)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                class="w-full h-96 object-cover rounded-xl shadow-lg"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="w-full h-96 bg-gray-200 rounded-xl flex items-center justify-center" style="display: none;">
            <div class="text-center text-gray-400">
                <i class="fas fa-exclamation-triangle text-6xl mb-4"></i>
                <p>Image Error</p>
                <p class="text-sm">Cannot load image</p>
            </div>
            </div>
        @else
            <div class="w-full h-96 bg-gray-200 rounded-xl flex items-center justify-center">
            <div class="text-center text-gray-400">
                <i class="fas fa-image text-6xl mb-4"></i>
                <p>No Image Available</p>
            </div>
            </div>
        @endif
        
        <!-- Image Gallery (if multiple images) -->
        <div class="flex gap-2 mt-4">
            @if($product->image_exists)
            <div class="w-20 h-20 border-2 border-purple-500 rounded-lg overflow-hidden">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                class="w-full h-full object-cover">
            </div>
            @endif
            <!-- Placeholder for additional images -->
            <div class="w-20 h-20 border-2 border-gray-200 rounded-lg flex items-center justify-center">
            <i class="fas fa-plus text-gray-400"></i>
            </div>
        </div>
        </div>

      <!-- Product Info -->
      <div>
        <div class="mb-4">
          <span class="text-purple-600 text-sm font-semibold uppercase tracking-wide">{{ $product->category_id }}</span>
          <h1 class="text-3xl font-bold text-gray-900 mt-2">{{ $product->name }}</h1>
        </div>

        <!-- Price -->
        <div class="mb-6">
          <div class="text-3xl font-bold text-purple-600">
            Rp {{ number_format($product->price, 0, ',', '.') }}
          </div>
          @if($product->weight)
          <div class="text-sm text-gray-500 mt-1">Berat: {{ $product->weight }}g</div>
          @endif
        </div>

        <!-- Stock Status -->
        <div class="mb-6">
          @if($product->stock > 0)
            <div class="flex items-center gap-2 text-green-600">
              <i class="fas fa-check-circle"></i>
              <span class="font-semibold">Stok Tersedia ({{ $product->stock }} item)</span>
            </div>
          @else
            <div class="flex items-center gap-2 text-red-600">
              <i class="fas fa-times-circle"></i>
              <span class="font-semibold">Stok Habis</span>
            </div>
          @endif
        </div>

        <!-- Description -->
        @if($product->description)
        <div class="mb-6">
          <h3 class="font-semibold text-gray-900 mb-3">Deskripsi Produk</h3>
          <div class="text-gray-700 leading-relaxed">
            {{ $product->description }}
          </div>
        </div>
        @endif

        <!-- Customer Actions -->
        <div class="space-y-4">
          <!-- Quantity Selector -->
          <div class="flex items-center gap-4">
            <span class="text-gray-700 font-medium">Jumlah:</span>
            <div class="flex items-center border border-gray-300 rounded-lg">
              <button type="button" class="px-3 py-2 hover:bg-gray-100">-</button>
              <input type="number" value="1" min="1" max="{{ $product->stock }}" 
                     class="w-16 text-center border-0 focus:outline-none">
              <button type="button" class="px-3 py-2 hover:bg-gray-100">+</button>
            </div>
          </div>

          <!-- Add to Cart Button -->
          <button class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold py-3 rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all transform hover:scale-105 {{ $product->stock <= 0 || $product->status === 'inactive' ? 'opacity-50 cursor-not-allowed' : '' }}"
                  {{ $product->stock <= 0 || $product->status === 'inactive' ? 'disabled' : '' }}>
            <i class="fas fa-shopping-cart mr-2"></i>
            {{ $product->stock <= 0 ? 'Stok Habis' : 'Tambah ke Keranjang' }}
          </button>

          <!-- Buy Now Button -->
          <button class="w-full border-2 border-purple-600 text-purple-600 font-semibold py-3 rounded-xl hover:bg-purple-50 transition-all {{ $product->stock <= 0 || $product->status === 'inactive' ? 'opacity-50 cursor-not-allowed' : '' }}"
                  {{ $product->stock <= 0 || $product->status === 'inactive' ? 'disabled' : '' }}>
            <i class="fas fa-bolt mr-2"></i>
            Beli Sekarang
          </button>
        </div>

        <!-- Product Meta -->
        <div class="mt-8 pt-6 border-t border-gray-200">
          <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
              <span class="text-gray-500">SKU:</span>
              <span class="font-medium">{{ $product->sku }}</span>
            </div>
            <div>
              <span class="text-gray-500">Kategori:</span>
              <span class="font-medium">{{ $product->category_id }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Image Modal -->
<div id="image-modal" class="fixed inset-0 bg-black/90 z-50 flex items-center justify-center hidden" onclick="closeImageModal()">
  <div class="max-w-4xl max-h-screen p-4" onclick="event.stopPropagation()">
    @if($product->image)
    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
         class="max-w-full max-h-full object-contain rounded-xl">
    @endif
  </div>
  <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">
    <i class="fas fa-times"></i>
  </button>
</div>

<!-- Stock Edit Modal -->
<div id="stock-modal" class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center hidden">
  <div class="glass-effect rounded-2xl w-full max-w-md mx-4">
    <div class="p-6 border-b border-slate-700">
      <h3 class="text-xl font-bold">Edit Stok Produk</h3>
    </div>
    
    <div class="p-6">
      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Aksi Stok</label>
        <select id="stock-action" class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 focus:border-purple-500 focus:outline-none">
          <option value="set">Set Stok Baru</option>
          <option value="add">Tambah Stok</option>
          <option value="subtract">Kurangi Stok</option>
        </select>
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Jumlah Stok</label>
        <input type="number" id="stock-amount" min="0" 
               class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 focus:border-purple-500 focus:outline-none">
      </div>
      <div class="text-sm text-slate-400">
        Stok saat ini: <span id="current-stock">{{ $product->stock }}</span>
      </div>
    </div>
    
    <div class="p-6 border-t border-slate-700 flex justify-end gap-4">
      <button onclick="closeStockModal()" class="glass-effect hover:bg-slate-700/50 px-6 py-2 rounded-xl font-semibold">Batal</button>
      <button onclick="saveStock()" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 px-6 py-2 rounded-xl font-semibold">Simpan</button>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let currentView = 'admin';

// Auto hide alerts
setTimeout(() => {
  const alerts = document.querySelectorAll('#success-alert, #error-alert');
  alerts.forEach(alert => {
    alert.style.opacity = '0';
    setTimeout(() => alert.remove(), 300);
  });
}, 5000);

// View toggle functionality
function toggleView(view) {
  currentView = view;
  const adminView = document.getElementById('admin-view');
  const customerView = document.getElementById('customer-view');
  const adminBtn = document.getElementById('admin-view-btn');
  const customerBtn = document.getElementById('customer-view-btn');

  if (view === 'admin') {
    adminView.classList.remove('hidden');
    customerView.classList.add('hidden');
    adminBtn.classList.add('admin-view');
    customerBtn.classList.remove('admin-view');
  } else {
    adminView.classList.add('hidden');
    customerView.classList.remove('hidden');
    customerBtn.classList.add('admin-view');
    adminBtn.classList.remove('admin-view');
  }
}

// Image modal functions
function openImageModal() {
  @if($product->image)
  const modal = document.getElementById('image-modal');
  const img = modal.querySelector('img');
  if (img) {
    img.src = '{{ asset('storage/' . $product->image) }}';
  }
  modal.classList.remove('hidden');
  @else
  Swal.fire('Info', 'Produk ini belum memiliki gambar', 'info');
  @endif
}

function closeImageModal() {
  document.getElementById('image-modal').classList.add('hidden');
}

// Stock management
function editStock() {
  document.getElementById('stock-modal').classList.remove('hidden');
}

function closeStockModal() {
  document.getElementById('stock-modal').classList.add('hidden');
}

function saveStock() {
  const action = document.getElementById('stock-action').value;
  const amount = document.getElementById('stock-amount').value;
  
  if (!amount || amount < 0) {
    Swal.fire('Error', 'Masukkan jumlah stok yang valid!', 'error');
    return;
  }
  
  fetch(`{{ route('products.update-stock', $product) }}`, {
    method: 'PATCH',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({
      action: action,
      stock: parseInt(amount)
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      Swal.fire('Berhasil', data.message, 'success');
      location.reload();
    } else {
      Swal.fire('Error', data.message, 'error');
    }
  })
  .catch(error => {
    Swal.fire('Error', 'Terjadi kesalahan sistem!', 'error');
  });
  
  closeStockModal();
}

// Toggle product status
function toggleStatus() {
  const currentStatus = '{{ $product->status }}';
  const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
  
  Swal.fire({
    title: 'Ubah Status Produk?',
    text: `Produk akan diubah menjadi ${newStatus === 'active' ? 'aktif' : 'tidak aktif'}`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#8b5cf6',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Ya, Ubah!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '{{ route("products.toggle-status", $product) }}';
      form.innerHTML = `
        @csrf
        @method('PATCH')
      `;
      document.body.appendChild(form);
      form.submit();
    }
  });
}

// Duplicate product
function duplicateProduct() {
  Swal.fire({
    title: 'Duplikasi Produk?',
    text: 'Produk baru akan dibuat dengan data yang sama',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#8b5cf6',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Ya, Duplikasi!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = '{{ route("products.create") }}?duplicate={{ $product->id }}';
    }
  });
}

// Customer view quantity controls
document.addEventListener('DOMContentLoaded', function() {
  const quantityInput = document.querySelector('#customer-view input[type="number"]');
  const decreaseBtn = document.querySelector('#customer-view button:first-of-type');
  const increaseBtn = document.querySelector('#customer-view button:nth-of-type(2)');
  
  if (quantityInput && decreaseBtn && increaseBtn) {
    decreaseBtn.addEventListener('click', function() {
      const current = parseInt(quantityInput.value);
      if (current > 1) {
        quantityInput.value = current - 1;
      }
    });

    increaseBtn.addEventListener('click', function() {
      const current = parseInt(quantityInput.value);
      const max = {{ $product->stock }};
      if (current < max) {
        quantityInput.value = current + 1;
      }
    });
  }
});

// Close modals when clicking outside
document.getElementById('stock-modal').addEventListener('click', (e) => {
  if (e.target === e.currentTarget) {
    closeStockModal();
  }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeImageModal();
    closeStockModal();
  }
  
  if (e.ctrlKey || e.metaKey) {
    switch(e.key) {
      case 'e':
        e.preventDefault();
        window.location.href = '{{ route("products.edit", $product) }}';
        break;
      case 'b':
        e.preventDefault();
        window.location.href = '{{ route("products.index") }}';
        break;
    }
  }
});

// Auto-refresh data every 30 seconds (optional)
setInterval(() => {
  // You can implement auto-refresh logic here if needed
  // This is useful for real-time stock updates
}, 30000);
</script>
@endsection