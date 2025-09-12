@extends('user.layouts.app')
@section('title', 'Beranda - SoleStyle')
@section('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
* {
    font-family: 'Inter', sans-serif;
}
.animate-float {
    animation: float 6s ease-in-out infinite;
}
.animate-float-delayed {
    animation: float 6s ease-in-out infinite;
    animation-delay: -3s;
}
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
}
.gradient-text {
    background: linear-gradient(45deg, #8b5cf6, #ec4899, #06b6d4);
    background-size: 200% 200%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: gradient-shift 4s ease infinite;
}
@keyframes gradient-shift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}
.glass-effect {
    backdrop-filter: blur(20px);
    background: rgba(15, 23, 42, 0.7);
    border: 1px solid rgba(148, 163, 184, 0.1);
}
.product-card {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.product-card:hover {
    transform: translateY(-12px) scale(1.03);
    box-shadow: 0 35px 60px -12px rgba(139, 92, 246, 0.4);
}
.shine-effect {
    position: relative;
    overflow: hidden;
}
.shine-effect::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.6s;
}
.shine-effect:hover::before {
    left: 100%;
}
.hero-bg {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 25%, #334155 50%, #1e293b 75%, #0f172a 100%);
    position: relative;
}
.hero-bg::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 80%, rgba(139, 92, 246, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(236, 72, 153, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
}
.scroll-indicator {
    position: fixed;
    top: 0;
    left: 0;
    width: 0%;
    height: 3px;
    background: linear-gradient(90deg, #8b5cf6, #ec4899);
    z-index: 9999;
    transition: width 0.3s ease;
}
.parallax-element {
    transform: translateZ(0);
    will-change: transform;
}
.section-divider {
    height: 100px;
    background: linear-gradient(to right, transparent, rgba(139, 92, 246, 0.1), transparent);
    position: relative;
}
.section-divider::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #8b5cf6, #ec4899);
    border-radius: 2px;
}
.pulse-effect {
    animation: pulse-glow 2s ease-in-out infinite alternate;
}
@keyframes pulse-glow {
    from {
        box-shadow: 0 0 20px rgba(139, 92, 246, 0.5);
    }
    to {
        box-shadow: 0 0 30px rgba(236, 72, 153, 0.8);
    }
}
/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}
::-webkit-scrollbar-track {
    background: #1e293b;
}
::-webkit-scrollbar-thumb {
    background: #8b5cf6;
    border-radius: 4px;
}
::-webkit-scrollbar-thumb:hover {
    background: #7c3aed;
}
</style>
@endsection
@section('content')
<!-- Scroll Progress Indicator -->
<div class="scroll-indicator" id="scrollIndicator"></div>
<!-- Hero Section -->
<div class="hero-bg relative overflow-hidden min-h-screen flex items-center">
  <!-- Floating Elements -->
  <div class="absolute top-20 left-10 w-32 h-32 bg-purple-500/20 rounded-full blur-xl animate-float"></div>
  <div class="absolute bottom-20 right-10 w-48 h-48 bg-pink-500/20 rounded-full blur-xl animate-float-delayed"></div>
  
  <div class="container mx-auto px-4 py-20 relative z-10">
    <div class="flex flex-col md:flex-row items-center">
      <div class="md:w-1/2 mb-10 md:mb-0">
        <!-- Badge -->
        <div class="mb-6">
          <span class="inline-block bg-gradient-to-r from-purple-600/80 to-pink-600/80 backdrop-blur-sm text-white px-6 py-2 rounded-full text-sm font-semibold border border-white/10 pulse-effect">
            ✨ Koleksi Premium 2025
          </span>
        </div>
        
        <h1 class="text-5xl md:text-7xl font-black mb-6 leading-tight">
          <span class="gradient-text">Temukan Sepatu</span><br>
          <span class="text-white">Impianmu</span>
        </h1>
        
        <p class="text-slate-300 text-xl mb-8 leading-relaxed max-w-lg">
          Koleksi sepatu terlengkap dengan kualitas terbaik, desain terdepan, dan harga yang bersahabat untuk gaya hidup modern.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-6 mb-12">
          <a href="{{ url('produk') }}" class="group bg-gradient-to-r from-purple-600 via-purple-700 to-pink-600 hover:from-purple-700 hover:via-purple-800 hover:to-pink-700 text-white px-10 py-4 rounded-xl font-semibold transition-all transform hover:scale-105 shine-effect flex items-center justify-center text-lg shadow-2xl">
            <i class="fas fa-shopping-bag mr-3 group-hover:animate-bounce"></i>
            Jelajahi Koleksi
          </a>
          <a href="{{ url('kategori') }}" class="glass-effect hover:bg-slate-700/50 text-white px-10 py-4 rounded-xl font-semibold transition-all hover:scale-105 flex items-center justify-center text-lg border border-white/20">
            <i class="fas fa-tag mr-3"></i>
            Lihat Kategori
          </a>
        </div>
        
        <!-- Stats -->
        <div class="grid grid-cols-3 gap-8 max-w-md">
          <div class="text-center">
            <div class="text-3xl font-bold gradient-text">50K+</div>
            <div class="text-slate-400 text-sm font-medium">Pelanggan</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold gradient-text">1000+</div>
            <div class="text-slate-400 text-sm font-medium">Produk</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold gradient-text">24/7</div>
            <div class="text-slate-400 text-sm font-medium">Support</div>
          </div>
        </div>
      </div>
      
      <!-- Hero Image Area -->
      <div class="md:w-1/2 relative">
        <div class="relative">
          <div class="absolute inset-0 bg-gradient-to-r from-purple-500/30 to-pink-500/30 rounded-full blur-3xl animate-pulse"></div>
          <div class="glass-effect rounded-3xl p-8 border border-white/10 relative">
            <div class="text-center mb-6">
              <span class="text-purple-400 font-semibold text-lg">Featured Product</span>
              <h3 class="text-3xl font-bold text-white mt-2">Premium Collection</h3>
            </div>
            <div class="bg-gradient-to-br from-slate-700/50 to-slate-800/50 rounded-2xl h-72 flex items-center justify-center mb-6 border border-slate-600/30">
              <i class="fas fa-shoe-prints text-8xl text-slate-400 opacity-50"></i>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-3xl font-bold gradient-text">Rp 2.499.000</span>
              <button class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-3 rounded-xl font-semibold hover:scale-105 transition-transform shine-effect">
                <i class="fas fa-heart mr-2"></i>Favorit
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Scroll Down Indicator -->
  <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
    <div class="w-6 h-10 border-2 border-purple-400 rounded-full flex justify-center">
      <div class="w-1 h-3 bg-purple-400 rounded-full mt-2 animate-pulse"></div>
    </div>
  </div>
</div>
<!-- Section Divider -->
<div class="section-divider"></div>
<!-- Best Sellers Section -->
<div class="bg-slate-900 py-20 relative overflow-hidden">
  <!-- Background Elements -->
  <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>
  <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-purple-900/5 via-transparent to-transparent"></div>
  
  <div class="container mx-auto px-4 relative z-10">
    <div class="text-center mb-16">
      <span class="inline-block bg-gradient-to-r from-orange-500/80 to-red-500/80 backdrop-blur-sm text-white px-6 py-2 rounded-full text-sm font-semibold border border-white/10 mb-6 pulse-effect">
        🔥 TRENDING NOW
      </span>
      <h2 class="text-4xl md:text-6xl font-bold text-white mb-6">
        Produk <span class="gradient-text">Terlaris</span>
      </h2>
      <p class="text-slate-400 text-xl max-w-3xl mx-auto leading-relaxed">
        Sepatu pilihan favorit dengan rating tertinggi dan penjualan terbanyak dari koleksi premium kami
      </p>
    </div>
    
    <div class="flex justify-between items-center mb-12">
      <h3 class="text-2xl font-bold text-white flex items-center">
        <i class="fas fa-fire text-orange-400 mr-3 text-3xl"></i>
        Best Sellers
      </h3>
      <a href="{{ url('produk?sort=popular') }}" class="group text-purple-400 hover:text-purple-300 transition-colors text-lg font-semibold">
        Lihat Semua 
        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
      </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
      @foreach($bestSellers as $product)
      <a href="{{ route('produk.show', $product) }}" class="block group">
        <div class="product-card glass-effect rounded-2xl shadow-2xl overflow-hidden border border-slate-700/50 hover:border-purple-500/50">
          <div class="relative overflow-hidden">
            @if($product->image_exists)
              <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                   class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-500">
            @else
              <div class="w-full h-56 bg-gradient-to-br from-slate-700/50 to-slate-800/50 flex items-center justify-center">
                <div class="text-center text-slate-400">
                  <i class="fas fa-image text-5xl mb-3"></i>
                  <p class="text-sm font-medium">No Image</p>
                </div>
              </div>
            @endif
            
            <!-- Badges -->
            <div class="absolute top-4 left-4 flex flex-col gap-2">
              @if($product->total_stock <= 5 && $product->total_stock > 0)
                <span class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-xs px-3 py-2 rounded-full font-semibold backdrop-blur-sm">
                  <i class="fas fa-exclamation-triangle mr-1"></i>Stok Terbatas
                </span>
              @elseif($product->total_stock == 0)
                <span class="bg-gradient-to-r from-red-500 to-red-600 text-white text-xs px-3 py-2 rounded-full font-semibold backdrop-blur-sm">
                  <i class="fas fa-times-circle mr-1"></i>Habis
                </span>
              @endif
            </div>
            
            <span class="absolute top-4 right-4 bg-gradient-to-r from-orange-500 to-yellow-500 text-white text-xs px-3 py-2 rounded-full font-semibold backdrop-blur-sm pulse-effect">
              <i class="fas fa-fire mr-1"></i>Best Seller
            </span>
            
            <!-- Hover Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-6">
              <button class="bg-white text-slate-900 px-6 py-3 rounded-xl font-semibold transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 shadow-xl">
                <i class="fas fa-eye mr-2"></i>Lihat Detail
              </button>
            </div>
          </div>
          
          <div class="p-6">
            @if(isset($product->brand))
              <span class="text-purple-400 text-sm font-bold uppercase tracking-wider">{{ $product->brand }}</span>
            @endif
            <h3 class="font-bold text-lg text-white mt-2 mb-3 group-hover:text-purple-300 transition-colors line-clamp-2">{{ $product->name }}</h3>
            
            <div class="flex justify-between items-center">
              <span class="text-2xl font-bold gradient-text">{{ $product->formatted_price ?? 'Rp 0' }}</span>
              <span class="text-green-400 text-sm bg-green-400/10 px-3 py-2 rounded-full font-medium border border-green-400/20">
                <i class="fas fa-check-circle mr-1"></i>{{ $product->sold_count ?? '0' }} terjual
              </span>
            </div>
          </div>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</div>
<!-- Section Divider -->
<div class="section-divider"></div>
<!-- New Arrivals Section -->
<div class="bg-slate-900 py-20 relative overflow-hidden">
  <div class="absolute inset-0 bg-gradient-to-br from-blue-900/10 via-transparent to-cyan-900/10"></div>
  
  <div class="container mx-auto px-4 relative z-10">
    <div class="text-center mb-16">
      <span class="inline-block bg-gradient-to-r from-blue-500/80 to-cyan-500/80 backdrop-blur-sm text-white px-6 py-2 rounded-full text-sm font-semibold border border-white/10 mb-6 pulse-effect">
        ✨ FRESH ARRIVALS
      </span>
      <h2 class="text-4xl md:text-6xl font-bold text-white mb-6">
        Koleksi <span class="gradient-text">Terbaru</span>
      </h2>
      <p class="text-slate-400 text-xl max-w-3xl mx-auto leading-relaxed">
        Temukan sepatu dengan desain terdepan dan teknologi terkini yang baru diluncurkan
      </p>
    </div>
    
    <div class="flex justify-between items-center mb-12">
      <h3 class="text-2xl font-bold text-white flex items-center">
        <i class="fas fa-sparkles text-blue-400 mr-3 text-3xl"></i>
        New Arrivals
      </h3>
      <a href="{{ url('produk?sort=newest') }}" class="group text-purple-400 hover:text-purple-300 transition-colors text-lg font-semibold">
        Lihat Semua 
        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
      </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
      @foreach($newArrivals as $product)
      <a href="{{ route('produk.show', $product) }}" class="block group">
        <div class="product-card glass-effect rounded-2xl shadow-2xl overflow-hidden border border-slate-700/50 hover:border-blue-500/50">
          <div class="relative overflow-hidden">
            @if($product->image_exists)
              <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                   class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-500">
            @else
              <div class="w-full h-56 bg-gradient-to-br from-slate-700/50 to-slate-800/50 flex items-center justify-center">
                <div class="text-center text-slate-400">
                  <i class="fas fa-image text-5xl mb-3"></i>
                  <p class="text-sm font-medium">No Image</p>
                </div>
              </div>
            @endif
            
            <span class="absolute top-4 left-4 bg-gradient-to-r from-blue-500 to-cyan-500 text-white text-xs px-3 py-2 rounded-full font-semibold backdrop-blur-sm pulse-effect">
              <i class="fas fa-sparkles mr-1"></i>Baru
            </span>
            
            <!-- Hover Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-6">
              <button class="bg-white text-slate-900 px-6 py-3 rounded-xl font-semibold transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 shadow-xl">
                <i class="fas fa-eye mr-2"></i>Lihat Detail
              </button>
            </div>
          </div>
          
          <div class="p-6">
            @if(isset($product->brand))
              <span class="text-purple-400 text-sm font-bold uppercase tracking-wider">{{ $product->brand }}</span>
            @endif
            <h3 class="font-bold text-lg text-white mt-2 mb-3 group-hover:text-purple-300 transition-colors line-clamp-2">{{ $product->name }}</h3>
            
            <div class="flex justify-between items-center">
              <span class="text-2xl font-bold gradient-text">{{ $product->formatted_price ?? 'Rp 0' }}</span>
              <span class="text-blue-400 text-sm font-medium">
                <i class="fas fa-clock mr-1"></i>Baru
              </span>
            </div>
          </div>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</div>
<!-- Section Divider -->
<div class="section-divider"></div>
<!-- Newsletter Section Enhanced -->
<div class="relative py-20 overflow-hidden">
  <div class="absolute inset-0 bg-gradient-to-br from-purple-900/30 via-slate-900 to-pink-900/30"></div>
  <div class="absolute top-10 left-10 w-40 h-40 bg-purple-500/20 rounded-full blur-3xl animate-float"></div>
  <div class="absolute bottom-10 right-10 w-56 h-56 bg-pink-500/20 rounded-full blur-3xl animate-float-delayed"></div>
  
  <div class="container mx-auto px-4 relative z-10">
    <div class="max-w-4xl mx-auto text-center">
      <span class="inline-block bg-gradient-to-r from-purple-600/80 to-pink-600/80 backdrop-blur-sm text-white px-6 py-2 rounded-full text-sm font-semibold border border-white/10 mb-6 pulse-effect">
        📧 EXCLUSIVE OFFERS
      </span>
      
      <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
        Dapatkan <span class="gradient-text">Penawaran Eksklusif</span>
      </h2>
      <p class="text-slate-300 text-xl mb-10 leading-relaxed">
        Berlangganan newsletter kami untuk mendapatkan update produk terbaru, promo menarik, dan diskon eksklusif hingga 50%
      </p>
      
      <form action="{{ url('newsletter/subscribe') }}" method="POST" class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto mb-8">
        @csrf
        <input type="email" name="email" placeholder="Masukkan email Anda" required
               class="flex-1 glass-effect border border-slate-600/50 rounded-xl px-6 py-4 text-white placeholder-slate-400 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all text-lg">
        <button type="submit" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-8 py-4 rounded-xl font-semibold transition-all transform hover:scale-105 shine-effect text-lg shadow-2xl">
          <i class="fas fa-paper-plane mr-2"></i>Berlangganan
        </button>
      </form>
      
      <!-- Benefits -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto">
        <div class="glass-effect rounded-xl p-6 border border-white/10">
          <i class="fas fa-percentage text-3xl text-purple-400 mb-3"></i>
          <h4 class="text-white font-semibold mb-2">Diskon Eksklusif</h4>
          <p class="text-slate-400 text-sm">Hingga 50% off untuk member</p>
        </div>
        <div class="glass-effect rounded-xl p-6 border border-white/10">
          <i class="fas fa-bell text-3xl text-pink-400 mb-3"></i>
          <h4 class="text-white font-semibold mb-2">Update Terbaru</h4>
          <p class="text-slate-400 text-sm">Produk baru & restok favorit</p>
        </div>
        <div class="glass-effect rounded-xl p-6 border border-white/10">
          <i class="fas fa-gift text-3xl text-blue-400 mb-3"></i>
          <h4 class="text-white font-semibold mb-2">Gift & Promo</h4>
          <p class="text-slate-400 text-sm">Surprise khusus subscriber</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script>
// Enhanced toast notification
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
  toast.className = `fixed top-6 right-6 bg-gradient-to-r ${colors[type]} text-white px-8 py-4 rounded-2xl shadow-2xl z-50 transform translate-x-full transition-all duration-500 backdrop-blur-sm border border-white/20`;
  toast.innerHTML = `
    <div class="flex items-center space-x-4">
      <i class="fas ${icons[type]} text-xl"></i>
      <span class="font-semibold">${message}</span>
      <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white/80 hover:text-white">
        <i class="fas fa-times"></i>
      </button>
    </div>
  `;
  
  document.body.appendChild(toast);
  
  setTimeout(() => {
    toast.classList.remove('translate-x-full');
    toast.classList.add('translate-x-0');
  }, 100);
  
  setTimeout(() => {
    toast.classList.add('translate-x-full');
    setTimeout(() => {
      if (document.body.contains(toast)) {
        document.body.removeChild(toast);
      }
    }, 500);
  }, 4000);
}
// Scroll progress indicator
window.addEventListener('scroll', () => {
  const scrolled = (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100;
  document.getElementById('scrollIndicator').style.width = scrolled + '%';
});
// Parallax effect for floating elements
window.addEventListener('scroll', () => {
  const scrolled = window.pageYOffset;
  const parallax = document.querySelectorAll('.parallax-element');
  
  parallax.forEach(element => {
    const speed = element.dataset.speed || 0.5;
    element.style.transform = `translateY(${scrolled * speed}px)`;
  });
});
// Intersection Observer for animations
const observerOptions = {
  threshold: 0.1,
  rootMargin: '0px 0px -50px 0px'
};
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('animate-in');
    }
  });
}, observerOptions);
// Observe elements for animation
document.addEventListener('DOMContentLoaded', () => {
  const animateElements = document.querySelectorAll('.product-card, .glass-effect');
  animateElements.forEach(el => observer.observe(el));
});
</script>
@endsection