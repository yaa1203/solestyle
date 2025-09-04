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
        <!-- Product Images -->
        <div>
          <label class="block text-sm font-medium mb-2">Gambar Produk <span class="text-slate-400">(Maksimal 5 gambar)</span></label>
          
          <!-- Image Upload Area -->
          <div class="product-image-preview" onclick="document.getElementById('image-input').click()">
            <div class="text-center">
              <i class="fas fa-images text-4xl mb-2"></i>
              <p class="text-sm">Klik untuk pilih gambar</p>
              <p class="text-xs text-slate-500">JPG, PNG, WebP (Max: 2MB per gambar)</p>
            </div>
          </div>
          <input type="file" id="image-input" name="images[]" accept="image/*" class="file-input" multiple onchange="previewImages(this)">
          
          <!-- Image Preview Grid -->
          <div id="image-preview-grid" class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4 hidden">
            <!-- Preview images will be inserted here -->
          </div>
          
          @error('images') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
          @error('images.*') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
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

        <!-- Stock -->
        <div>
          <label class="block text-sm font-medium mb-2">Stok <span class="text-red-400">*</span></label>
          <div class="relative">
            <i class="fas fa-boxes absolute left-4 top-3 text-purple-400"></i>
            <input type="number" name="stock" value="{{ old('stock') }}" min="0"
                   class="w-full bg-slate-800/50 border border-slate-600 rounded-xl pl-12 pr-4 py-3 focus:border-purple-500 focus:outline-none placeholder-slate-400" 
                   placeholder="Jumlah stok..." required>
          </div>
          @error('stock') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Status -->
        <div>
          <label class="block text-sm font-medium mb-2">Status</label>
          <div class="custom-select relative">
            <i class="fas fa-toggle-on category-icon"></i>
            <select name="status" 
                    class="w-full bg-slate-800/50 border border-slate-600 rounded-xl pl-12 pr-4 py-3 focus:border-purple-500 focus:outline-none text-slate-200">
              <option value="active" {{ old('status') == 'active' ? 'selected' : '' }} class="bg-slate-800 text-slate-200">
                <i class="fas fa-check"></i> Aktif
              </option>
              <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }} class="bg-slate-800 text-slate-200">
                <i class="fas fa-times"></i> Tidak Aktif
              </option>
            </select>
          </div>
          @error('status') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
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
// Auto hide alerts
setTimeout(() => {
  const alerts = document.querySelectorAll('#success-alert, #error-alert');
  alerts.forEach(alert => {
    alert.style.opacity = '0';
    setTimeout(() => alert.remove(), 300);
  });
}, 5000);

// Multiple images preview functionality
let selectedImages = [];
let imageCounter = 0;

function previewImages(input) {
  const files = Array.from(input.files);
  const maxImages = 5;
  
  // Check if adding new images would exceed limit
  if (selectedImages.length + files.length > maxImages) {
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
          isPrimary: selectedImages.length === 0
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
  const uploadArea = document.querySelector('.product-image-preview');
  
  if (selectedImages.length > 0) {
    grid.classList.remove('hidden');
    uploadArea.style.display = 'none';
    
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
    uploadArea.style.display = 'flex';
  }
  
  updateFormData();
}

function removeImage(imageId) {
  selectedImages = selectedImages.filter(img => img.id !== imageId);
  
  // If we removed the primary image, make the first remaining image primary
  if (selectedImages.length > 0 && !selectedImages.some(img => img.isPrimary)) {
    selectedImages[0].isPrimary = true;
  }
  
  updateImagePreview();
}

function setPrimaryImage(imageId) {
  selectedImages.forEach(img => {
    img.isPrimary = img.id === imageId;
  });
  updateImagePreview();
}

function updateFormData() {
  // Remove existing hidden inputs
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

// Auto-generate SKU from product name
let skuCounter = 1;

document.querySelector('input[name="name"]').addEventListener('input', function() {
  const skuField = document.querySelector('input[name="sku"]');
  if (!skuField.value || skuField.dataset.autoGenerated === 'true') {
    const productName = this.value.trim();
    if (productName.length >= 3) {
      // Get first 3 characters and convert to uppercase
      const prefix = productName.substring(0, 3).toUpperCase();
      // Generate sequential number with leading zeros
      const number = String(skuCounter).padStart(3, '0');
      const sku = `${prefix}-${number}`;
      
      skuField.value = sku;
      skuField.dataset.autoGenerated = 'true';
      
      // Increment counter for next product
      skuCounter++;
    } else {
      skuField.value = '';
      skuField.dataset.autoGenerated = 'false';
    }
  }
});

// Allow manual SKU editing
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

// On form submit, convert formatted price back to numeric value
document.getElementById('product-form').addEventListener('submit', function(e) {
  const priceFormatted = priceInput.value;
  const priceNumeric = parseIDR(priceFormatted);
  
  // Create hidden input with numeric value
  const hiddenPriceInput = document.createElement('input');
  hiddenPriceInput.type = 'hidden';
  hiddenPriceInput.name = 'price';
  hiddenPriceInput.value = priceNumeric;
  
  // Remove name from visible input to avoid conflict
  priceInput.removeAttribute('name');
  
  // Add hidden input to form
  this.appendChild(hiddenPriceInput);
});

// Form validation
document.getElementById('product-form').addEventListener('submit', function(e) {
  const name = document.querySelector('input[name="name"]').value;
  const price = parseIDR(document.querySelector('#price-input').value);
  const stock = document.querySelector('input[name="stock"]').value;
  const category = document.querySelector('select[name="category_id"]').value;

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

  if (price <= 0) {
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

// Drag and drop functionality for multiple images
const imagePreview = document.querySelector('.product-image-preview');
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
  if (selectedImages.length === 0) {
    imagePreview.style.borderColor = '#8b5cf6';
    imagePreview.style.backgroundColor = '#4c1d95';
  }
}

function unhighlight(e) {
  if (selectedImages.length === 0) {
    imagePreview.style.borderColor = '#4b5563';
    imagePreview.style.backgroundColor = '#374151';
  }
}

function handleDrop(e) {
  const dt = e.dataTransfer;
  const files = dt.files;
  
  if (files.length > 0 && selectedImages.length < 5) {
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

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
  const priceValue = priceInput.value;
  if (priceValue && !isNaN(priceValue)) {
    priceInput.value = formatIDR(parseInt(priceValue));
  }
  
  // Initialize SKU counter by checking existing products (you may want to get this from backend)
  // For now, we'll start from 1, but in real application, you should get the last SKU number from database
  skuCounter = 1;
});
</script>
@endsection