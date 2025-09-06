@extends('admin.layouts.app')

@section('title', 'Edit Produk')
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
    border: 2px dashed #4b5563;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  .product-image-preview:hover {
    border-color: #8b5cf6;
    background-color: #4c1d95;
  }
  .file-input {
    display: none;
  }
  .swal2-popup {
    background-color: #1e293b !important;
    color: #f1f5f9 !important;
    border: 1px solid rgba(148, 163, 184, 0.1) !important;
  }
  .image-preview-item {
    position: relative;
    border-radius: 0.75rem;
    overflow: hidden;
    background-color: #374151;
    aspect-ratio: 1;
  }
  .image-preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .image-remove-btn {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: rgba(239, 68, 68, 0.9);
    color: white;
    border: none;
    border-radius: 50%;
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
  }
  .image-remove-btn:hover {
    background: rgba(239, 68, 68, 1);
    transform: scale(1.1);
  }
  .image-primary-badge {
    position: absolute;
    bottom: 0.5rem;
    left: 0.5rem;
    background: rgba(139, 92, 246, 0.9);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
  }
  .custom-select select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
  }
  .category-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #8b5cf6;
    z-index: 1;
  }
  .currency-input {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23a855f7'%3e%3cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1'/%3e%3c/svg%3e");
    background-position: right 1rem center;
    background-repeat: no-repeat;
    background-size: 1.25rem 1.25rem;
    padding-right: 3rem;
  }
  .danger-zone {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
  }
  .image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    border-radius: 0.75rem;
  }
  .product-image-preview:hover .image-overlay {
    opacity: 1;
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
    <h2 class="text-2xl font-bold">Edit Produk</h2>
    <p class="text-slate-400">Edit informasi produk "{{ $product->name }}"</p>
  </div>
  <div class="flex items-center gap-4">
    <a href="{{ route('products.show', $product) }}" class="glass-effect hover:bg-slate-700/50 px-4 py-2 rounded-xl flex items-center gap-2">
      <i class="fas fa-eye"></i>
      <span>Lihat Detail</span>
    </a>
    <a href="{{ route('products.index') }}" class="glass-effect hover:bg-slate-700/50 px-4 py-2 rounded-xl flex items-center gap-2">
      <i class="fas fa-arrow-left"></i>
      <span>Kembali</span>
    </a>
  </div>
</div>

<!-- Form -->
<div class="glass-effect rounded-2xl p-6 mb-6">
  <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" id="product-form">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Left Column -->
      <div class="space-y-6">
        <!-- Product Images -->
        <div>
          <label class="block text-sm font-medium mb-2">Gambar Produk <span class="text-slate-400">(Maksimal 5 gambar)</span></label>
          
          <!-- Image Upload Area -->
          <div class="product-image-preview" onclick="document.getElementById('image-input').click()" id="upload-area">
            <div class="text-center">
              <i class="fas fa-images text-4xl mb-2"></i>
              <p class="text-sm">Klik untuk pilih gambar</p>
              <p class="text-xs text-slate-500">JPG, PNG, WebP (Max: 2MB per gambar)</p>
            </div>
          </div>
          <input type="file" id="image-input" name="images[]" accept="image/*" class="file-input" multiple onchange="previewImages(this)">
          
          <!-- Existing Images Grid (if has existing images) -->
          @if($product->images && $product->images->count() > 0)
          <div id="existing-images-grid" class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4">
            @foreach($product->images as $index => $image)
            <div class="image-preview-item" data-existing-id="{{ $image->id }}">
              <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $product->name }}">
              <button type="button" class="image-remove-btn" onclick="removeExistingImage({{ $image->id }}, this)">
                <i class="fas fa-times"></i>
              </button>
              @if($index === 0)
                <div class="image-primary-badge">Utama</div>
              @else
                <button type="button" class="absolute bottom-2 right-2 bg-purple-600/80 hover:bg-purple-600 text-white px-2 py-1 rounded text-xs transition-all" onclick="setExistingPrimary({{ $image->id }})">
                  Set Utama
                </button>
              @endif
            </div>
            @endforeach
          </div>
          @endif
          
          <!-- New Images Preview Grid -->
          <div id="image-preview-grid" class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4 hidden">
            <!-- Preview images will be inserted here -->
          </div>
          
          <!-- Hidden inputs for removed images -->
          <div id="removed-images-container"></div>
          
          @error('images') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
          @error('images.*') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Product Name -->
        <div>
          <label class="block text-sm font-medium mb-2">Nama Produk <span class="text-red-400">*</span></label>
          <input type="text" name="name" value="{{ old('name', $product->name) }}" 
                 class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 focus:border-purple-500 focus:outline-none placeholder-slate-400" 
                 placeholder="Masukkan nama produk..." required>
          @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- SKU -->
        <div>
          <label class="block text-sm font-medium mb-2">SKU <span class="text-red-400">*</span></label>
          <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" 
                 class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 focus:border-purple-500 focus:outline-none placeholder-slate-400" 
                 placeholder="Masukkan SKU produk..." required>
          <p class="text-xs text-slate-500 mt-1">SKU tidak dapat diubah setelah ada transaksi</p>
          @error('sku') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Category -->
        <div>
          <label class="block text-sm font-medium mb-2">Kategori <span class="text-red-400">*</span></label>
          <div class="custom-select relative">
            <i class="fas fa-tags category-icon"></i>
            <select name="category_id" id="category_id" 
                    class="w-full bg-slate-800/50 border border-slate-600 rounded-xl pl-12 pr-4 py-3 focus:border-purple-500 focus:outline-none text-slate-200" required>
              <option value="" class="text-slate-400">-- Pilih Kategori --</option>
              @if(isset($categories))
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" 
                          {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }} 
                          class="bg-slate-800 text-slate-200">
                    {{ $category->name }}
                  </option>
                @endforeach
              @else
                <!-- Fallback for static categories if $categories not available -->
                <option value="elektronik" {{ old('category', $product->category) == 'elektronik' ? 'selected' : '' }}>Elektronik</option>
                <option value="fashion" {{ old('category', $product->category) == 'fashion' ? 'selected' : '' }}>Fashion</option>
                <option value="rumah_tangga" {{ old('category', $product->category) == 'rumah_tangga' ? 'selected' : '' }}>Rumah Tangga</option>
                <option value="olahraga" {{ old('category', $product->category) == 'olahraga' ? 'selected' : '' }}>Olahraga</option>
                <option value="kesehatan" {{ old('category', $product->category) == 'kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                <option value="makanan_minuman" {{ old('category', $product->category) == 'makanan_minuman' ? 'selected' : '' }}>Makanan & Minuman</option>
              @endif
            </select>
          </div>
          @error('category_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
          @error('category') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      <!-- Right Column -->
      <div class="space-y-6">
        <!-- Price -->
        <div>
          <label class="block text-sm font-medium mb-2">Harga <span class="text-red-400">*</span></label>
          <div class="relative">
            <span class="absolute left-4 top-3 text-slate-400 font-semibold">Rp</span>
            <input type="text" name="price" value="{{ old('price', number_format($product->price, 0, ',', '.')) }}" 
                   class="w-full bg-slate-800/50 border border-slate-600 rounded-xl pl-12 pr-4 py-3 focus:border-purple-500 focus:outline-none placeholder-slate-400 currency-input" 
                   placeholder="0" required id="price-input">
          </div>
          <p class="text-xs text-slate-400 mt-1">Contoh: 15000 untuk Rp 15.000</p>
          @error('price') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Stock -->
        <div>
          <label class="block text-sm font-medium mb-2">Stok <span class="text-red-400">*</span></label>
          <div class="relative">
            <i class="fas fa-boxes absolute left-4 top-3 text-purple-400"></i>
            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0"
                   class="w-full bg-slate-800/50 border border-slate-600 rounded-xl pl-12 pr-4 py-3 focus:border-purple-500 focus:outline-none placeholder-slate-400" 
                   placeholder="Jumlah stok..." required>
          </div>
          <p class="text-xs text-slate-400 mt-1">Stok saat ini: <span class="font-semibold">{{ $product->stock }}</span></p>
          @error('stock') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Status -->
        <div>
          <label class="block text-sm font-medium mb-2">Status</label>
          <div class="custom-select relative">
            <i class="fas fa-toggle-on category-icon"></i>
            <select name="status" 
                    class="w-full bg-slate-800/50 border border-slate-600 rounded-xl pl-12 pr-4 py-3 focus:border-purple-500 focus:outline-none text-slate-200">
              <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }} class="bg-slate-800 text-slate-200">Aktif</option>
              <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }} class="bg-slate-800 text-slate-200">Tidak Aktif</option>
            </select>
          </div>
          @error('status') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

<!-- Di dalam form, tambahkan bagian ukuran sepatu -->
<!-- Di dalam form, tambahkan bagian ukuran sepatu -->
<div class="mb-6">
  <h3 class="text-lg font-semibold mb-4">Ukuran Sepatu</h3>
  
  <div id="sizes-container" class="space-y-3">
    <!-- Existing sizes will be rendered here -->
    @foreach($product->sizes as $index => $size)
    <div class="size-input-row flex items-center gap-2" data-size-id="{{ $size->id }}">
      <input type="hidden" name="sizes[{{ $index }}][id]" value="{{ $size->id }}">
      <input type="text" name="sizes[{{ $index }}][size]" value="{{ $size->size }}" placeholder="Ukuran (misal: 38, 39, S, M, L)" 
             class="flex-1 bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 focus:border-purple-500 focus:outline-none"
             required>
      <input type="number" name="sizes[{{ $index }}][stock]" value="{{ $size->stock }}" placeholder="Stok" min="0"
             class="w-24 bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 focus:border-purple-500 focus:outline-none"
             required>
      <button type="button" onclick="removeSize(this)" class="text-red-400 hover:text-red-300">
        <i class="fas fa-trash"></i>
      </button>
    </div>
    @endforeach
  </div>
  
  <button type="button" onclick="addSize()" class="mt-3 text-purple-400 hover:text-purple-300 flex items-center gap-2">
    <i class="fas fa-plus"></i>
    <span>Tambah Ukuran</span>
  </button>
  
  <p class="text-xs text-slate-400 mt-2">Tambahkan atau ubah ukuran produk. Minimal satu ukuran harus diisi.</p>
</div>

        <!-- Product Stats -->
        <div class="glass-effect rounded-xl p-4">
          <h4 class="font-medium mb-3 flex items-center gap-2">
            <i class="fas fa-chart-line text-purple-400"></i>
            Statistik Produk
          </h4>
          <div class="space-y-2 text-sm">
            <div class="flex justify-between">
              <span class="text-slate-400">Dibuat:</span>
              <span>{{ $product->created_at->format('d M Y') }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-400">Terakhir diupdate:</span>
              <span>{{ $product->updated_at->format('d M Y') }}</span>
            </div>
            @if(isset($product->total_sold))
            <div class="flex justify-between">
              <span class="text-slate-400">Total terjual:</span>
              <span class="text-green-400 font-semibold">{{ $product->total_sold ?? 0 }}</span>
            </div>
            @endif
            @if(isset($product->views))
            <div class="flex justify-between">
              <span class="text-slate-400">Total views:</span>
              <span class="text-blue-400 font-semibold">{{ $product->views ?? 0 }}</span>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Description -->
    <div class="mt-6">
      <label class="block text-sm font-medium mb-2">Deskripsi Produk</label>
      <div class="relative">
        <i class="fas fa-align-left absolute left-4 top-4 text-purple-400"></i>
        <textarea name="description" rows="4" 
                  class="w-full bg-slate-800/50 border border-slate-600 rounded-xl pl-12 pr-4 py-3 focus:border-purple-500 focus:outline-none placeholder-slate-400 resize-none" 
                  placeholder="Masukkan deskripsi produk...">{{ old('description', $product->description) }}</textarea>
      </div>
      @error('description') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Form Actions -->
    <div class="flex justify-between items-center mt-8 pt-6 border-t border-slate-700">
      <!-- Left side - Last updated info -->
      <div class="text-sm text-slate-400">
        <i class="fas fa-clock mr-1"></i>
        Terakhir diupdate: {{ $product->updated_at->diffForHumans() }}
      </div>
      
      <!-- Right side - Action buttons -->
      <div class="flex gap-4">
        <a href="{{ route('products.index') }}" 
           class="glass-effect hover:bg-slate-700/50 px-6 py-3 rounded-xl font-semibold transition-all flex items-center gap-2">
          <i class="fas fa-times"></i>
          <span>Batal</span>
        </a>
        <button type="submit" 
                class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 flex items-center gap-2">
          <i class="fas fa-save"></i>
          <span>Update Produk</span>
        </button>
      </div>
    </div>
  </form>
</div>

<!-- Danger Zone -->
<div class="danger-zone rounded-2xl p-6">
  <div class="flex items-center justify-between">
    <div>
      <h3 class="text-lg font-semibold text-red-400 mb-2 flex items-center gap-2">
        <i class="fas fa-exclamation-triangle"></i>
        Danger Zone
      </h3>
      <p class="text-slate-400 text-sm">Aksi ini tidak dapat dibatalkan. Produk akan dihapus secara permanen.</p>
    </div>
    <button type="button" onclick="deleteProduct()" 
            class="bg-red-600 hover:bg-red-700 px-6 py-3 rounded-xl font-semibold transition-all flex items-center gap-2 transform hover:scale-105">
      <i class="fas fa-trash"></i>
      <span>Hapus Produk</span>
    </button>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

// Fungsi untuk menambahkan ukuran baru
let sizeIndex = {{ $product->sizes->count() }}; // Mulai dari jumlah ukuran yang sudah ada

function addSize() {
  const container = document.getElementById('sizes-container');
  
  // Batasi maksimal 10 ukuran
  if (sizeIndex >= 10) {
    Swal.fire({
      title: 'Batas Maksimal Tercapai',
      text: 'Anda hanya dapat menambahkan maksimal 10 ukuran!',
      icon: 'warning',
      confirmButtonColor: '#8b5cf6'
    });
    return;
  }
  
  const sizeDiv = document.createElement('div');
  sizeDiv.className = 'size-input-row flex items-center gap-2';
  sizeDiv.innerHTML = `
    <input type="text" name="sizes[${sizeIndex}][size]" placeholder="Ukuran (misal: 38, 39, S, M, L)" 
           class="flex-1 bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 focus:border-purple-500 focus:outline-none"
           required>
    <input type="number" name="sizes[${sizeIndex}][stock]" placeholder="Stok" min="0"
           class="w-24 bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 focus:border-purple-500 focus:outline-none"
           required>
    <button type="button" onclick="removeSize(this)" class="text-red-400 hover:text-red-300">
      <i class="fas fa-trash"></i>
    </button>
  `;
  
  container.appendChild(sizeDiv);
  sizeIndex++;
}

// Fungsi untuk menghapus ukuran
function removeSize(button) {
  const container = document.getElementById('sizes-container');
  const row = button.closest('.size-input-row');
  
  // Pastikan setidaknya satu input ukuran tersisa
  if (container.querySelectorAll('.size-input-row').length <= 1) {
    Swal.fire({
      title: 'Tidak Dapat Menghapus',
      text: 'Setidaknya harus ada satu ukuran produk!',
      icon: 'warning',
      confirmButtonColor: '#8b5cf6'
    });
    return;
  }
  
  // Jika ini ukuran yang sudah ada (memiliki id), tandai untuk dihapus
  const sizeId = row.dataset.sizeId;
  if (sizeId) {
    // Tambahkan input hidden untuk menandai penghapusan
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'removed_sizes[]';
    hiddenInput.value = sizeId;
    container.appendChild(hiddenInput);
  }
  
  row.remove();
  
  // Update indeks untuk semua input yang tersisa
  updateSizeIndices();
}

// Fungsi untuk memperbarui indeks input ukuran
function updateSizeIndices() {
  const container = document.getElementById('sizes-container');
  const rows = container.querySelectorAll('.size-input-row');
  
  rows.forEach((row, index) => {
    // Hapus input id yang lama jika ada
    const oldIdInput = row.querySelector('input[type="hidden"]');
    if (oldIdInput && oldIdInput.name === 'sizes[index][id]') {
      oldIdInput.remove();
    }
    
    // Tambahkan input id untuk ukuran yang sudah ada
    const sizeId = row.dataset.sizeId;
    if (sizeId) {
      const idInput = document.createElement('input');
      idInput.type = 'hidden';
      idInput.name = `sizes[${index}][id]`;
      idInput.value = sizeId;
      row.insertBefore(idInput, row.firstChild);
    }
    
    // Update nama input ukuran dan stok
    const sizeInput = row.querySelector('input[name*="[size]"]');
    const stockInput = row.querySelector('input[name*="[stock]"]');
    
    if (sizeInput) sizeInput.name = `sizes[${index}][size]`;
    if (stockInput) stockInput.name = `sizes[${index}][stock]`;
  });
  
  // Update global sizeIndex
  sizeIndex = rows.length;
}

// Validasi form sebelum submit
document.getElementById('product-form').addEventListener('submit', function(e) {
  // Validasi ukuran
  const sizeRows = document.querySelectorAll('.size-input-row');
  let hasValidSize = false;
  
  sizeRows.forEach(row => {
    const sizeInput = row.querySelector('input[name*="[size]"]');
    const stockInput = row.querySelector('input[name*="[stock]"]');
    
    if (sizeInput.value.trim() && stockInput.value && parseInt(stockInput.value) >= 0) {
      hasValidSize = true;
    }
  });
  
  if (!hasValidSize) {
    e.preventDefault();
    Swal.fire({
      title: 'Ukuran Tidak Valid',
      text: 'Pastikan Anda telah memasukkan setidaknya satu ukuran dengan stok yang valid!',
      icon: 'warning',
      confirmButtonColor: '#8b5cf6'
    });
    return;
  }
  
  // Validasi lainnya...
  const name = document.querySelector('input[name="name"]').value;
  const price = parseIDR(document.querySelector('#price-input').value);
  const stock = document.querySelector('input[name="stock"]').value;
  const category = document.querySelector('select[name="category_id"], select[name="category"]').value;
  
  if (!name || !price || !stock || !category) {
    e.preventDefault();
    Swal.fire({
      title: 'Form Tidak Lengkap',
      text: 'Mohon lengkapi semua field yang wajib diisi!',
      icon: 'warning',
      confirmButtonColor: '#8b5cf6'
    });
    return;
  }
  
  if (parseFloat(price) <= 0) {
    e.preventDefault();
    Swal.fire({
      title: 'Harga Tidak Valid',
      text: 'Harga produk harus lebih dari 0!',
      icon: 'warning',
      confirmButtonColor: '#8b5cf6'
    });
    return;
  }
  
  if (parseInt(stock) < 0) {
    e.preventDefault();
    Swal.fire({
      title: 'Stok Tidak Valid',
      text: 'Stok produk tidak boleh negatif!',
      icon: 'warning',
      confirmButtonColor: '#8b5cf6'
    });
    return;
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

// Multiple images functionality
let selectedImages = [];
let imageCounter = 0;
let removedImageIds = [];

// Initialize existing images count
let existingImagesCount = {{ $product->images ? $product->images->count() : 0 }};

function previewImages(input) {
  const files = Array.from(input.files);
  const maxImages = 5;
  const currentTotal = existingImagesCount + selectedImages.length;
  
  // Check if adding new images would exceed limit
  if (currentTotal + files.length > maxImages) {
    Swal.fire({
      title: 'Batas Maksimal Tercapai',
      text: `Anda hanya dapat mengunggah maksimal ${maxImages} gambar!`,
      icon: 'warning',
      confirmButtonColor: '#8b5cf6'
    });
    return;
  }
  
  files.forEach(file => {
    if (file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const imageId = 'img-' + (++imageCounter);
        selectedImages.push({
          id: imageId,
          file: file,
          url: e.target.result,
          isPrimary: selectedImages.length === 0 && existingImagesCount === 0
        });
        
        updateImagePreview();
      };
      reader.readAsDataURL(file);
    }
  });
  
  // Clear input for next selection
  input.value = '';
}

function updateImagePreview() {
  const grid = document.getElementById('image-preview-grid');
  const uploadArea = document.getElementById('upload-area');
  
  if (selectedImages.length > 0) {
    grid.classList.remove('hidden');
    
    // Hide upload area if we have max images
    if (existingImagesCount + selectedImages.length >= 5) {
      uploadArea.style.display = 'none';
    }
    
    grid.innerHTML = selectedImages.map((img, index) => `
      <div class="image-preview-item">
        <img src="${img.url}" alt="Preview ${index + 1}">
        <button type="button" class="image-remove-btn" onclick="removeImage('${img.id}')">
          <i class="fas fa-times"></i>
        </button>
        ${img.isPrimary ? '<div class="image-primary-badge">Utama</div>' : ''}
        ${!img.isPrimary ? `<button type="button" class="absolute bottom-2 right-2 bg-purple-600/80 hover:bg-purple-600 text-white px-2 py-1 rounded text-xs transition-all" onclick="setPrimaryImage('${img.id}')">Set Utama</button>` : ''}
      </div>
    `).join('');
  } else {
    grid.classList.add('hidden');
    if (existingImagesCount < 5) {
      uploadArea.style.display = 'flex';
    }
  }
  
  updateFormData();
}

function removeImage(imageId) {
  selectedImages = selectedImages.filter(img => img.id !== imageId);
  
  // If we removed the primary image and no existing primary, make the first remaining image primary
  if (selectedImages.length > 0 && !selectedImages.some(img => img.isPrimary) && existingImagesCount === 0) {
    selectedImages[0].isPrimary = true;
  }
  
  updateImagePreview();
}

function setPrimaryImage(imageId) {
  // Remove primary status from existing images
  document.querySelectorAll('#existing-images-grid .image-primary-badge').forEach(badge => {
    badge.remove();
  });
  document.querySelectorAll('#existing-images-grid [onclick^="setExistingPrimary"]').forEach(btn => {
    btn.style.display = 'block';
  });
  
  // Set primary for new images
  selectedImages.forEach(img => {
    img.isPrimary = img.id === imageId;
  });
  updateImagePreview();
}

function removeExistingImage(imageId, button) {
  const imageItem = button.closest('.image-preview-item');
  const wasPrimary = imageItem.querySelector('.image-primary-badge') !== null;
  
  // Add to removed list
  removedImageIds.push(imageId);
  existingImagesCount--;
  
  // Remove from DOM
  imageItem.remove();
  
  // Update hidden input
  updateRemovedImagesInput();
  
  // If removed image was primary, set new primary
  if (wasPrimary) {
    const remainingExisting = document.querySelectorAll('#existing-images-grid .image-preview-item');
    if (remainingExisting.length > 0) {
      // Set first remaining existing image as primary
      setExistingPrimary(remainingExisting[0].dataset.existingId, true);
    } else if (selectedImages.length > 0) {
      // Set first new image as primary
      selectedImages[0].isPrimary = true;
      updateImagePreview();
    }
  }
  
  // Show upload area if under limit
  if (existingImagesCount + selectedImages.length < 5) {
    document.getElementById('upload-area').style.display = 'flex';
  }
}

function setExistingPrimary(imageId, skipUpdate = false) {
  // Remove primary badges from existing images
  document.querySelectorAll('#existing-images-grid .image-primary-badge').forEach(badge => {
    badge.remove();
  });
  
  // Show "Set Utama" buttons on existing images
  document.querySelectorAll('#existing-images-grid [onclick^="setExistingPrimary"]').forEach(btn => {
    btn.style.display = 'block';
  });
  
  // Add primary badge to selected image
  const selectedItem = document.querySelector(`#existing-images-grid [data-existing-id="${imageId}"]`);
  if (selectedItem) {
    const badge = document.createElement('div');
    badge.className = 'image-primary-badge';
    badge.textContent = 'Utama';
    selectedItem.appendChild(badge);
    
    // Hide "Set Utama" button for this image
    const setBtn = selectedItem.querySelector('[onclick^="setExistingPrimary"]');
    if (setBtn) setBtn.style.display = 'none';
  }
  
  // Remove primary from new images
  if (!skipUpdate) {
    selectedImages.forEach(img => {
      img.isPrimary = false;
    });
    updateImagePreview();
  }
}

function updateRemovedImagesInput() {
  const container = document.getElementById('removed-images-container');
  container.innerHTML = removedImageIds.map(id => 
    `<input type="hidden" name="removed_images[]" value="${id}">`
  ).join('');
}

function updateFormData() {
  // Remove existing hidden inputs for new images
  document.querySelectorAll('input[name="images[]"]').forEach(input => {
    if (input.type === 'hidden') input.remove();
  });
  
  // Create DataTransfer object to set files
  const dt = new DataTransfer();
  
  // Sort images so primary image is first
  const sortedImages = [...selectedImages].sort((a, b) => {
    if (a.isPrimary) return -1;
    if (b.isPrimary) return 1;
    return 0;
  });
  
  sortedImages.forEach(img => {
    dt.items.add(img.file);
  });
  
  // Update the file input
  document.getElementById('image-input').files = dt.files;
}

// Format IDR currency input
function formatIDR(number) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(number).replace('IDR', 'Rp');
}

function parseIDR(value) {
  return parseInt(value.replace(/[^\d]/g, '')) || 0;
}

// Price input formatting
const priceInput = document.getElementById('price-input');
let isFormatting = false;

priceInput.addEventListener('input', function(e) {
  if (isFormatting) return;
  
  isFormatting = true;
  
  // Get cursor position
  const cursorPosition = e.target.selectionStart;
  const oldValue = e.target.value;
  
  // Remove all non-numeric characters
  let numericValue = oldValue.replace(/[^\d]/g, '');
  
  // Convert to number and format
  if (numericValue) {
    const number = parseInt(numericValue);
    const formatted = formatIDR(number);
    e.target.value = formatted;
    
    // Adjust cursor position
    const newCursorPosition = cursorPosition + (formatted.length - oldValue.length);
    setTimeout(() => {
      e.target.setSelectionRange(newCursorPosition, newCursorPosition);
    }, 0);
  }
  
  isFormatting = false;
});

// Form submission handler
document.getElementById('product-form').addEventListener('submit', function(e) {
  // Convert formatted price back to numeric value
  const priceFormatted = priceInput.value;
  const priceNumeric = parseIDR(priceFormatted);
  
  // Create hidden input with numeric value
  const hiddenPriceInput = document.createElement('input');
  hiddenPriceInput.type = 'hidden';
  hiddenPriceInput.name = 'price';
  hiddenPriceInput.value = priceNumeric;
  this.appendChild(hiddenPriceInput);
  
  // Remove name from visible input to avoid conflict
  priceInput.removeAttribute('name');

  const name = document.querySelector('input[name="name"]').value;
  const price = priceNumeric;
  const stock = document.querySelector('input[name="stock"]').value;
  const category = document.querySelector('select[name="category_id"], select[name="category"]').value;

  if (!name || !price || !stock || !category) {
    e.preventDefault();
    Swal.fire({
      title: 'Form Tidak Lengkap',
      text: 'Mohon lengkapi semua field yang wajib diisi!',
      icon: 'warning',
      confirmButtonColor: '#8b5cf6'
    });
    return;
  }

  if (parseFloat(price) <= 0) {
    e.preventDefault();
    Swal.fire({
      title: 'Harga Tidak Valid',
      text: 'Harga produk harus lebih dari 0!',
      icon: 'warning',
      confirmButtonColor: '#8b5cf6'
    });
    return;
  }

  if (parseInt(stock) < 0) {
    e.preventDefault();
    Swal.fire({
      title: 'Stok Tidak Valid',
      text: 'Stok produk tidak boleh negatif!',
      icon: 'warning',
      confirmButtonColor: '#8b5cf6'
    });
    return;
  }

  // Show loading
  Swal.fire({
    title: 'Menyimpan Perubahan...',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });
});

// Delete product function
function deleteProduct() {
  Swal.fire({
    title: 'Hapus Produk?',
    text: `Apakah Anda yakin ingin menghapus "{{ $product->name }}"? Aksi ini tidak dapat dibatalkan!`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal',
    input: 'text',
    inputPlaceholder: 'Ketik "HAPUS" untuk konfirmasi',
    inputValidator: (value) => {
      if (value !== 'HAPUS') {
        return 'Ketik "HAPUS" untuk mengkonfirmasi penghapusan!';
      }
    }
  }).then((result) => {
    if (result.isConfirmed) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '{{ route("products.destroy", $product) }}';
      form.innerHTML = `
        @csrf
        @method('DELETE')
      `;
      document.body.appendChild(form);
      form.submit();
    }
  });
}

// Drag and drop functionality for images
const imagePreview = document.getElementById('upload-area');
const imageInput = document.getElementById('image-input');

// Prevent default drag behaviors
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
  imagePreview.addEventListener(eventName, preventDefaults, false);
  document.body.addEventListener(eventName, preventDefaults, false);
});

// Highlight drop area when item is dragged over it
['dragenter', 'dragover'].forEach(eventName => {
  imagePreview.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
  imagePreview.addEventListener(eventName, unhighlight, false);
});

// Handle dropped files
imagePreview.addEventListener('drop', handleDrop, false);

function preventDefaults(e) {
  e.preventDefault();
  e.stopPropagation();
}

function highlight(e) {
  if (imagePreview.style.display !== 'none') {
    imagePreview.style.borderColor = '#8b5cf6';
    imagePreview.style.backgroundColor = '#4c1d95';
  }
}

function unhighlight(e) {
  if (imagePreview.style.display !== 'none') {
    imagePreview.style.borderColor = '#4b5563';
    imagePreview.style.backgroundColor = '#374151';
  }
}

function handleDrop(e) {
  const dt = e.dataTransfer;
  const files = dt.files;
  
  if (files.length > 0 && existingImagesCount + selectedImages.length < 5) {
    // Create a new input event to simulate file selection
    const fileArray = Array.from(files);
    const validFiles = fileArray.filter(file => file.type.startsWith('image/'));
    
    if (validFiles.length > 0) {
      // Create new DataTransfer object
      const newDt = new DataTransfer();
      validFiles.forEach(file => newDt.items.add(file));
      
      imageInput.files = newDt.files;
      previewImages(imageInput);
    }
  }
}

// Detect unsaved changes
let formChanged = false;
const formInputs = document.querySelectorAll('#product-form input, #product-form select, #product-form textarea');

formInputs.forEach(input => {
  input.addEventListener('change', () => {
    formChanged = true;
  });
});

// Warn before leaving if there are unsaved changes
window.addEventListener('beforeunload', (e) => {
  if (formChanged) {
    e.preventDefault();
    e.returnValue = '';
  }
});

// Reset formChanged when form is submitted
document.getElementById('product-form').addEventListener('submit', () => {
  formChanged = false;
});

// Initialize price formatting on page load
document.addEventListener('DOMContentLoaded', function() {
  const priceValue = priceInput.value.replace(/[^\d]/g, '');
  if (priceValue && !isNaN(priceValue)) {
    priceInput.value = formatIDR(parseInt(priceValue));
  }
  
  // Initialize upload area visibility
  if (existingImagesCount >= 5) {
    document.getElementById('upload-area').style.display = 'none';
  }
});
@endsection