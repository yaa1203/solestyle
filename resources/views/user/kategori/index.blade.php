{{-- resources/views/user/categories/index.blade.php --}}
@extends('user.layouts.app')

@section('title', 'Kategori Produk - SoleStyle')

@section('styles')
<style>
  .category-card {
    transition: all 0.3s ease;
  }
  .category-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(147, 51, 234, 0.3);
  }
  .hero-section {
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.8), rgba(236, 72, 153, 0.8)),
                url('https://images.unsplash.com/photo-1560769629-975ec94e6a86?w=1200&h=400&fit=crop') center/cover;
  }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section py-20">
  <div class="max-w-7xl mx-auto px-6 text-center">
    <h1 class="text-4xl md:text-5xl font-bold mb-4">Kategori Produk</h1>
    <p class="text-xl text-slate-200 mb-8">Temukan sepatu yang tepat untuk setiap kebutuhan Anda</p>
    <div class="text-purple-200">
      <span class="bg-purple-600/30 px-4 py-2 rounded-lg">
        {{ $totalCategories }} Kategori • {{ $totalProducts }} Produk Tersedia
      </span>
    </div>
  </div>
</section>

<!-- Categories Section -->
<section class="py-16">
  <div class="max-w-7xl mx-auto px-6">
    
    <!-- Main Categories dari Database -->
    <div class="mb-12">
      <h2 class="text-2xl font-bold mb-8">Semua Kategori</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($featuredCategories as $index => $category)
        <div class="category-card bg-slate-800/50 backdrop-blur-sm rounded-xl overflow-hidden cursor-pointer" 
             onclick="goToCategory('{{ $category->id }}', '{{ $category->name }}')">
          <div class="relative">
            <!-- Default images berdasarkan urutan atau nama kategori -->
            @php
              $categoryImages = [
                'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=250&fit=crop', // Sneakers
                'https://images.unsplash.com/photo-1556906781-9a412961c28c?w=400&h=250&fit=crop', // Running
                'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=400&h=250&fit=crop', // Basketball
                'https://images.unsplash.com/photo-1607522370275-f14206abe5d3?w=400&h=250&fit=crop', // Casual
                'https://images.unsplash.com/photo-1594223274512-ad4803739b7c?w=400&h=250&fit=crop', // Formal
                'https://images.unsplash.com/photo-1612902456551-333ac5afa26e?w=400&h=250&fit=crop', // Boots
              ];
              
              $colorClasses = [
                'bg-purple-600', 'bg-blue-600', 'bg-green-600', 
                'bg-red-600', 'bg-yellow-600', 'bg-indigo-600'
              ];
              
              $imageIndex = $index % count($categoryImages);
              $colorIndex = $index % count($colorClasses);
            @endphp
            
            <img src="{{ $categoryImages[$imageIndex] }}" 
                 alt="{{ $category->name }}" class="w-full h-48 object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent">
              <div class="absolute bottom-4 left-4">
                <h3 class="text-2xl font-bold text-white">{{ $category->name }}</h3>
                <p class="text-slate-300">Kategori pilihan terbaik</p>
              </div>
            </div>
            <div class="absolute top-4 right-4 {{ $colorClasses[$colorIndex] }} text-white px-3 py-1 rounded-full text-sm">
              {{ $category->products_count }}+ Produk
            </div>
          </div>
          <div class="p-4">
            <div class="flex items-center justify-between">
              <span class="text-slate-400">Total Produk</span>
              <span class="text-purple-400 font-bold">{{ $category->products_count }} Items</span>
            </div>
          </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
          <div class="text-slate-400">
            <i class="fas fa-folder-open text-4xl mb-4"></i>
            <p class="text-lg">Belum ada kategori tersedia</p>
            <p class="text-sm">Kategori akan muncul setelah admin menambahkannya</p>
          </div>
        </div>
        @endforelse
      </div>
    </div>

    <!-- Kategori Berdasarkan Jumlah Produk -->
    @if($categories->count() > 0)
    <div class="mb-12">
      <h2 class="text-2xl font-bold mb-8">Kategori Berdasarkan Popularitas</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        @php
          $topCategories = $categories->sortByDesc('products_count')->take(3);
          $labels = ['Terpopuler', 'Favorit', 'Pilihan'];
          $colors = ['text-green-400 bg-green-600', 'text-yellow-400 bg-yellow-600', 'text-blue-400 bg-blue-600'];
        @endphp

        @foreach($topCategories as $index => $category)
        <div class="bg-slate-800/50 rounded-lg p-6 cursor-pointer hover:bg-slate-700/50 transition" 
             onclick="goToCategory('{{ $category->id }}', '{{ $category->name }}')">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 {{ $colors[$index] ?? 'bg-purple-600' }} rounded-lg flex items-center justify-center">
              <i class="fas fa-crown text-white"></i>
            </div>
            <div>
              <h3 class="font-bold text-lg">{{ $category->name }}</h3>
              <p class="text-slate-400">{{ $labels[$index] ?? 'Kategori' }}</p>
              <p class="{{ explode(' ', $colors[$index] ?? 'text-purple-400')[0] }} text-sm">{{ $category->products_count }} produk tersedia</p>
            </div>
          </div>
        </div>
        @endforeach

      </div>
    </div>
    @endif

    <!-- Featured Collections -->
    <div class="mb-12">
      <h2 class="text-2xl font-bold mb-8">Koleksi Spesial</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <div class="bg-gradient-to-r from-red-600/20 to-orange-600/20 rounded-xl p-6 cursor-pointer hover:from-red-600/30 hover:to-orange-600/30 transition" onclick="goToCollection('sale')">
          <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-red-600 rounded-xl flex items-center justify-center">
              <i class="fas fa-fire text-white text-2xl"></i>
            </div>
            <div>
              <h3 class="text-xl font-bold">Stok Terbatas</h3>
              <p class="text-slate-300">Produk dengan stok sedikit</p>
              <p class="text-red-400 font-semibold">{{ $saleProducts }} produk</p>
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-600/20 to-orange-600/20 rounded-xl p-6 cursor-pointer hover:from-yellow-600/30 hover:to-orange-600/30 transition" onclick="goToCollection('new')">
          <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-yellow-600 rounded-xl flex items-center justify-center">
              <i class="fas fa-sparkles text-white text-2xl"></i>
            </div>
            <div>
              <h3 class="text-xl font-bold">Produk Terbaru</h3>
              <p class="text-slate-300">Koleksi terbaru 2025</p>
              <p class="text-yellow-400 font-semibold">{{ $newArrivals }} produk baru</p>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- All Categories List -->
    @if($categories->count() > 6)
    <div class="mb-12">
      <h2 class="text-2xl font-bold mb-8">Semua Kategori</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($categories as $category)
        <div class="bg-slate-800/50 rounded-lg p-4 text-center cursor-pointer hover:bg-slate-700/50 transition" 
             onclick="goToCategory('{{ $category->id }}', '{{ $category->name }}')">
          <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg mx-auto mb-3 flex items-center justify-center">
            <i class="fas fa-tags text-white"></i>
          </div>
          <h4 class="font-semibold mb-1">{{ $category->name }}</h4>
          <p class="text-slate-400 text-sm">{{ $category->products_count }} produk</p>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    <!-- Quick Stats -->
    <div class="bg-slate-800/30 rounded-xl p-8">
      <h2 class="text-2xl font-bold mb-6 text-center">Kenapa Pilih SoleStyle?</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
        
        <div>
          <div class="text-3xl font-bold text-purple-400 mb-2">{{ $totalProducts }}+</div>
          <p class="text-slate-400">Total Produk</p>
        </div>

        <div>
          <div class="text-3xl font-bold text-green-400 mb-2">{{ $totalCategories }}+</div>
          <p class="text-slate-400">Kategori Tersedia</p>
        </div>

        <div>
          <div class="text-3xl font-bold text-blue-400 mb-2">5K+</div>
          <p class="text-slate-400">Customer Puas</p>
        </div>

        <div>
          <div class="text-3xl font-bold text-yellow-400 mb-2">4.8★</div>
          <p class="text-slate-400">Rating Toko</p>
        </div>

      </div>
    </div>

  </div>
</section>
@endsection

@section('scripts')
<script>
  function goToCategory(categoryId, categoryName) {
    // Redirect ke halaman produk berdasarkan kategori
    window.location.href = `{{ url('/kategori') }}/${categoryId}`;
  }

  function goToCollection(collection) {
    if (collection === 'sale') {
      // Redirect ke produk dengan stok rendah
      window.location.href = `{{ url('produk') }}?stock=low`;
    } else if (collection === 'new') {
      // Redirect ke produk terbaru
      window.location.href = `{{ url('produk') }}?sort=newest`;
    }
  }

  // Add hover animations
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.category-card').forEach(card => {
      card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-4px) scale(1.02)';
      });
      
      card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
      });
    });

    // Auto refresh data setiap 30 detik untuk update real-time
    setInterval(function() {
      // Bisa ditambahkan AJAX call untuk update counter tanpa reload halaman
      updateCategoryCounters();
    }, 30000);
  });

  function updateCategoryCounters() {
    fetch('{{ url('kategori') }}')
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Update counters jika diperlukan
          console.log('Category data updated:', data.data);
        }
      })
      .catch(error => {
        console.log('Error updating categories:', error);
      });
  }
</script>
@endsection