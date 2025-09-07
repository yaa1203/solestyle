@extends('admin.layouts.app')
@section('title', 'Kelola Produk')
@section('products-active', 'active')
@section('styles')
<style>
  .glass-effect {
    background: rgba(30, 41, 59, 0.4);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(148, 163, 184, 0.1);
  }
  .product-image-preview {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 0.75rem;
    background-color: #374151;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
  }
  .swal2-popup {
    background-color: #1e293b !important;
    color: #f1f5f9 !important;
    border: 1px solid rgba(148, 163, 184, 0.1) !important;
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
<div class="flex items-center justify-between mb-8">
  <div>
    <h2 class="text-2xl font-bold">Kelola Produk</h2>
    <p class="text-slate-400">Tambahkan dan kelola produk di toko Anda</p>
  </div>
  <div class="flex items-center gap-4">
    <a href="{{ route('products.export', request()->all()) }}" class="glass-effect hover:bg-slate-700/50 px-4 py-2 rounded-xl flex items-center gap-2">
      <i class="fas fa-download"></i>
      <span>Ekspor Data</span>
    </a>
    <a href="{{ route('products.create') }}" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 px-4 py-2 rounded-xl font-semibold transition-all transform hover:scale-105 flex items-center gap-2">
      <i class="fas fa-plus"></i>
      <span>Tambah Produk</span>
    </a>
  </div>
</div>
<!-- Filter -->
<form method="GET" action="{{ route('products.index') }}">
  <div class="glass-effect rounded-2xl p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
      <!-- Search -->
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." 
             class="bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-2 focus:border-purple-500 focus:outline-none">
      
      <!-- Category Filter -->
      <select name="category" class="bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-2 focus:border-purple-500 focus:outline-none">
        <option value="">Semua Kategori</option>
        @foreach($categories as $category)
        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
          {{ $category->name }}
        </option>
        @endforeach
      </select>
      
      <!-- Status Filter -->
      <select name="status" class="bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-2 focus:border-purple-500 focus:outline-none">
        <option value="">Semua Status</option>
        @foreach($statusOptions as $status)
        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
          {{ $status }}
        </option>
        @endforeach
      </select>
      
      <!-- Stock Filter -->
      <select name="stock_filter" class="bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-2 focus:border-purple-500 focus:outline-none">
        <option value="">Semua Stok</option>
        @foreach($stockOptions as $stockOption)
        <option value="{{ $stockOption }}" {{ request('stock_filter') == $stockOption ? 'selected' : '' }}>
          {{ $stockOption }}
        </option>
        @endforeach
      </select>
      
      <!-- Filter Button -->
      <button type="submit" class="glass-effect hover:bg-slate-700/50 px-4 py-2 rounded-xl flex items-center gap-2 justify-center">
        <i class="fas fa-filter"></i>
        <span>Filter</span>
      </button>
    </div>
    
    <!-- Clear Filter -->
    @if(request()->hasAny(['search', 'category', 'status', 'stock_filter']))
    <div class="mt-4 flex justify-end">
      <a href="{{ route('products.index') }}" class="text-slate-400 hover:text-white text-sm flex items-center gap-2">
        <i class="fas fa-times"></i>
        <span>Hapus Filter</span>
      </a>
    </div>
    @endif
  </div>
</form>
<!-- Bulk Actions -->
<div class="glass-effect rounded-2xl p-4 mb-6 hidden" id="bulk-actions">
  <div class="flex items-center justify-between">
    <div class="text-slate-400">
      <span id="selected-count">0</span> produk dipilih
    </div>
    <div class="flex gap-2">
      <button type="button" onclick="toggleSelectedStatus()" class="glass-effect hover:bg-slate-700/50 px-4 py-2 rounded-xl text-sm flex items-center gap-2">
        <i class="fas fa-toggle-on"></i>
        <span>Toggle Status</span>
      </button>
      <button type="button" onclick="deleteSelected()" class="glass-effect hover:bg-red-500/20 text-red-400 px-4 py-2 rounded-xl text-sm flex items-center gap-2">
        <i class="fas fa-trash"></i>
        <span>Hapus Terpilih</span>
      </button>
    </div>
  </div>
</div>
<!-- Products Table -->
<div class="glass-effect rounded-2xl p-6 mb-8">
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead>
        <tr class="border-b border-slate-700">
          <th class="pb-4 text-left">
            <input type="checkbox" id="select-all" class="rounded bg-slate-700 border-slate-600" onchange="toggleSelectAll()">
          </th>
          <th class="pb-4 text-left">
            <a href="{{ route('products.index', array_merge(request()->all(), ['sort_by' => 'name', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}" 
               class="flex items-center gap-2 hover:text-purple-400">
              Produk
              @if(request('sort_by') === 'name')
                <i class="fas fa-sort-{{ request('sort_order') === 'asc' ? 'up' : 'down' }}"></i>
              @endif
            </a>
          </th>
          <th class="pb-4 text-left">
            <a href="{{ route('products.index', array_merge(request()->all(), ['sort_by' => 'category', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}" 
               class="flex items-center gap-2 hover:text-purple-400">
              Kategori
              @if(request('sort_by') === 'category')
                <i class="fas fa-sort-{{ request('sort_order') === 'asc' ? 'up' : 'down' }}"></i>
              @endif
            </a>
          </th>
          <th class="pb-4 text-left">
            <a href="{{ route('products.index', array_merge(request()->all(), ['sort_by' => 'price', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}" 
               class="flex items-center gap-2 hover:text-purple-400">
              Harga
              @if(request('sort_by') === 'price')
                <i class="fas fa-sort-{{ request('sort_order') === 'asc' ? 'up' : 'down' }}"></i>
              @endif
            </a>
          </th>
          <th class="pb-4 text-left">
            <a href="{{ route('products.index', array_merge(request()->all(), ['sort_by' => 'stock', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}" 
               class="flex items-center gap-2 hover:text-purple-400">
              Stok
              @if(request('sort_by') === 'stock')
                <i class="fas fa-sort-{{ request('sort_order') === 'asc' ? 'up' : 'down' }}"></i>
              @endif
            </a>
          </th>
          <th class="pb-4 text-left">
            <a href="{{ route('products.index', array_merge(request()->all(), ['sort_by' => 'status', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}" 
               class="flex items-center gap-2 hover:text-purple-400">
              Status
              @if(request('sort_by') === 'status')
                <i class="fas fa-sort-{{ request('sort_order') === 'asc' ? 'up' : 'down' }}"></i>
              @endif
            </a>
          </th>
          <th class="pb-4 text-left">Ukuran & Stok</th>
          <th class="pb-4 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($products as $product)
        <tr class="border-b border-slate-700 hover:bg-slate-700/30" data-product-id="{{ $product->id }}">
          <td class="py-4">
            <input type="checkbox" class="product-checkbox rounded bg-slate-700 border-slate-600" 
                   value="{{ $product->id }}" onchange="updateBulkActions()">
          </td>
          <td class="py-4">
            <div class="flex items-center gap-3">
              @if($product->image_url)
                <img src="{{ $product->image_url }}" 
                    alt="{{ $product->name }}" 
                    class="w-12 h-12 object-cover rounded-xl border border-slate-600"
                    loading="lazy"
                    onerror="this.src='{{ asset('images/product-placeholder.png') }}';">
              @else
                <div class="w-12 h-12 bg-slate-700 rounded-xl flex items-center justify-center border border-slate-600">
                  <i class="fas fa-image text-slate-500"></i>
                </div>
              @endif
              
              <div>
                <div class="font-semibold">{{ $product->name }}</div>
                <div class="text-slate-400 text-sm">SKU: {{ $product->sku }}</div>
              </div>
            </div>
          </td>
          <td class="py-4">
            @if($product->category)
              <span class="bg-purple-500/20 text-purple-400 px-2 py-1 rounded-lg text-sm">
                {{ $product->category->name }}
              </span>
            @else
              <span class="text-slate-500 text-sm">-</span>
            @endif
          </td>
          <td class="py-4">{{ $product->formatted_price }}</td>
          <td class="py-4">
            <span class="{{ $product->stock_badge_class }}" 
                  onclick="editStock({{ $product->id }}, {{ $product->total_stock }})" 
                  style="cursor: pointer;" title="Klik untuk edit stok total">
              {{ $product->total_stock }}
            </span>
          </td>
          <td class="py-4">
            <span class="{{ $product->status_badge_class }}">{{ $product->status_text }}</span>
          </td>
          <td class="py-4">
            <div class="flex flex-wrap gap-1">
              @foreach($product->sizes as $size)
                <span class="inline-block px-2 py-1 rounded text-xs font-medium 
                      {{ $size->stock > 0 ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                  {{ $size->size }} ({{ $size->stock }})
                </span>
              @endforeach
              @if($product->sizes->count() == 0)
                <span class="text-slate-500 text-sm">Tidak ada ukuran</span>
              @endif
            </div>
          </td>
          <td class="py-4 text-right">
            <div class="flex justify-end gap-2">
              <a href="{{ route('products.show', $product) }}" 
                class="p-2 text-slate-400 hover:text-blue-400" title="Lihat Detail">
                <i class="fas fa-eye"></i>
              </a>
              <a href="{{ route('products.edit', $product) }}" 
                class="p-2 text-slate-400 hover:text-yellow-400" title="Edit">
                <i class="fas fa-edit"></i>
              </a>
              <button onclick="toggleStatus({{ $product->id }})" 
                      class="p-2 text-slate-400 hover:text-green-400" title="Toggle Status">
                <i class="fas fa-toggle-{{ $product->status === 'active' ? 'on' : 'off' }}"></i>
              </button>
              <button onclick="editSizeStock({{ $product->id }})" 
                      class="p-2 text-slate-400 hover:text-purple-400" title="Edit Stok per Ukuran">
                <i class="fas fa-ruler"></i>
              </button>
              <button onclick="deleteProduct({{ $product->id }}, '{{ $product->name }}')" 
                      class="p-2 text-slate-400 hover:text-red-400" title="Hapus">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="py-8 text-center text-slate-400">
            <i class="fas fa-box-open text-4xl mb-4 block"></i>
            <p>Tidak ada produk yang ditemukan</p>
            @if(request()->hasAny(['search', 'category', 'status', 'stock_filter']))
              <p class="text-sm mt-2">Coba ubah filter pencarian Anda</p>
            @endif
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  
  <!-- Pagination -->
  @if($products->hasPages())
  <div class="flex items-center justify-between mt-6">
    <div class="text-slate-400 text-sm">
      Menampilkan {{ $products->firstItem() }}-{{ $products->lastItem() }} dari {{ $products->total() }} produk
    </div>
    <div class="flex gap-2">
      <!-- Previous Page -->
      @if($products->onFirstPage())
        <button disabled class="glass-effect w-10 h-10 rounded-xl flex items-center justify-center opacity-50 cursor-not-allowed">
          <i class="fas fa-chevron-left"></i>
        </button>
      @else
        <a href="{{ $products->previousPageUrl() }}" class="glass-effect hover:bg-slate-700/50 w-10 h-10 rounded-xl flex items-center justify-center">
          <i class="fas fa-chevron-left"></i>
        </a>
      @endif
      <!-- Page Numbers -->
      @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
        @if($page == $products->currentPage())
          <button class="glass-effect bg-purple-500/20 text-purple-400 w-10 h-10 rounded-xl">{{ $page }}</button>
        @else
          <a href="{{ $url }}" class="glass-effect hover:bg-slate-700/50 w-10 h-10 rounded-xl flex items-center justify-center">{{ $page }}</a>
        @endif
      @endforeach
      <!-- Next Page -->
      @if($products->hasMorePages())
        <a href="{{ $products->nextPageUrl() }}" class="glass-effect hover:bg-slate-700/50 w-10 h-10 rounded-xl flex items-center justify-center">
          <i class="fas fa-chevron-right"></i>
        </a>
      @else
        <button disabled class="glass-effect w-10 h-10 rounded-xl flex items-center justify-center opacity-50 cursor-not-allowed">
          <i class="fas fa-chevron-right"></i>
        </button>
      @endif
    </div>
  </div>
  @endif
</div>

<!-- Modal untuk Edit Stok Total -->
<div id="stock-modal" class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center hidden">
  <div class="glass-effect rounded-2xl w-full max-w-md mx-4">
    <div class="p-6 border-b border-slate-700">
      <h3 class="text-xl font-bold">Edit Stok Total</h3>
    </div>
    
    <div class="p-6">
      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Produk</label>
        <input type="text" id="product-name" class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3" readonly>
      </div>
      
      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Stok Saat Ini</label>
        <p class="text-slate-400" id="current-stock">0</p>
      </div>
      
      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Aksi</label>
        <select id="stock-action" class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 focus:border-purple-500 focus:outline-none">
          <option value="set">Set Stok</option>
          <option value="add">Tambah Stok</option>
          <option value="subtract">Kurangi Stok</option>
        </select>
      </div>
      
      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Jumlah</label>
        <input type="number" id="stock-amount" class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 focus:border-purple-500 focus:outline-none" min="0">
      </div>
    </div>
    
    <div class="p-6 border-t border-slate-700 flex justify-end gap-4">
      <button onclick="closeStockModal()" class="glass-effect hover:bg-slate-700/50 px-6 py-2 rounded-xl font-semibold">Batal</button>
      <button onclick="saveStock()" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 px-6 py-2 rounded-xl font-semibold">Simpan</button>
    </div>
  </div>
</div>

<!-- Modal untuk Edit Stok per Ukuran -->
<div id="size-stock-modal" class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center hidden">
  <div class="glass-effect rounded-2xl w-full max-w-md mx-4">
    <div class="p-6 border-b border-slate-700">
      <h3 class="text-xl font-bold">Edit Stok per Ukuran</h3>
    </div>
    
    <div class="p-6">
      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Produk</label>
        <input type="text" id="product-name" class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3" readonly>
      </div>
      
      <div id="sizes-list" class="space-y-3">
        <!-- Daftar ukuran akan dimuat di sini -->
      </div>
    </div>
    
    <div class="p-6 border-t border-slate-700 flex justify-end gap-4">
      <button onclick="closeSizeStockModal()" class="glass-effect hover:bg-slate-700/50 px-6 py-2 rounded-xl font-semibold">Batal</button>
      <button onclick="saveSizeStock()" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 px-6 py-2 rounded-xl font-semibold">Simpan</button>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let selectedProducts = [];
let currentProductId = null;
function editSizeStock(productId) {
    currentProductId = productId;
    
    // Fetch product details including sizes
    fetch(`/products/${productId}/sizes`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('product-name').value = data.product.name;
                
                // Populate sizes list
                const sizesList = document.getElementById('sizes-list');
                sizesList.innerHTML = '';
                
                data.product.sizes.forEach(size => {
                    const sizeDiv = document.createElement('div');
                    sizeDiv.className = 'flex items-center gap-2';
                    sizeDiv.innerHTML = `
                        <span class="w-16 text-sm">${size.size}</span>
                        <input type="number" id="size-${size.id}" value="${size.stock}" min="0"
                               class="flex-1 bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 focus:border-purple-500 focus:outline-none">
                    `;
                    sizesList.appendChild(sizeDiv);
                });
                
                // Show modal
                document.getElementById('size-stock-modal').classList.remove('hidden');
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error', 'Terjadi kesalahan sistem!', 'error');
        });
}
function closeSizeStockModal() {
    document.getElementById('size-stock-modal').classList.add('hidden');
    currentProductId = null;
}
function saveSizeStock() {
    // Collect size data
    const sizes = [];
    const sizeInputs = document.querySelectorAll('#sizes-list input');
    
    sizeInputs.forEach(input => {
        const sizeId = input.id.replace('size-', '');
        const stock = parseInt(input.value);
        sizes.push({ id: sizeId, stock: stock });
    });
    
    // Send to server
    fetch(`/products/${currentProductId}/update-size-stock`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ sizes: sizes })
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
    
    closeSizeStockModal();
}
// Close modal when clicking outside
document.getElementById('size-stock-modal').addEventListener('click', (e) => {
    if (e.target === e.currentTarget) {
        closeSizeStockModal();
    }
});
// Auto hide alerts
setTimeout(() => {
  const alerts = document.querySelectorAll('#success-alert, #error-alert');
  alerts.forEach(alert => {
    alert.style.opacity = '0';
    setTimeout(() => alert.remove(), 300);
  });
}, 5000);
// Select All Functionality
function toggleSelectAll() {
  const selectAll = document.getElementById('select-all');
  const checkboxes = document.querySelectorAll('.product-checkbox');
  
  checkboxes.forEach(checkbox => {
    checkbox.checked = selectAll.checked;
  });
  
  updateBulkActions();
}
// Update bulk actions visibility
function updateBulkActions() {
  const checkboxes = document.querySelectorAll('.product-checkbox:checked');
  const bulkActions = document.getElementById('bulk-actions');
  const selectedCount = document.getElementById('selected-count');
  
  selectedProducts = Array.from(checkboxes).map(cb => cb.value);
  
  if (selectedProducts.length > 0) {
    bulkActions.classList.remove('hidden');
    selectedCount.textContent = selectedProducts.length;
  } else {
    bulkActions.classList.add('hidden');
  }
}
// Delete single product
function deleteProduct(id, name) {
  Swal.fire({
    title: 'Hapus Produk?',
    text: `Apakah Anda yakin ingin menghapus "${name}"?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = `/products/${id}`;
      form.innerHTML = `
        @csrf
        @method('DELETE')
      `;
      document.body.appendChild(form);
      form.submit();
    }
  });
}
// Delete multiple products
function deleteSelected() {
  if (selectedProducts.length === 0) return;
  
  Swal.fire({
    title: 'Hapus Produk Terpilih?',
    text: `Apakah Anda yakin ingin menghapus ${selectedProducts.length} produk yang dipilih?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '{{ route("products.destroy-multiple") }}';
      form.innerHTML = `
        @csrf
        @method('DELETE')
        ${selectedProducts.map(id => `<input type="hidden" name="product_ids[]" value="${id}">`).join('')}
      `;
      document.body.appendChild(form);
      form.submit();
    }
  });
}
// Toggle product status
function toggleStatus(id) {
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = `/products/${id}/toggle-status`;
  form.innerHTML = `
    @csrf
    @method('PATCH')
  `;
  document.body.appendChild(form);
  form.submit();
}
// Stock management
function editStock(productId, currentStock) {
  currentProductId = productId;
  document.getElementById('product-name').value = document.querySelector(`tr[data-product-id="${productId}"] .font-semibold`).textContent;
  document.getElementById('current-stock').textContent = currentStock;
  document.getElementById('stock-amount').value = '';
  document.getElementById('stock-action').value = 'set';
  document.getElementById('stock-modal').classList.remove('hidden');
}
function closeStockModal() {
  document.getElementById('stock-modal').classList.add('hidden');
  currentProductId = null;
}
function saveStock() {
  const action = document.getElementById('stock-action').value;
  const amount = document.getElementById('stock-amount').value;
  
  if (!amount || amount < 0) {
    Swal.fire('Error', 'Masukkan jumlah stok yang valid!', 'error');
    return;
  }
  
  fetch(`/products/${currentProductId}/update-stock`, {
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
// Close modal when clicking outside
document.getElementById('stock-modal').addEventListener('click', (e) => {
  if (e.target === e.currentTarget) {
    closeStockModal();
  }
});
// Auto-submit filter form on change
document.querySelectorAll('select[name="category"], select[name="status"], select[name="stock_filter"]').forEach(select => {
  select.addEventListener('change', function() {
    this.closest('form').submit();
  });
});
// Search with debounce
let searchTimeout;
const searchInput = document.querySelector('input[name="search"]');
if (searchInput) {
  searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      this.closest('form').submit();
    }, 500);
  });
}
</script>
@endsection