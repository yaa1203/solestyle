@extends('admin.layouts.app')

@section('title', 'Tambah Produk')
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
  .image-preview-container {
    position: relative;
    border-radius: 0.75rem;
    overflow: hidden;
    background-color: #374151;
  }
  .image-preview-container img {
    width: 100%;
    height: 200px;
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
    <h2 class="text-2xl font-bold">Tambah Produk Baru</h2>
    <p class="text-slate-400">Tambahkan produk baru ke toko Anda</p>
  </div>
  <div class="flex items-center gap-4">
    <a href="{{ route('products.index') }}" class="glass-effect hover:bg-slate-700/50 px-4 py-2 rounded-xl flex items-center gap-2">
      <i class="fas fa-arrow-left"></i>
      <span>Kembali</span>
    </a>
  </div>
</div>

<!-- Form -->
<div class="glass-effect rounded-2xl p-6">
  <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="product-form">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Left Column -->
      <div class="space-y-6">
        <!-- Product Image -->
        <div>
          <label class="block text-sm font-medium mb-2">Gambar Produk</label>
          
          <!-- Image Upload Area -->
          <div id="image-upload-area" class="product-image-preview" onclick="document.getElementById('image-input').click()">
            <div class="text-center">
              <i class="fas fa-image text-4xl mb-2"></i>
              <p class="text-sm">Klik untuk pilih gambar</p>
              <p class="text-xs text-slate-500">JPG, PNG, WebP (Max: 2MB)</p>
            </div>
          </div>
          
          <!-- Image Preview -->
          <div id="image-preview" class="image-preview-container hidden">
            <img id="preview-image" src="" alt="Preview">
            <button type="button" class="image-remove-btn" onclick="removeImage()">
              <i class="fas fa-times"></i>
            </button>
          </div>
          
          <input type="file" id="image-input" name="image" accept="image/*" class="file-input" onchange="previewImage(this)">
          
          @error('image') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Product Name -->
        <div>
          <label class="block text-sm font-medium mb-2">Nama Produk <span class="text-red-400">*</span></label>
          <input type="text" name="name" value="{{ old('name') }}" 
                 class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 focus:border-purple-500 focus:outline-none placeholder-slate-400" 
                 placeholder="Masukkan nama produk..." required>
          @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- SKU -->
        <div>
          <label class="block text-sm font-medium mb-2">SKU <span class="text-red-400">*</span></label>
          <input type="text" name="sku" value="{{ old('sku') }}" 
                 class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 focus:border-purple-500 focus:outline-none placeholder-slate-400" 
                 placeholder="Masukkan SKU produk..." required>
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
              @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }} class="bg-slate-800 text-slate-200">
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
          </div>
          @error('category_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      <!-- Right Column -->
      <div class="space-y-6">
        <!-- Price -->
        <div>
          <label class="block text-sm font-medium mb-2">Harga <span class="text-red-400">*</span></label>
          <div class="relative">
            <span class="absolute left-4 top-3 text-slate-400 font-semibold">Rp</span>
            <input type="text" name="price" value="{{ old('price') }}" 
                   class="w-full bg-slate-800/50 border border-slate-600 rounded-xl pl-12 pr-4 py-3 focus:border-purple-500 focus:outline-none placeholder-slate-400 currency-input" 
                   placeholder="0" required id="price-input">
          </div>
          <p class="text-xs text-slate-400 mt-1">Contoh: 15000 untuk Rp 15.000</p>
          @error('price') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Status -->
        <div>
          <label class="block text-sm font-medium mb-2">Status</label>
          <div class="custom-select relative">
            <i class="fas fa-toggle-on category-icon"></i>
            <select name="status" 
                    class="w-full bg-slate-800/50 border border-slate-600 rounded-xl pl-12 pr-4 py-3 focus:border-purple-500 focus:outline-none text-slate-200">
              <option value="active" {{ old('status') == 'active' ? 'selected' : '' }} class="bg-slate-800 text-slate-200">
                Aktif
              </option>
              <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }} class="bg-slate-800 text-slate-200">
                Tidak Aktif
              </option>
            </select>
          </div>
          @error('status') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
      </div>
    </div>

    <!-- Ukuran Sepatu -->
    <div class="mt-6">
      <h3 class="text-lg font-semibold mb-4">Ukuran Sepatu</h3>
      
      <div id="sizes-container" class="space-y-3">
        <div class="size-input-row flex items-center gap-2">
          <input type="text" name="sizes[0][size]" placeholder="Ukuran (misal: 38, 39, S, M, L)" 
                 class="flex-1 bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 focus:border-purple-500 focus:outline-none"
                 required>
          <input type="number" name="sizes[0][stock]" placeholder="Stok" min="0"
                 class="w-24 bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 focus:border-purple-500 focus:outline-none"
                 required>
          <button type="button" onclick="removeSize(this)" class="text-red-400 hover:text-red-300">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>
      
      <button type="button" onclick="addSize()" class="mt-3 text-purple-400 hover:text-purple-300 flex items-center gap-2">
        <i class="fas fa-plus"></i>
        <span>Tambah Ukuran</span>
      </button>
      
      <p class="text-xs text-slate-400 mt-2">Tambahkan setidaknya satu ukuran untuk produk ini</p>
    </div>

    <!-- Description -->
    <div class="mt-6">
      <label class="block text-sm font-medium mb-2">Deskripsi Produk</label>
      <div class="relative">
        <i class="fas fa-align-left absolute left-4 top-4 text-purple-400"></i>
        <textarea name="description" rows="4" 
                  class="w-full bg-slate-800/50 border border-slate-600 rounded-xl pl-12 pr-4 py-3 focus:border-purple-500 focus:outline-none placeholder-slate-400 resize-none" 
                  placeholder="Masukkan deskripsi produk...">{{ old('description') }}</textarea>
      </div>
      @error('description') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Form Actions -->
    <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-slate-700">
      <a href="{{ route('products.index') }}" 
         class="glass-effect hover:bg-slate-700/50 px-6 py-3 rounded-xl font-semibold transition-all flex items-center gap-2">
        <i class="fas fa-times"></i>
        <span>Batal</span>
      </a>
      <button type="submit" 
              class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 flex items-center gap-2">
        <i class="fas fa-save"></i>
        <span>Simpan Produk</span>
      </button>
    </div>
  </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

// Single image preview functionality
function previewImage(input) {
  const file = input.files[0];
  const uploadArea = document.getElementById('image-upload-area');
  const previewContainer = document.getElementById('image-preview');
  const previewImage = document.getElementById('preview-image');
  
  if (file && file.type.startsWith('image/')) {
    const reader = new FileReader();
    reader.onload = function(e) {
      previewImage.src = e.target.result;
      uploadArea.classList.add('hidden');
      previewContainer.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
  }
}

function removeImage() {
  const uploadArea = document.getElementById('image-upload-area');
  const previewContainer = document.getElementById('image-preview');
  const imageInput = document.getElementById('image-input');
  
  uploadArea.classList.remove('hidden');
  previewContainer.classList.add('hidden');
  imageInput.value = '';
}

// Fungsi untuk menambahkan input ukuran baru
let sizeIndex = 1;

function addSize() {
  const container = document.getElementById('sizes-container');
  
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

function removeSize(button) {
  const container = document.getElementById('sizes-container');
  
  if (container.children.length <= 1) {
    Swal.fire({
      title: 'Tidak Dapat Menghapus',
      text: 'Setidaknya harus ada satu ukuran produk!',
      icon: 'warning',
      confirmButtonColor: '#8b5cf6'
    });
    return;
  }
  
  button.parentElement.remove();
  updateSizeIndices();
}

function updateSizeIndices() {
  const container = document.getElementById('sizes-container');
  const rows = container.querySelectorAll('.size-input-row');
  
  rows.forEach((row, index) => {
    const inputs = row.querySelectorAll('input');
    inputs[0].name = `sizes[${index}][size]`;
    inputs[1].name = `sizes[${index}][stock]`;
  });
  
  sizeIndex = rows.length;
}

// Auto-generate SKU from product name
let skuCounter = 1;

document.querySelector('input[name="name"]').addEventListener('input', function() {
  const skuField = document.querySelector('input[name="sku"]');
  if (!skuField.value || skuField.dataset.autoGenerated === 'true') {
    const productName = this.value.trim();
    if (productName.length >= 3) {
      const prefix = productName.substring(0, 3).toUpperCase();
      const number = String(skuCounter).padStart(3, '0');
      const sku = `${prefix}-${number}`;
      
      skuField.value = sku;
      skuField.dataset.autoGenerated = 'true';
      skuCounter++;
    } else {
      skuField.value = '';
      skuField.dataset.autoGenerated = 'false';
    }
  }
});

document.querySelector('input[name="sku"]').addEventListener('input', function() {
  this.dataset.autoGenerated = 'false';
});

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
  
  const cursorPosition = e.target.selectionStart;
  const oldValue = e.target.value;
  
  let numericValue = oldValue.replace(/[^\d]/g, '');
  
  if (numericValue) {
    const number = parseInt(numericValue);
    const formatted = formatIDR(number);
    e.target.value = formatted;
    
    const newCursorPosition = cursorPosition + (formatted.length - oldValue.length);
    setTimeout(() => {
      e.target.setSelectionRange(newCursorPosition, newCursorPosition);
    }, 0);
  }
  
  isFormatting = false;
});

// Form validation and submission
document.getElementById('product-form').addEventListener('submit', function(e) {
  // Convert formatted price back to numeric value
  const priceFormatted = priceInput.value;
  const priceNumeric = parseIDR(priceFormatted);
  
  // Create hidden input with numeric value
  const hiddenPriceInput = document.createElement('input');
  hiddenPriceInput.type = 'hidden';
  hiddenPriceInput.name = 'price';
  hiddenPriceInput.value = priceNumeric;
  
  priceInput.removeAttribute('name');
  this.appendChild(hiddenPriceInput);

  // Validate sizes
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

  // Basic validation
  const name = document.querySelector('input[name="name"]').value;
  const category = document.querySelector('select[name="category_id"]').value;

  if (!name || !priceNumeric || !category) {
    e.preventDefault();
    Swal.fire({
      title: 'Form Tidak Lengkap',
      text: 'Mohon lengkapi semua field yang wajib diisi!',
      icon: 'warning',
      confirmButtonColor: '#8b5cf6'
    });
    return;
  }

  if (priceNumeric <= 0) {
    e.preventDefault();
    Swal.fire({
      title: 'Harga Tidak Valid',
      text: 'Harga produk harus lebih dari 0!',
      icon: 'warning',
      confirmButtonColor: '#8b5cf6'
    });
    return;
  }
});

// Drag and drop functionality for single image
const imageUploadArea = document.getElementById('image-upload-area');
const imageInput = document.getElementById('image-input');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
  imageUploadArea.addEventListener(eventName, preventDefaults, false);
  document.body.addEventListener(eventName, preventDefaults, false);
});

['dragenter', 'dragover'].forEach(eventName => {
  imageUploadArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
  imageUploadArea.addEventListener(eventName, unhighlight, false);
});

imageUploadArea.addEventListener('drop', handleDrop, false);

function preventDefaults(e) {
  e.preventDefault();
  e.stopPropagation();
}

function highlight(e) {
  imageUploadArea.style.borderColor = '#8b5cf6';
  imageUploadArea.style.backgroundColor = '#4c1d95';
}

function unhighlight(e) {
  imageUploadArea.style.borderColor = '#4b5563';
  imageUploadArea.style.backgroundColor = '#374151';
}

function handleDrop(e) {
  const dt = e.dataTransfer;
  const files = dt.files;
  
  if (files.length > 0) {
    const file = files[0];
    if (file.type.startsWith('image/')) {
      const newDt = new DataTransfer();
      newDt.items.add(file);
      imageInput.files = newDt.files;
      previewImage(imageInput);
    }
  }
}

// Auto hide alerts
setTimeout(() => {
  const alerts = document.querySelectorAll('#success-alert, #error-alert');
  alerts.forEach(alert => {
    alert.style.opacity = '0';
    setTimeout(() => alert.remove(), 300);
  });
}, 5000);

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
  const priceValue = priceInput.value;
  if (priceValue && !isNaN(priceValue)) {
    priceInput.value = formatIDR(parseInt(priceValue));
  }
  
  skuCounter = 1;
});
</script>
@endsection