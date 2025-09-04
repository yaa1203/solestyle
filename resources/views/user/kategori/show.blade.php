{{-- resources/views/user/categories/show.blade.php --}}
@extends('user.layouts.app')

@section('title', $category->name . ' - SoleStyle')

@section('styles')
<style>
  .product-card {
    transition: all 0.3s ease;
  }
  .product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(147, 51, 234, 0.2);
  }
</style>
@endsection

@section('content')

<!-- Category Header -->
<section class="py-12 bg-gradient-to-r from-purple-600/20 to-pink-600/20">
  <div class="max-w-7xl mx-auto px-6">
    <div class="text-center">
      <h1 class="text-3xl md:text-4xl font-bold mb-4">{{ $category->name }}</h1>
      <p class="text-slate-300 mb-6">Temukan koleksi terbaik dalam kategori {{ $category->name }}</p>
      
      <!-- Category Stats -->
      <div class="flex justify-center gap-8 text-sm">
        <div class="text-center">
          <div class="text-xl font-bold text-purple-400">{{ $categoryStats['total_products'] }}</div>
          <div class="text-slate-400">Total Produk</div>
        </div>
        @if($categoryStats['min_price'])
        <div class="text-center">
          <div class="text-xl font-bold text-green-400">Rp {{ number_format($categoryStats['min_price'], 0, ',', '.') }}</div>
          <div class="text-slate-400">Harga Termurah</div>
        </div>
        @endif
        @if($categoryStats['max_price'])
        <div class="text-center">
          <div class="text-xl font-bold text-yellow-400">Rp {{ number_format($categoryStats['max_price'], 0, ',', '.') }}</div>
          <div class="text-slate-400">Harga Tertinggi</div>
        </div>
        @endif
      </div>
    </div>
  </div>
</section>

<!-- Filter Section -->
<section class="py-6 bg-slate-800/30">
  <div class="max-w-7xl mx-auto px-6">
    <form method="GET" class="flex flex-wrap items-center gap-4">
      
      <!-- Sort By -->
      <select name="sort" onchange="this.form.submit()" 
              class="bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-sm focus:border-purple-500 focus:outline-none">
        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Urutkan: A-Z</option>
        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Terbaru</option>
        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Terpopuler</option>
      </select>

      <!-- Price Range -->
      <div class="flex items-center gap-2">
        <input type="number" name="min_price" value="{{ request('min_price') }}" 
               placeholder="Min harga" class="bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-sm w-24 focus:border-purple-500 focus:outline-none">
        <span class="text-slate-400">-</span>
        <input type="number" name="max_price" value="{{ request('max_price') }}" 
               placeholder="Max harga" class="bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-sm w-24 focus:border-purple-500 focus:outline-none">
      </div>

      <!-- Stock Filter -->
      <label class="flex items-center gap-2 text-sm">
        <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }} 
               onchange="this.form.submit()" class="rounded bg-slate-700 border-slate-600">
        <span>Stok Tersedia</span>
      </label>

      <button type="submit" class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg text-sm transition">
        <i class="fas fa-filter mr-2"></i>Filter
      </button>

      @if(request()->hasAny(['sort', 'min_price', 'max_price', 'in_stock']))
      <a href="{{ route('kategori.show', $category) }}" 
         class="text-slate-400 hover:text-white text-sm flex items-center gap-2">
        <i class="fas fa-times"></i>
        Reset Filter
      </a>
      @endif

    </form>
  </div>
</section>

<!-- Products Grid -->
<section class="py-12">
  <div class="max-w-7xl mx-auto px-6">
    
    @if($products->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
      @foreach($products as $product)
      <div class="product-card bg-slate-800/50 rounded-xl overflow-hidden cursor-pointer" 
           onclick="goToProduct('{{ $product->id }}')">
        
        <!-- Product Image -->
        <div class="relative">
          @if($product->image && file_exists(public_path('storage/' . $product->image)))
            <img src="{{ asset('storage/' . $product->image) }}" 
                 alt="{{ $product->name }}" class="w-full h-48 object-cover">
          @else
            <div class="w-full h-48 bg-slate-700 flex items-center justify-center">
              <i class="fas fa-image text-4xl text-slate-500"></i>
            </div>
          @endif
          
          <!-- Stock Badge -->
          @if($product->stock <= 0)
            <div class="absolute top-3 left-3 bg-red-600 text-white px-2 py-1 rounded text-xs font-semibold">
              Habis
            </div>
          @elseif($product->stock <= 10)
            <div class="absolute top-3 left-3 bg-yellow-600 text-white px-2 py-1 rounded text-xs font-semibold">
              Stok Terbatas
            </div>
          @endif

          <!-- Wishlist Button -->
          <button class="absolute top-3 right-3 w-8 h-8 bg-black/50 rounded-full flex items-center justify-center hover:bg-black/70 transition">
            <i class="fas fa-heart text-white text-sm"></i>
          </button>
        </div>

        <!-- Product Info -->
        <div class="p-4">
          <h3 class="font-semibold mb-2 line-clamp-2">{{ $product->name }}</h3>
          <p class="text-slate-400 text-sm mb-3">SKU: {{ $product->sku }}</p>
          
          <div class="flex items-center justify-between">
            <div>
              <div class="text-lg font-bold text-purple-400">
                Rp {{ number_format($product->price, 0, ',', '.') }}
              </div>
              @if($product->stock > 0)
                <div class="text-green-400 text-xs">Stok: {{ $product->stock }}</div>
              @else
                <div class="text-red-400 text-xs">Tidak Tersedia</div>
              @endif
            </div>
            
            <button class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 px-3 py-2 rounded-lg text-sm transition {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                    {{ $product->stock <= 0 ? 'disabled' : '' }}>
              <i class="fas fa-cart-plus"></i>
            </button>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="flex justify-center">
      {{ $products->links('user.pagination.custom') }}
    </div>
    @endif

    @else
    <!-- Empty State -->
    <div class="text-center py-20">
      <div class="w-24 h-24 bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-6">
        <i class="fas fa-search text-3xl text-slate-500"></i>
      </div>
      <h3 class="text-xl font-bold mb-2">Produk Tidak Ditemukan</h3>
      <p class="text-slate-400 mb-6">Tidak ada produk dalam kategori "{{ $category->name }}" saat ini</p>
      <div class="flex justify-center gap-4">
        <a href="{{ route('kategori.index') }}" 
           class="bg-purple-600 hover:bg-purple-700 px-6 py-3 rounded-lg transition">
          <i class="fas fa-arrow-left mr-2"></i>Kembali ke Kategori
        </a>
        <a href="{{ url('produk') }}" 
           class="bg-slate-600 hover:bg-slate-700 px-6 py-3 rounded-lg transition">
          <i class="fas fa-th mr-2"></i>Lihat Semua Produk
        </a>
      </div>
    </div>
    @endif

  </div>
</section>
@endsection

@section('scripts')
<script>
  function goToProduct(productId) {
    window.location.href = `{{ url('#') }}/${productId}`;
  }

  // Add to cart functionality
  function addToCart(productId, productName) {
    // Implementasi add to cart
    Swal.fire({
      title: 'Ditambahkan ke Keranjang!',
      text: `${productName} berhasil ditambahkan ke keranjang`,
      icon: 'success',
      timer: 2000,
      showConfirmButton: false
    });
  }

  // Add to wishlist functionality
  function toggleWishlist(productId) {
    // Implementasi wishlist toggle
    const heartIcon = event.target;
    heartIcon.classList.toggle('fas');
    heartIcon.classList.toggle('far');
    
    if (heartIcon.classList.contains('fas')) {
      heartIcon.style.color = '#ef4444';
    } else {
      heartIcon.style.color = '#ffffff';
    }
  }

  // Auto-submit filter form on change
  document.querySelectorAll('select[name="sort"]').forEach(select => {
    select.addEventListener('change', function() {
      this.closest('form').submit();
    });
  });

  // Price filter with enter key
  document.querySelectorAll('input[name="min_price"], input[name="max_price"]').forEach(input => {
    input.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        this.closest('form').submit();
      }
    });
  });
</script>
@endsection