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
        
        <div class="flex items-center gap-2 mb-6">
          <div class="text-yellow-400">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
          </div>
          <span class="text-slate-400">(4.5) 128 ulasan</span>
        </div>
        
        <div class="mb-6">
          <span class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">{{ $product->formatted_price }}</span>
        </div>
        
        <!-- Description -->
        <div class="mb-6">
          <h3 class="font-semibold text-white mb-2">Deskripsi</h3>
          <p class="text-slate-300">{{ $product->description ?? 'Tidak ada deskripsi tersedia.' }}</p>
        </div>
        
        <!-- Size Selection -->
        <div class="mb-6">
          <h3 class="font-semibold text-white mb-3">Pilih Ukuran</h3>
          <div class="grid grid-cols-4 gap-3">
            @foreach($product->sizes as $size)
              <div class="text-center">
                <div class="py-3 px-4 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-300 text-sm font-medium 
                        {{ $size->stock > 0 ? 'hover:border-purple-500 cursor-pointer' : 'opacity-50 cursor-not-allowed' }}">
                  {{ $size->size }}
                </div>
                <span class="text-xs text-slate-400 block mt-1">Stok: {{ $size->stock }}</span>
              </div>
            @endforeach
          </div>
        </div>
        
        <!-- Quantity Selector -->
        <div class="mb-6">
          <h3 class="font-semibold text-white mb-3">Jumlah</h3>
          <div class="flex items-center gap-3">
            <button class="w-10 h-10 bg-slate-700 rounded-lg flex items-center justify-center text-white hover:bg-slate-600 transition-all">
              <i class="fas fa-minus"></i>
            </button>
            <input type="number" value="1" min="1" max="{{ $product->total_stock }}" 
                   class="w-20 h-10 bg-slate-700 rounded-lg text-white text-center focus:outline-none focus:ring-2 focus:ring-purple-500">
            <button class="w-10 h-10 bg-slate-700 rounded-lg flex items-center justify-center text-white hover:bg-slate-600 transition-all">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex gap-3">
          @if($product->total_stock > 0)
            <button class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white py-3 rounded-lg font-medium transition-all transform hover:scale-105">
              <i class="fas fa-shopping-cart mr-2"></i>Tambah ke Keranjang
            </button>
          @else
            <button class="flex-1 bg-slate-700 text-slate-400 py-3 rounded-lg font-medium cursor-not-allowed">
              <i class="fas fa-ban mr-2"></i>Stok Habis
            </button>
          @endif
          
          <button class="flex-1 bg-slate-700 hover:bg-slate-600 text-white py-3 rounded-lg font-medium transition-all">
            <i class="fas fa-bolt mr-2"></i>Beli Sekarang
          </button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Related Products -->
  <div class="mt-12">
    <h2 class="text-2xl font-bold text-white mb-6">Produk Terkait</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      @foreach($relatedProducts as $product)
      <a href="{{ route('produk.show', $product) }}" class="block">
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl shadow-xl overflow-hidden group hover:border-purple-500/50 transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
          <div class="relative overflow-hidden">
            @if($product->image_exists)
              <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                   class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
            @else
              <div class="w-full h-48 bg-slate-700/50 flex items-center justify-center">
                <div class="text-center text-slate-400">
                  <i class="fas fa-image text-4xl mb-2"></i>
                  <p class="text-sm">No Image</p>
                </div>
              </div>
            @endif
            
            @if($product->total_stock <= 5 && $product->total_stock > 0)
              <span class="absolute top-3 left-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-xs px-3 py-1 rounded-full font-medium">
                <i class="fas fa-exclamation-triangle mr-1"></i>Stok Terbatas
              </span>
            @elseif($product->total_stock == 0)
              <span class="absolute top-3 left-3 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs px-3 py-1 rounded-full font-medium">
                <i class="fas fa-times-circle mr-1"></i>Habis
              </span>
            @endif
          </div>
          
          <div class="p-4">
            <h3 class="font-bold text-lg text-white mb-1 group-hover:text-purple-300 transition-colors">{{ $product->name }}</h3>
            <p class="text-slate-400 text-sm mb-3">{{ $product->category_name ?? 'Uncategorized' }}</p>
            
            <div class="flex justify-between items-center">
              <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 font-bold">{{ $product->formatted_price }}</span>
              @if($product->total_stock > 0)
                <span class="text-green-400 text-sm bg-green-400/10 px-2 py-1 rounded-full">
                  <i class="fas fa-check-circle mr-1"></i>Stok: {{ $product->total_stock }}
                </span>
              @endif
            </div>
          </div>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script>
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
</script>
@endsection