@extends('user.layouts.app')
@section('title', 'Produk - SoleStyle')
@section('content')
<div class="container mx-auto px-4 py-6">
  
  <!-- Page Header -->
  <div class="mb-8">
    <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">Koleksi Sepatu</h1>
    <p class="text-slate-400 mt-2">Temukan sepatu impian Anda dari koleksi terlengkap</p>
  </div>
  
  <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    
    <!-- Filter Sidebar -->
    <div class="md:col-span-1">
      <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 p-6 rounded-xl shadow-xl">
        <h3 class="font-semibold mb-6 text-lg flex items-center">
          <i class="fas fa-filter text-purple-400 mr-2"></i>
          Filter Produk
        </h3>
        
        <form method="GET" action="{{ url('produk') }}" id="filter-form">
          
          <!-- Search -->
          <div class="mb-6">
            <label class="block text-sm mb-2 text-slate-300">Cari Produk</label>
            <div class="relative">
              <input type="text" name="search" value="{{ request('search') }}" 
                     placeholder="Cari sepatu..." 
                     class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 pl-10 text-white placeholder-slate-400 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all">
              <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
            </div>
          </div>
          
          <!-- Kategori -->
          @if(isset($categories) && count($categories) > 0)
          <div class="mb-6">
            <h4 class="font-medium mb-3 text-slate-200 flex items-center">
              <i class="fas fa-tags text-purple-400 mr-2"></i>
              Kategori
            </h4>
            <div class="space-y-2">
              @foreach($categories as $category)
              <label class="flex items-center group cursor-pointer">
                <input type="checkbox" name="category[]" value="{{ $category }}" 
                       {{ in_array($category, request('category', [])) ? 'checked' : '' }}
                       class="w-4 h-4 text-purple-600 bg-slate-700 border-slate-600 rounded focus:ring-purple-500 focus:ring-2">
                <span class="ml-3 text-slate-300 group-hover:text-purple-400 transition-colors">{{ ucfirst($category) }}</span>
              </label>
              @endforeach
            </div>
          </div>
          @endif
          
          <!-- Ukuran -->
          @if(isset($availableSizes) && count($availableSizes) > 0)
          <div class="mb-6">
            <h4 class="font-medium mb-3 text-slate-200 flex items-center">
              <i class="fas fa-shoe-prints text-purple-400 mr-2"></i>
              Ukuran
            </h4>
            <div class="grid grid-cols-3 gap-2">
              @foreach($availableSizes as $size)
              <label class="text-center cursor-pointer">
                <input type="checkbox" name="size[]" value="{{ $size }}" 
                       {{ in_array($size, request('size', [])) ? 'checked' : '' }}
                       class="hidden peer">
                <div class="py-2 px-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-300 text-sm font-medium peer-checked:bg-gradient-to-r peer-checked:from-purple-600 peer-checked:to-pink-600 peer-checked:text-white peer-checked:border-transparent hover:border-purple-500 transition-all">
                  {{ $size }}
                </div>
              </label>
              @endforeach
            </div>
          </div>
          @endif
          
          <!-- Harga -->
          <div class="mb-6">
            <h4 class="font-medium mb-3 text-slate-200 flex items-center">
              <i class="fas fa-money-bill-wave text-purple-400 mr-2"></i>
              Rentang Harga
            </h4>
            <div class="space-y-2">
              <label class="flex items-center group cursor-pointer">
                <input type="radio" name="price_range" value="0-500000" 
                       {{ request('price_range') === '0-500000' ? 'checked' : '' }}
                       class="w-4 h-4 text-purple-600 bg-slate-700 border-slate-600 focus:ring-purple-500 focus:ring-2">
                <span class="ml-3 text-slate-300 group-hover:text-purple-400 transition-colors">< Rp 500K</span>
              </label>
              <label class="flex items-center group cursor-pointer">
                <input type="radio" name="price_range" value="500000-1000000" 
                       {{ request('price_range') === '500000-1000000' ? 'checked' : '' }}
                       class="w-4 h-4 text-purple-600 bg-slate-700 border-slate-600 focus:ring-purple-500 focus:ring-2">
                <span class="ml-3 text-slate-300 group-hover:text-purple-400 transition-colors">Rp 500K - 1Jt</span>
              </label>
              <label class="flex items-center group cursor-pointer">
                <input type="radio" name="price_range" value="1000000-2000000" 
                       {{ request('price_range') === '1000000-2000000' ? 'checked' : '' }}
                       class="w-4 h-4 text-purple-600 bg-slate-700 border-slate-600 focus:ring-purple-500 focus:ring-2">
                <span class="ml-3 text-slate-300 group-hover:text-purple-400 transition-colors">Rp 1 - 2Jt</span>
              </label>
              <label class="flex items-center group cursor-pointer">
                <input type="radio" name="price_range" value="2000000+" 
                       {{ request('price_range') === '2000000+' ? 'checked' : '' }}
                       class="w-4 h-4 text-purple-600 bg-slate-700 border-slate-600 focus:ring-purple-500 focus:ring-2">
                <span class="ml-3 text-slate-300 group-hover:text-purple-400 transition-colors">> Rp 2Jt</span>
              </label>
            </div>
          </div>
          <input type="hidden" name="sort" value="{{ request('sort') }}">
        </form>
        
        <div class="space-y-3">
          <button onclick="applyFilters()" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white py-3 rounded-lg font-medium transition-all transform hover:scale-105">
            <i class="fas fa-check mr-2"></i>Terapkan Filter
          </button>
          <a href="{{ url('produk') }}" class="w-full bg-slate-700 hover:bg-slate-600 text-white py-3 rounded-lg block text-center font-medium transition-all">
            <i class="fas fa-undo mr-2"></i>Reset Filter
          </a>
        </div>
      </div>
    </div>
    
    <!-- Main Products Area -->
    <div class="md:col-span-3">
      
      <!-- Results Info & Sort -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div class="flex items-center space-x-2">
          <i class="fas fa-box text-purple-400"></i>
          <span class="text-slate-300">{{ $products->total() }} produk ditemukan</span>
        </div>
        
        <form method="GET" action="{{ url('produk') }}" class="flex items-center gap-3">
          <label class="text-slate-300 text-sm">Urutkan:</label>
          <select name="sort" class="bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2 text-white focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all" onchange="this.form.submit()">
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
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
        <a href="{{ route('produk.show', $product) }}" class="block">
          <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl shadow-xl overflow-hidden group hover:border-purple-500/50 transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
            <div class="relative overflow-hidden">
              @if($product->image_exists)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                     class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
              @else
                <div class="w-full h-56 bg-slate-700/50 flex items-center justify-center">
                  <div class="text-center text-slate-400">
                    <i class="fas fa-image text-4xl mb-2"></i>
                    <p class="text-sm">No Image</p>
                  </div>
                </div>
              @endif
              
              <!-- Status badge berdasarkan total stock dari sizes -->
              @if($product->total_stock <= 5 && $product->total_stock > 0)
                <span class="absolute top-3 left-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-xs px-3 py-1 rounded-full font-medium">
                  <i class="fas fa-exclamation-triangle mr-1"></i>Stok Terbatas
                </span>
              @elseif($product->total_stock == 0)
                <span class="absolute top-3 left-3 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs px-3 py-1 rounded-full font-medium">
                  <i class="fas fa-times-circle mr-1"></i>Habis
                </span>
              @endif
              
              <!-- Wishlist Button -->
              <button onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $product->id }})" class="absolute top-3 right-3 w-10 h-10 bg-slate-800/80 backdrop-blur-sm rounded-full flex items-center justify-center text-slate-300 hover:text-red-400 hover:bg-slate-700/80 transition-all">
                <i class="fas fa-heart"></i>
              </button>
            </div>
            
            <div class="p-5">
              @if(isset($product->brand))
                <span class="text-purple-400 text-sm font-semibold uppercase tracking-wide">{{ $product->brand }}</span>
              @endif
              <h3 class="font-bold text-lg text-white mt-1 mb-1 group-hover:text-purple-300 transition-colors">{{ $product->name }}</h3>
              <p class="text-slate-400 text-sm mb-3">{{ $product->category_name ?? 'Uncategorized' }}</p>
              
              <!-- Ukuran Tersedia dengan Stok -->
              @if($product->sizes && count($product->sizes) > 0)
                <div class="mb-3">
                  <p class="text-xs text-slate-400 mb-1">Ukuran Tersedia:</p>
                  <div class="flex flex-wrap gap-1">
                    @foreach($product->sizes->where('stock', '>', 0) as $size)
                      <div class="text-xs bg-purple-500/20 text-purple-400 px-2 py-1 rounded-full flex items-center gap-1">
                        <span>{{ $size->size }}</span>
                        <span class="text-purple-300">({{ $size->stock }})</span>
                      </div>
                    @endforeach
                  </div>
                  
                  <!-- Ukuran yang habis stok -->
                  @if($product->sizes->where('stock', '=', 0)->count() > 0)
                    <div class="mt-1">
                      <p class="text-xs text-slate-500 mb-1">Habis:</p>
                      <div class="flex flex-wrap gap-1">
                        @foreach($product->sizes->where('stock', '=', 0) as $size)
                          <span class="text-xs bg-red-500/20 text-red-400 px-2 py-1 rounded-full">
                            {{ $size->size }}
                          </span>
                        @endforeach
                      </div>
                    </div>
                  @endif
                </div>
              @endif
              
              <div class="flex justify-between items-center mb-4">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 font-bold text-xl">{{ $product->formatted_price ?? 'Rp 0' }}</span>
                @if($product->total_stock > 0)
                  <span class="text-green-400 text-sm bg-green-400/10 px-2 py-1 rounded-full">
                    <i class="fas fa-check-circle mr-1"></i>Total: {{ $product->total_stock }}
                  </span>
                @else
                  <span class="text-red-400 text-sm bg-red-400/10 px-2 py-1 rounded-full">
                    <i class="fas fa-times-circle mr-1"></i>Habis
                  </span>
                @endif
              </div>
              
              <!-- View Details Button -->
              <div class="w-full">
                @if($product->total_stock > 0)
                  <div class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white py-3 rounded-lg font-medium transition-all transform group-hover:scale-105 text-center">
                    <i class="fas fa-eye mr-2"></i>Lihat Detail
                  </div>
                @else
                  <div class="w-full bg-slate-700 text-slate-400 py-3 rounded-lg font-medium cursor-not-allowed text-center">
                    <i class="fas fa-ban mr-2"></i>Stok Habis
                  </div>
                @endif
              </div>
            </div>
          </div>
        </a>
        @empty
        <div class="col-span-full text-center py-16">
          <div class="max-w-md mx-auto">
            <div class="w-24 h-24 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
              <i class="fas fa-search text-4xl text-slate-400"></i>
            </div>
            <h3 class="text-2xl font-semibold mb-3 text-white">Produk Tidak Ditemukan</h3>
            <p class="text-slate-400 mb-6">Tidak ada produk yang sesuai dengan filter Anda. Coba ubah kriteria pencarian.</p>
            <a href="{{ url('produk') }}" class="inline-flex items-center bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-3 rounded-lg font-medium transition-all transform hover:scale-105">
              <i class="fas fa-eye mr-2"></i>Lihat Semua Produk
            </a>
          </div>
        </div>
        @endforelse
      </div>
      
      <!-- Pagination -->
      @if($products->hasPages())
      <div class="mt-8 flex justify-center">
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-4">
          {{ $products->links() }}
        </div>
      </div>
      @endif
      
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script>
function applyFilters() {
  document.getElementById('filter-form').submit();
}
function toggleWishlist(productId) {
  // Add wishlist functionality here
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
      document.body.removeChild(toast);
    }, 300);
  }, 3000);
}
// Auto submit filter when checkbox/radio changed
document.addEventListener('change', function(e) {
  if (e.target.matches('input[type="checkbox"], input[type="radio"]')) {
    setTimeout(() => {
      applyFilters();
    }, 100);
  }
});
</script>
@endsection