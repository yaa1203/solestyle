@extends('user.layouts.app')

@section('title', 'Produk - SoleStyle')

@section('content')
<div class="container mx-auto px-4 py-6">
  
  <!-- Page Header -->
  <div class="mb-6">
    <h1 class="text-2xl font-bold">Koleksi Sepatu</h1>
    <p class="text-gray-600">Temukan sepatu impian Anda</p>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    
    <!-- Filter Sidebar (sama seperti sebelumnya) -->
    <div class="md:col-span-1">
      <div class="bg-white p-4 rounded-lg shadow">
        <h3 class="font-semibold mb-4">Filter Produk</h3>
        
        <form method="GET" action="{{ url('produk') }}" id="filter-form">
          
          <!-- Search -->
          <div class="mb-4">
            <label class="block text-sm mb-2">Cari Produk</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari sepatu..." 
                   class="w-full border rounded px-3 py-2">
          </div>
          
          <!-- Kategori -->
          @if(isset($categories) && count($categories) > 0)
          <div class="mb-4">
            <h4 class="font-medium mb-2">Kategori</h4>
            @foreach($categories as $category)
            <label class="block">
              <input type="checkbox" name="category[]" value="{{ $category }}" 
                     {{ in_array($category, request('category', [])) ? 'checked' : '' }}>
              <span class="ml-2">{{ ucfirst($category) }}</span>
            </label>
            @endforeach
          </div>
          @endif
          
          <!-- Size -->
          <div class="mb-4">
            <h4 class="font-medium mb-2">Ukuran</h4>
            <div class="grid grid-cols-3 gap-2">
              @php $sizes = ['36', '37', '38', '39', '40', '41', '42', '43', '44', '45']; @endphp
              @foreach($sizes as $size)
              <label class="text-center">
                <input type="checkbox" name="size[]" value="{{ $size }}" 
                       {{ in_array($size, request('size', [])) ? 'checked' : '' }}
                       class="hidden peer">
                <div class="py-1 border rounded peer-checked:bg-blue-500 peer-checked:text-white">
                  {{ $size }}
                </div>
              </label>
              @endforeach
            </div>
          </div>
          
          <!-- Harga -->
          <div class="mb-4">
            <h4 class="font-medium mb-2">Rentang Harga</h4>
            <label class="block">
              <input type="radio" name="price_range" value="0-500000" 
                     {{ request('price_range') === '0-500000' ? 'checked' : '' }}>
              <span class="ml-2">< Rp 500K</span>
            </label>
            <label class="block">
              <input type="radio" name="price_range" value="500000-1000000" 
                     {{ request('price_range') === '500000-1000000' ? 'checked' : '' }}>
              <span class="ml-2">Rp 500K - 1Jt</span>
            </label>
            <label class="block">
              <input type="radio" name="price_range" value="1000000-2000000" 
                     {{ request('price_range') === '1000000-2000000' ? 'checked' : '' }}>
              <span class="ml-2">Rp 1 - 2Jt</span>
            </label>
            <label class="block">
              <input type="radio" name="price_range" value="2000000+" 
                     {{ request('price_range') === '2000000+' ? 'checked' : '' }}>
              <span class="ml-2">> Rp 2Jt</span>
            </label>
          </div>

          <input type="hidden" name="sort" value="{{ request('sort') }}">
        </form>
        
        <button onclick="applyFilters()" class="w-full bg-blue-600 text-white py-2 rounded">
          Terapkan Filter
        </button>
        <a href="{{ url('produk') }}" class="w-full bg-gray-500 text-white py-2 rounded block text-center mt-2">
          Reset
        </a>
      </div>
    </div>
    
    <!-- Main Products Area -->
    <div class="md:col-span-3">
      
      <!-- Results Info & Sort -->
      <div class="flex justify-between items-center mb-4">
        <div>
          <span>{{ $products->total() }} produk ditemukan</span>
        </div>
        
        <form method="GET" action="{{ url('produk') }}" class="flex items-center gap-2">
          <label>Urutkan:</label>
          <select name="sort" class="border rounded px-2 py-1" onchange="this.form.submit()">
            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Terpopuler</option>
            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Terbaru</option>
            <option value="price-low" {{ request('sort') === 'price-low' ? 'selected' : '' }}>Harga Terendah</option>
            <option value="price-high" {{ request('sort') === 'price-high' ? 'selected' : '' }}>Harga Tertinggi</option>
            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nama A-Z</option>
            <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
          </select>
          
          @foreach(request()->except(['sort', 'page']) as $key => $value)
            @if(is_array($value))
              @foreach($value as $item)
                <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
              @endforeach
            @else
              <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
          @endforeach
        </form>
      </div>
      
      <!-- Products Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($products as $product)
        <div class="bg-white rounded-lg shadow p-4">
          <div class="relative">
            @if($product->image_exists)
              <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                   class="w-full h-48 object-cover rounded mb-3">
            @else
              <div class="w-full h-48 bg-gray-200 rounded mb-3 flex items-center justify-center">
                <span class="text-gray-400">No Image</span>
              </div>
            @endif
            
            @if(isset($product->stock) && $product->stock <= 5 && $product->stock > 0)
              <span class="absolute top-2 left-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded">
                Stok Terbatas
              </span>
            @elseif(isset($product->stock) && $product->stock == 0)
              <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded">
                Habis
              </span>
            @endif
          </div>
          
          <div>
            @if(isset($product->brand))
              <span class="text-blue-600 text-sm font-semibold">{{ $product->brand }}</span>
            @endif
            <h3 class="font-bold">{{ $product->name }}</h3>
            <p class="text-gray-500 text-sm">{{ $product->category_name ?? 'Uncategorized' }}</p>
            
            <div class="flex items-center gap-2 my-2">
              <div class="text-yellow-400">★★★★★</div>
              <span class="text-sm">4.5 (128 ulasan)</span>
            </div>
            
            <div class="flex justify-between items-center mb-3">
              <span class="text-blue-600 font-bold text-lg">{{ $product->formatted_price ?? 'Rp 0' }}</span>
              @if(isset($product->stock) && $product->stock > 0)
                <span class="text-green-600 text-sm">Stok: {{ $product->stock }}</span>
              @endif
            </div>
            
            @if(isset($product->stock) && $product->stock > 0)
              <div class="space-y-2">
                <button onclick="openSizeModal({{ $product->id }}, '{{ $product->name }}')" 
                        class="w-full bg-blue-600 text-white py-2 rounded">
                  Tambah ke Keranjang
                </button>
                <button onclick="buyNow({{ $product->id }}, '{{ $product->name }}')" 
                        class="w-full border border-blue-600 text-blue-600 py-2 rounded">
                  Beli Sekarang
                </button>
              </div>
            @else
              <button disabled class="w-full bg-gray-400 text-white py-2 rounded">
                Stok Habis
              </button>
            @endif
          </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
          <h3 class="text-xl font-semibold mb-2">Produk Tidak Ditemukan</h3>
          <p class="text-gray-500 mb-4">Tidak ada produk yang sesuai dengan filter Anda</p>
          <a href="{{ url('produk') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
            Lihat Semua Produk
          </a>
        </div>
        @endforelse
      </div>
      
      <!-- Pagination -->
      @if($products->hasPages())
      <div class="mt-6 flex justify-center">
        {{ $products->links() }}
      </div>
      @endif
      
    </div>
  </div>
</div>

<!-- Modal Pilih Ukuran -->
<div id="sizeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
    <h3 class="text-lg font-semibold mb-4">Pilih Ukuran</h3>
    <p class="text-gray-600 mb-4" id="selectedProductName">Nama Produk</p>
    
    <div class="grid grid-cols-5 gap-2 mb-4">
      @php $modalSizes = ['36', '37', '38', '39', '40', '41', '42', '43', '44', '45']; @endphp
      @foreach($modalSizes as $size)
      <button onclick="selectSize('{{ $size }}')" 
              class="size-btn py-2 px-3 border rounded text-center hover:bg-blue-50" 
              data-size="{{ $size }}">
        {{ $size }}
      </button>
      @endforeach
    </div>
    
    <div class="mb-4">
      <label class="block text-sm mb-2">Jumlah:</label>
      <input type="number" id="quantity" value="1" min="1" class="w-20 border rounded px-2 py-1">
    </div>
    
    <div class="flex gap-3">
      <button onclick="addToCart()" class="flex-1 bg-blue-600 text-white py-2 rounded">
        Tambah ke Keranjang
      </button>
      <button onclick="closeSizeModal()" class="px-4 py-2 border rounded">
        Batal
      </button>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
let selectedProductId = null;
let selectedSize = null;

function applyFilters() {
  document.getElementById('filter-form').submit();
}

function openSizeModal(productId, productName) {
  selectedProductId = productId;
  selectedSize = null;
  
  document.getElementById('selectedProductName').textContent = productName;
  document.getElementById('quantity').value = 1;
  
  // Reset semua button size
  document.querySelectorAll('.size-btn').forEach(btn => {
    btn.classList.remove('bg-blue-500', 'text-white');
    btn.classList.add('border');
  });
  
  document.getElementById('sizeModal').classList.remove('hidden');
  document.getElementById('sizeModal').classList.add('flex');
}

function selectSize(size) {
  selectedSize = size;
  
  // Reset semua button
  document.querySelectorAll('.size-btn').forEach(btn => {
    btn.classList.remove('bg-blue-500', 'text-white');
    btn.classList.add('border');
  });
  
  // Highlight yang dipilih
  const selectedBtn = document.querySelector(`[data-size="${size}"]`);
  selectedBtn.classList.add('bg-blue-500', 'text-white');
  selectedBtn.classList.remove('border');
}

function closeSizeModal() {
  document.getElementById('sizeModal').classList.add('hidden');
  document.getElementById('sizeModal').classList.remove('flex');
}

function addToCart() {
  if (!selectedSize) {
    alert('Silakan pilih ukuran terlebih dahulu');
    return;
  }
  
  const quantity = document.getElementById('quantity').value;
  
  fetch('/cart/add', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
      product_id: selectedProductId,
      quantity: parseInt(quantity),
      size: parseInt(selectedSize)
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert(data.message);
      updateCartCount(data.cart_count);
      closeSizeModal();
    } else {
      alert(data.message || 'Terjadi kesalahan');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Terjadi kesalahan saat menambahkan ke keranjang');
  });
}

function buyNow(productId, productName) {
  alert(`Mengarahkan ke halaman checkout untuk ${productName}...`);
}

function updateCartCount(count) {
  const cartCountElement = document.getElementById('cart-count');
  if (cartCountElement) {
    cartCountElement.textContent = count;
  }
}

// Auto submit filter when checkbox/radio changed
document.addEventListener('change', function(e) {
  if (e.target.matches('input[type="checkbox"], input[type="radio"]')) {
    setTimeout(() => {
      applyFilters();
    }, 100);
  }
});

// Close modal when clicking outside
document.getElementById('sizeModal').addEventListener('click', function(e) {
  if (e.target === this) {
    closeSizeModal();
  }
});
</script>
@endsection