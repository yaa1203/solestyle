<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SoleStyle - Toko Sepatu Premium</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-15px); }
    }
    .float { animation: float 6s ease-in-out infinite; }
    
    @keyframes fadeInUp {
      from { 
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .fadeInUp { animation: fadeInUp 1s ease-out; }
    
    @keyframes pulse-glow {
      0%, 100% { box-shadow: 0 0 20px rgba(147, 51, 234, 0.3); }
      50% { box-shadow: 0 0 40px rgba(147, 51, 234, 0.6); }
    }
    .pulse-glow { animation: pulse-glow 2s ease-in-out infinite; }
    
    @keyframes slideInFromLeft {
      0% { opacity: 0; transform: translateX(-100px); }
      100% { opacity: 1; transform: translateX(0); }
    }
    
    @keyframes slideInFromRight {
      0% { opacity: 0; transform: translateX(100px); }
      100% { opacity: 1; transform: translateX(0); }
    }
    
    .slide-in-left { animation: slideInFromLeft 0.8s ease-out; }
    .slide-in-right { animation: slideInFromRight 0.8s ease-out; }
    
    .glass-effect {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .product-card {
      transition: all 0.3s ease;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .product-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 40px rgba(147, 51, 234, 0.2);
      border-color: rgba(147, 51, 234, 0.3);
    }
    
    .category-card:hover {
      background: linear-gradient(135deg, rgba(147, 51, 234, 0.2), rgba(236, 72, 153, 0.2));
      transform: scale(1.05);
    }
    
    .text-gradient {
      background: linear-gradient(135deg, #9333ea, #ec4899);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    @keyframes counter {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .counter-animation { animation: counter 1s ease-out forwards; }
    
    .notification {
      position: fixed;
      top: 100px;
      right: 20px;
      z-index: 1000;
      opacity: 0;
      transform: translateX(400px);
      transition: all 0.3s ease;
    }
    
    .notification.show {
      opacity: 1;
      transform: translateX(0);
    }
    
    .search-overlay {
      background: rgba(0, 0, 0, 0.9);
      backdrop-filter: blur(10px);
    }
  </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white overflow-x-hidden">

  <!-- Loading Screen -->
  <div id="loading-screen" class="fixed inset-0 bg-slate-900 z-50 flex items-center justify-center">
    <div class="text-center">
      <div class="text-4xl font-bold mb-4 text-gradient">SoleStyle</div>
      <div class="loading-spinner w-8 h-8 border-4 border-purple-200 border-t-purple-600 rounded-full animate-spin mx-auto"></div>
    </div>
  </div>

  <!-- Notification -->
  <div id="notification" class="notification glass-effect text-white p-4 rounded-lg shadow-lg">
    <div class="flex items-center gap-3">
      <i class="fas fa-check-circle text-green-400"></i>
      <span id="notification-text">Produk berhasil ditambahkan ke wishlist!</span>
      <button onclick="closeNotification()" class="ml-auto text-slate-400 hover:text-white">
        <i class="fas fa-times"></i>
      </button>
    </div>
  </div>

  <!-- Search Overlay -->
  <div id="search-overlay" class="search-overlay fixed inset-0 z-40 hidden items-center justify-center">
    <div class="glass-effect rounded-2xl p-8 max-w-2xl w-full mx-4">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-2xl font-bold">Cari Sepatu</h3>
        <button onclick="closeSearch()" class="text-slate-400 hover:text-white">
          <i class="fas fa-times text-2xl"></i>
        </button>
      </div>
      <div class="relative">
        <input type="text" placeholder="Cari sepatu favorit Anda..." 
               class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-6 py-4 text-lg focus:border-purple-500 focus:outline-none">
        <button class="absolute right-4 top-1/2 transform -translate-y-1/2 text-purple-400">
          <i class="fas fa-search text-xl"></i>
        </button>
      </div>
      <div class="flex flex-wrap gap-2 mt-4">
        <span class="px-3 py-1 bg-slate-700 rounded-full text-sm cursor-pointer hover:bg-purple-600 transition">Nike</span>
        <span class="px-3 py-1 bg-slate-700 rounded-full text-sm cursor-pointer hover:bg-purple-600 transition">Adidas</span>
        <span class="px-3 py-1 bg-slate-700 rounded-full text-sm cursor-pointer hover:bg-purple-600 transition">Sneakers</span>
        <span class="px-3 py-1 bg-slate-700 rounded-full text-sm cursor-pointer hover:bg-purple-600 transition">Running</span>
      </div>
    </div>
  </div>

  <!-- Background Animasi -->
  <div class="absolute inset-0 -z-10 overflow-hidden">
    <div class="absolute top-20 left-20 w-96 h-96 bg-purple-600 rounded-full opacity-20 blur-3xl float"></div>
    <div class="absolute bottom-20 right-20 w-[500px] h-[500px] bg-pink-500 rounded-full opacity-20 blur-3xl float" style="animation-delay: -3s;"></div>
    <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-blue-500 rounded-full opacity-10 blur-3xl float" style="animation-delay: -1.5s;"></div>
  </div>

  <!-- Navbar -->
  <header class="sticky top-0 z-50 glass-effect shadow-lg">
    <nav class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
      <h1 class="text-3xl font-bold text-gradient cursor-pointer">SoleStyle</h1>

      <!-- Menu Desktop -->
      <ul class="hidden md:flex gap-8 text-slate-300">
        <li><a href="#home" class="nav-link hover:text-white transition-all duration-300 relative">Beranda</a></li>
        <li><a href="#products" class="nav-link hover:text-white transition-all duration-300 relative">Katalog</a></li>
        <li><a href="#categories" class="nav-link hover:text-white transition-all duration-300 relative">Kategori</a></li>
        <li><a href="#about" class="nav-link hover:text-white transition-all duration-300 relative">Tentang</a></li>
        <li><a href="#contact" class="nav-link hover:text-white transition-all duration-300 relative">Kontak</a></li>
      </ul>

      <!-- Action Buttons -->
      <div class="hidden md:flex items-center gap-4">
        <button onclick="openSearch()" class="text-slate-300 hover:text-white transition-all p-2 hover:bg-slate-700/50 rounded-lg">
          <i class="fas fa-search text-xl"></i>
        </button>
        <button class="relative text-slate-300 hover:text-white transition-all p-2 hover:bg-slate-700/50 rounded-lg">
          <i class="fas fa-heart text-xl"></i>
          <span class="absolute -top-1 -right-1 bg-purple-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
        </button>
        <button class="relative text-slate-300 hover:text-white transition-all p-2 hover:bg-slate-700/50 rounded-lg">
          <i class="fas fa-shopping-cart text-xl"></i>
          <span class="absolute -top-1 -right-1 bg-pink-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">2</span>
        </button>
        <a href="#" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 px-6 py-2 rounded-xl font-semibold transition-all transform hover:scale-105">Login</a>
      </div>

      <!-- Tombol Mobile -->
      <button id="menu-btn" class="md:hidden text-white text-3xl hover:text-purple-400 transition">
        <i class="fas fa-bars"></i>
      </button>
    </nav>

    <!-- Menu Mobile -->
    <div id="menu" class="hidden md:hidden flex-col glass-effect text-slate-200 px-6 py-4 space-y-4">
      <a href="#home" class="block hover:text-white transition">Beranda</a>
      <a href="#products" class="block hover:text-white transition">Katalog</a>
      <a href="#categories" class="block hover:text-white transition">Kategori</a>
      <a href="#about" class="block hover:text-white transition">Tentang</a>
      <a href="#contact" class="block hover:text-white transition">Kontak</a>
      <div class="flex gap-4 pt-4">
        <button class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 py-2 rounded-xl font-semibold">Login</button>
        <button onclick="openSearch()" class="p-2 border border-slate-600 rounded-xl hover:border-purple-500 transition">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section id="home" class="relative min-h-screen flex items-center">
    <!-- Hero Background Slider -->
    <div class="absolute inset-0 -z-20">
      <div class="absolute inset-0 bg-gradient-to-r from-slate-900/95 via-slate-800/85 to-slate-900/95 z-10"></div>
      
      <!-- Slider Container -->
      <div class="slider-container w-full h-full overflow-hidden">
        <div id="slider" class="slider flex transition-transform duration-1000 ease-in-out h-full">
          <!-- Slide 1 -->
          <div class="slide min-w-full h-full relative">
            <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80" 
                 alt="Premium Sneakers Collection" 
                 class="w-full h-full object-cover">
          </div>
          <!-- Slide 2 -->
          <div class="slide min-w-full h-full relative">
            <img src="https://images.unsplash.com/photo-1560769629-975ec94e6a86?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80" 
                 alt="Nike Air Jordan Collection" 
                 class="w-full h-full object-cover">
          </div>
          <!-- Slide 3 -->
          <div class="slide min-w-full h-full relative">
            <img src="https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80" 
                 alt="Adidas Sneakers Collection" 
                 class="w-full h-full object-cover">
          </div>
          <!-- Slide 4 -->
          <div class="slide min-w-full h-full relative">
            <img src="https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80" 
                 alt="Converse All Star Collection" 
                 class="w-full h-full object-cover">
          </div>
        </div>
      </div>

      <!-- Slider Arrow Navigation -->
      <div id="prevSlide">
        <div id="nextSlide">
          <!-- Slider Navigation -->
          <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex gap-3 z-20">
            <button class="slider-dot w-4 h-4 rounded-full bg-white/30 hover:bg-white transition-all duration-300 pulse-glow" data-slide="0"></button>
            <button class="slider-dot w-4 h-4 rounded-full bg-white/30 hover:bg-white transition-all duration-300" data-slide="1"></button>
            <button class="slider-dot w-4 h-4 rounded-full bg-white/30 hover:bg-white transition-all duration-300" data-slide="2"></button>
            <button class="slider-dot w-4 h-4 rounded-full bg-white/30 hover:bg-white transition-all duration-300" data-slide="3"></button>
          </div>
        </div>
      </div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6 py-24 grid lg:grid-cols-2 gap-12 items-center min-h-screen">
      <!-- Content Section -->
      <div class="space-y-8 fadeInUp lg:pr-8">
        <!-- Badge/Label -->
        <div class="slide-in-left">
          <span class="inline-flex items-center gap-2 px-4 py-2 glass-effect rounded-full text-purple-400 font-semibold text-sm tracking-wide border border-purple-400/20">
            <i class="fas fa-star animate-pulse"></i>
            KOLEKSI PREMIUM TERBARU 2025
          </span>
        </div>
        
        <!-- Main Headline -->
        <div class="space-y-4 slide-in-right">
          <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold leading-tight">
            Gaya Hidup<br>
            <span class="text-gradient">Modern</span><br>
            Dimulai dari <span class="text-white underline decoration-purple-500 underline-offset-4">Kaki</span>
          </h1>
        </div>
        
        <!-- Description -->
        <p class="text-slate-300 text-xl leading-relaxed max-w-lg fadeInUp">
          Jelajahi koleksi sepatu eksklusif dari brand terkemuka dunia. Kualitas premium, desain terdepan, dan kenyamanan yang tak tertandingi.
        </p>
        
        <!-- Key Features -->
        <div class="flex flex-wrap gap-6 text-sm fadeInUp" style="animation-delay: 0.3s;">
          <div class="flex items-center gap-2 text-slate-300">
            <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
            <span>Gratis Ongkir Seluruh Indonesia</span>
          </div>
          <div class="flex items-center gap-2 text-slate-300">
            <div class="w-2 h-2 bg-pink-500 rounded-full"></div>
            <span>Garansi Original 100%</span>
          </div>
          <div class="flex items-center gap-2 text-slate-300">
            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
            <span>Easy Return 30 Hari</span>
          </div>
        </div>
        
        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 pt-4 fadeInUp" style="animation-delay: 0.5s;">
          <a href="#products" class="group bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 px-8 py-4 rounded-2xl font-bold text-lg transition-all transform hover:scale-105 hover:shadow-2xl hover:shadow-purple-500/25 flex items-center justify-center gap-3">
            <i class="fas fa-shopping-bag group-hover:animate-bounce"></i>
            Belanja Sekarang
            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
          </a>
          <a href="#categories" class="group px-8 py-4 border-2 border-slate-600 hover:border-purple-500 rounded-2xl font-semibold text-lg transition-all hover:bg-purple-500/10 flex items-center justify-center gap-3">
            <i class="fas fa-play group-hover:scale-110 transition-transform"></i>
            Lihat Koleksi
          </a>
        </div>
      </div>
      
      <!-- Visual/Stats Section -->
      <div class="space-y-8 fadeInUp lg:pl-8" style="animation-delay: 0.4s;">
        <!-- Featured Product Showcase -->
        <div class="glass-effect rounded-3xl p-8 group hover:bg-slate-800/50 transition-all duration-500">
          <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl flex items-center justify-center">
              <i class="fas fa-crown text-white text-xl"></i>
            </div>
            <div>
              <h3 class="text-xl font-bold">Produk Pilihan Editor</h3>
              <p class="text-purple-400 text-sm">Rekomendasi Terbaik Bulan Ini</p>
            </div>
          </div>
          
          <div class="bg-slate-800/50 rounded-2xl p-4 flex items-center gap-4 hover:bg-slate-700/50 transition-all cursor-pointer">
            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" 
                 alt="Featured Product" 
                 class="w-16 h-16 object-cover rounded-xl group-hover:scale-110 transition-transform">
            <div class="flex-1">
              <h4 class="font-semibold mb-1">Nike Air Max Pro</h4>
              <div class="flex items-center gap-2">
                <span class="text-purple-400 font-bold">Rp 1.200.000</span>
                <div class="text-yellow-400 text-sm">
                  <i class="fas fa-star"></i> 4.8
                </div>
              </div>
            </div>
            <i class="fas fa-arrow-right text-purple-400 group-hover:translate-x-1 transition-transform"></i>
          </div>
        </div>
        
        <!-- Statistics -->
        <div class="grid grid-cols-3 gap-4">
          <div class="glass-effect rounded-2xl p-6 text-center hover:bg-slate-800/30 transition-all">
            <div class="text-3xl font-bold text-gradient mb-2" id="counter1">0</div>
            <div class="text-slate-400 text-sm">Brand Ternama</div>
          </div>
          <div class="glass-effect rounded-2xl p-6 text-center hover:bg-slate-800/30 transition-all">
            <div class="text-3xl font-bold text-gradient mb-2"><span id="counter2">0</span>+</div>
            <div class="text-slate-400 text-sm">Produk</div>
          </div>
          <div class="glass-effect rounded-2xl p-6 text-center hover:bg-slate-800/30 transition-all">
            <div class="text-3xl font-bold text-gradient mb-2"><span id="counter3">0</span>%</div>
            <div class="text-slate-400 text-sm">Kepuasan</div>
          </div>
        </div>
        
        <!-- Trust Indicators -->
        <div class="glass-effect rounded-2xl p-6">
          <h4 class="text-lg font-semibold mb-4 text-center">Dipercaya Oleh</h4>
          <div class="flex items-center justify-center gap-8 opacity-60">
            <div class="text-center">
              <i class="fab fa-google text-2xl mb-2"></i>
              <div class="text-xs">Google Partner</div>
            </div>
            <div class="text-center">
              <i class="fas fa-shield-alt text-2xl mb-2"></i>
              <div class="text-xs">SSL Secured</div>
            </div>
            <div class="text-center">
              <i class="fas fa-award text-2xl mb-2"></i>
              <div class="text-xs">Best Service</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Kategori Section -->
  <section id="categories" class="max-w-7xl mx-auto px-6 py-20">
    <div class="text-center mb-16">
      <h3 class="text-4xl font-bold mb-32 text-gradient">Kategori Pilihan</h3>
      <p class="text-slate-400 text-xl max-w-2xl mx-auto">Temukan sepatu sesuai dengan gaya dan kebutuhan Anda</p>
    </div>
    
    <div class="grid md:grid-cols-4 gap-6">
      <div class="category-card glass-effect rounded-2xl p-6 text-center transition-all duration-300 cursor-pointer group">
        <div class="text-5xl mb-4 group-hover:scale-110 transition-transform">
          <i class="fas fa-running text-purple-400"></i>
        </div>
        <h4 class="text-xl font-bold mb-2">Sneakers</h4>
        <p class="text-slate-400 text-sm">Gaya kasual dan modern</p>
        <div class="text-purple-400 font-semibold mt-2">120+ produk</div>
      </div>
      
      <div class="category-card glass-effect rounded-2xl p-6 text-center transition-all duration-300 cursor-pointer group">
        <div class="text-5xl mb-4 group-hover:scale-110 transition-transform">
          <i class="fas fa-dumbbell text-pink-400"></i>
        </div>
        <h4 class="text-xl font-bold mb-2">Olahraga</h4>
        <p class="text-slate-400 text-sm">Performance maksimal</p>
        <div class="text-purple-400 font-semibold mt-2">85+ produk</div>
      </div>
      
      <div class="category-card glass-effect rounded-2xl p-6 text-center transition-all duration-300 cursor-pointer group">
        <div class="text-5xl mb-4 group-hover:scale-110 transition-transform">
          <i class="fas fa-hiking text-blue-400"></i>
        </div>
        <h4 class="text-xl font-bold mb-2">Boots</h4>
        <p class="text-slate-400 text-sm">Tahan lama dan stylish</p>
        <div class="text-purple-400 font-semibold mt-2">60+ produk</div>
      </div>
      
      <div class="category-card glass-effect rounded-2xl p-6 text-center transition-all duration-300 cursor-pointer group">
        <div class="text-5xl mb-4 group-hover:scale-110 transition-transform">
          <i class="fas fa-user-tie text-green-400"></i>
        </div>
        <h4 class="text-xl font-bold mb-2">Formal</h4>
        <p class="text-slate-400 text-sm">Elegan dan profesional</p>
        <div class="text-purple-400 font-semibold mt-2">45+ produk</div>
      </div>
    </div>
  </section>

  <!-- Produk Unggulan -->
  <section id="products" class="max-w-7xl mx-auto px-6 py-20">
    <div class="text-center mb-16">
      <h3 class="text-4xl font-bold mb-4 text-gradient">Produk Unggulan</h3>
      <p class="text-slate-400 text-xl max-w-2xl mx-auto">Koleksi terpilih dengan kualitas terbaik dan desain terdepan</p>
    </div>
    
    <!-- Filter Buttons -->
    <div class="flex flex-wrap justify-center gap-4 mb-12">
      <button class="filter-btn active px-6 py-3 rounded-full glass-effect hover:bg-purple-600 transition-all" data-filter="all">Semua</button>
      <button class="filter-btn px-6 py-3 rounded-full glass-effect hover:bg-purple-600 transition-all" data-filter="nike">Nike</button>
      <button class="filter-btn px-6 py-3 rounded-full glass-effect hover:bg-purple-600 transition-all" data-filter="adidas">Adidas</button>
      <button class="filter-btn px-6 py-3 rounded-full glass-effect hover:bg-purple-600 transition-all" data-filter="converse">Converse</button>
    </div>
    
    <div class="grid md:grid-cols-3 gap-8" id="products-grid">
      <!-- Product Card 1 -->
      <div class="product-card glass-effect rounded-2xl overflow-hidden group" data-category="nike">
        <div class="relative overflow-hidden">
          <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" 
               alt="Nike Air Max Pro" 
               class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
          <div class="absolute top-4 left-4">
            <span class="bg-purple-600 text-white px-3 py-1 rounded-full text-sm font-semibold">Trending</span>
          </div>
          <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
            <button onclick="addToWishlist(this)" class="p-2 bg-white/20 backdrop-blur-sm rounded-full hover:bg-white/30 transition">
              <i class="fas fa-heart text-white"></i>
            </button>
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </div>
        <div class="p-6">
          <div class="flex justify-between items-start mb-2">
            <h4 class="text-xl font-bold">Nike Air Max Pro</h4>
            <div class="text-yellow-400">
              <i class="fas fa-star"></i>
              <span class="text-sm text-slate-400">4.8</span>
            </div>
          </div>
          <p class="text-slate-400 mb-4">Sneaker premium untuk gaya santai maupun olahraga dengan teknologi Air Max terdepan.</p>
          <div class="flex items-center justify-between mb-4">
            <div>
              <span class="text-purple-400 font-bold text-xl">Rp 1.200.000</span>
              <span class="text-slate-500 line-through text-sm ml-2">Rp 1.500.000</span>
            </div>
            <span class="text-green-400 text-sm font-semibold">20% OFF</span>
          </div>
          <div class="flex gap-2 mb-4">
            <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">38</button>
            <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">39</button>
            <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">40</button>
            <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">41</button>
          </div>
          <button onclick="addToCart(this)" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 flex items-center justify-center gap-2">
            <i class="fas fa-shopping-cart"></i>
            Tambah ke Keranjang
          </button>
        </div>
      </div>

      <!-- Product Card 2 -->
      <div class="product-card glass-effect rounded-2xl overflow-hidden group" data-category="adidas">
        <div class="relative overflow-hidden">
          <img src="https://images.unsplash.com/photo-1556906781-9a412961c28c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" 
               alt="Adidas Ultraboost 22" 
               class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
          <div class="absolute top-4 left-4">
            <span class="bg-green-600 text-white px-3 py-1 rounded-full text-sm font-semibold">Best Seller</span>
          </div>
          <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
            <button onclick="addToWishlist(this)" class="p-2 bg-white/20 backdrop-blur-sm rounded-full hover:bg-white/30 transition">
              <i class="fas fa-heart text-white"></i>
            </button>
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </div>
        <div class="p-6">
          <div class="flex justify-between items-start mb-2">
            <h4 class="text-xl font-bold">Adidas Ultraboost 22</h4>
            <div class="text-yellow-400">
              <i class="fas fa-star"></i>
              <span class="text-sm text-slate-400">4.9</span>
            </div>
          </div>
          <p class="text-slate-400 mb-4">Nyaman dipakai sepanjang hari dengan teknologi boost yang revolusioner.</p>
          <div class="flex items-center justify-between mb-4">
            <div>
              <span class="text-purple-400 font-bold text-xl">Rp 2.000.000</span>
              <span class="text-slate-500 line-through text-sm ml-2">Rp 2.200.000</span>
            </div>
            <span class="text-green-400 text-sm font-semibold">9% OFF</span>
          </div>
          <div class="flex gap-2 mb-4">
            <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">38</button>
            <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">39</button>
            <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">40</button>
            <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">41</button>
          </div>
          <button onclick="addToCart(this)" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 flex items-center justify-center gap-2">
            <i class="fas fa-shopping-cart"></i>
            Tambah ke Keranjang
          </button>
        </div>
      </div>

      <!-- Product Card 3 -->
      <div class="product-card glass-effect rounded-2xl overflow-hidden group" data-category="converse">
        <div class="relative overflow-hidden">
          <img src="https://images.unsplash.com/photo-1607522370275-f14206abe5d3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" 
               alt="Converse Chuck Taylor" 
               class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
          <div class="absolute top-4 left-4">
            <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">Classic</span>
          </div>
          <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
            <button onclick="addToWishlist(this)" class="p-2 bg-white/20 backdrop-blur-sm rounded-full hover:bg-white/30 transition">
              <i class="fas fa-heart text-white"></i>
            </button>
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </div>
        <div class="p-6">
          <div class="flex justify-between items-start mb-2">
            <h4 class="text-xl font-bold">Converse Chuck Taylor</h4>
            <div class="text-yellow-400">
              <i class="fas fa-star"></i>
              <span class="text-sm text-slate-400">4.7</span>
            </div>
          </div>
          <p class="text-slate-400 mb-4">Sepatu klasik yang tak lekang oleh waktu dengan desain ikonik.</p>
          <div class="flex items-center justify-between mb-4">
            <div>
              <span class="text-purple-400 font-bold text-xl">Rp 850.000</span>
            </div>
          </div>
          <div class="flex gap-2 mb-4">
            <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">38</button>
            <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">39</button>
            <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">40</button>
            <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">41</button>
          </div>
          <button onclick="addToCart(this)" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 flex items-center justify-center gap-2">
            <i class="fas fa-shopping-cart"></i>
            Tambah ke Keranjang
          </button>
        </div>
      </div>
    </div>
    
    <!-- Load More Button -->
    <div class="text-center mt-12">
      <button class="px-8 py-4 glass-effect hover:bg-purple-600/20 rounded-xl font-semibold transition-all transform hover:scale-105">
        <i class="fas fa-plus mr-2"></i>
        Muat Lebih Banyak
      </button>
    </div>
  </section>

  <!-- Newsletter Section -->
  <section class="max-w-7xl mx-auto px-6 py-20">
    <div class="glass-effect rounded-3xl p-12 text-center">
      <h3 class="text-3xl font-bold mb-4 text-gradient">Dapatkan Update Terbaru</h3>
      <p class="text-slate-400 text-lg mb-8 max-w-2xl mx-auto">Berlangganan newsletter kami untuk mendapatkan info produk terbaru, diskon khusus, dan tips fashion.</p>
      <div class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
        <input type="email" placeholder="Email address" class="flex-1 bg-slate-800/50 border border-slate-600 rounded-xl px-6 py-4 focus:border-purple-500 focus:outline-none">
        <button class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 px-8 py-4 rounded-xl font-semibold transition-all transform hover:scale-105 flex items-center justify-center gap-2">
          <i class="fas fa-paper-plane"></i>
          Subscribe
        </button>
      </div>
      <p class="text-slate-500 text-sm mt-4">*Dapatkan diskon 10% untuk pembelian pertama Anda!</p>
    </div>
  </section>

  <!-- Testimonial Section -->
  <section class="max-w-7xl mx-auto px-6 py-20">
    <div class="text-center mb-16">
      <h3 class="text-4xl font-bold mb-4 text-gradient">Apa Kata Mereka</h3>
      <p class="text-slate-400 text-xl max-w-2xl mx-auto">Testimoni dari pelanggan setia SoleStyle</p>
    </div>
    
    <div class="grid md:grid-cols-3 gap-8">
      <div class="glass-effect rounded-2xl p-6">
        <div class="flex items-center mb-4">
          <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80" 
               alt="Customer" class="w-12 h-12 rounded-full mr-4">
          <div>
            <h5 class="font-semibold">Budi Santoso</h5>
            <div class="text-yellow-400 text-sm">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
          </div>
        </div>
        <p class="text-slate-300">"Kualitas sepatu luar biasa! Nyaman dipakai seharian dan desainnya sangat keren. Pasti akan beli lagi!"</p>
      </div>
      
      <div class="glass-effect rounded-2xl p-6">
        <div class="flex items-center mb-4">
          <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80" 
               alt="Customer" class="w-12 h-12 rounded-full mr-4">
          <div>
            <h5 class="font-semibold">Sari Dewi</h5>
            <div class="text-yellow-400 text-sm">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
          </div>
        </div>
        <p class="text-slate-300">"Pelayanan customer service sangat baik, pengiriman cepat, dan produk sesuai ekspektasi. Recommended!"</p>
      </div>
      
      <div class="glass-effect rounded-2xl p-6">
        <div class="flex items-center mb-4">
          <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80" 
               alt="Customer" class="w-12 h-12 rounded-full mr-4">
          <div>
            <h5 class="font-semibold">Ahmad Rahman</h5>
            <div class="text-yellow-400 text-sm">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
          </div>
        </div>
        <p class="text-slate-300">"Koleksi lengkap, harga terjangkau, dan kualitas original. SoleStyle memang yang terbaik!"</p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-slate-900/80 backdrop-blur-md border-t border-slate-800 py-16 mt-20">
    <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-8 px-6">
      <div class="col-span-1 md:col-span-2">
        <h4 class="text-3xl font-bold mb-4 text-gradient">SoleStyle</h4>
        <p class="text-slate-400 mb-6 max-w-md">Toko sepatu modern dengan koleksi stylish, nyaman, dan berkualitas tinggi. Ekspresikan gaya unik Anda bersama kami.</p>
        <div class="flex gap-4">
          <a href="#" class="w-10 h-10 bg-slate-800 hover:bg-purple-600 rounded-full flex items-center justify-center transition">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="#" class="w-10 h-10 bg-slate-800 hover:bg-purple-600 rounded-full flex items-center justify-center transition">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="#" class="w-10 h-10 bg-slate-800 hover:bg-purple-600 rounded-full flex items-center justify-center transition">
            <i class="fab fa-twitter"></i>
          </a>
          <a href="#" class="w-10 h-10 bg-slate-800 hover:bg-purple-600 rounded-full flex items-center justify-center transition">
            <i class="fab fa-tiktok"></i>
          </a>
        </div>
      </div>
      <div>
        <h5 class="font-bold mb-4 text-lg">Quick Links</h5>
        <ul class="space-y-3 text-slate-400">
          <li><a href="#home" class="hover:text-white transition flex items-center gap-2"><i class="fas fa-home text-sm"></i>Beranda</a></li>
          <li><a href="#products" class="hover:text-white transition flex items-center gap-2"><i class="fas fa-shopping-bag text-sm"></i>Katalog</a></li>
          <li><a href="#categories" class="hover:text-white transition flex items-center gap-2"><i class="fas fa-th-large text-sm"></i>Kategori</a></li>
          <li><a href="#about" class="hover:text-white transition flex items-center gap-2"><i class="fas fa-info-circle text-sm"></i>Tentang</a></li>
        </ul>
      </div>
      <div>
        <h5 class="font-bold mb-4 text-lg">Support</h5>
        <ul class="space-y-3 text-slate-400">
          <li><a href="#" class="hover:text-white transition flex items-center gap-2"><i class="fas fa-headset text-sm"></i>Customer Service</a></li>
          <li><a href="#" class="hover:text-white transition flex items-center gap-2"><i class="fas fa-shipping-fast text-sm"></i>Pengiriman</a></li>
          <li><a href="#" class="hover:text-white transition flex items-center gap-2"><i class="fas fa-undo text-sm"></i>Return Policy</a></li>
          <li><a href="#" class="hover:text-white transition flex items-center gap-2"><i class="fas fa-question-circle text-sm"></i>FAQ</a></li>
        </ul>
      </div>
    </div>
    
    <div class="max-w-7xl mx-auto px-6 mt-12 pt-8 border-t border-slate-800">
      <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="text-slate-500">© 2025 SoleStyle. All rights reserved.</div>
        <div class="flex items-center gap-6 text-slate-500 text-sm">
          <a href="#" class="hover:text-white transition">Privacy Policy</a>
          <a href="#" class="hover:text-white transition">Terms of Service</a>
          <a href="#" class="hover:text-white transition">Cookie Policy</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Back to Top Button -->
  <button id="back-to-top" class="fixed bottom-8 right-8 w-12 h-12 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 rounded-full flex items-center justify-center transition-all transform scale-0 z-50">
    <i class="fas fa-chevron-up text-white"></i>
  </button>

  <script>
    // Loading Screen
    window.addEventListener('load', () => {
      setTimeout(() => {
        document.getElementById('loading-screen').style.opacity = '0';
        setTimeout(() => {
          document.getElementById('loading-screen').style.display = 'none';
        }, 500);
      }, 1500);
    });

    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });

    // Toggle menu mobile
    const menuBtn = document.getElementById("menu-btn");
    const menu = document.getElementById("menu");

    menuBtn.addEventListener("click", () => {
      menu.classList.toggle("hidden");
    });

    // Counter Animation
    function animateCounter(element, target) {
      let current = 0;
      const increment = target / 100;
      const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
          element.textContent = target;
          clearInterval(timer);
        } else {
          element.textContent = Math.floor(current);
        }
      }, 20);
    }

    // Start counters when in viewport
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const counter1 = document.getElementById('counter1');
          const counter2 = document.getElementById('counter2');
          const counter3 = document.getElementById('counter3');
          
          animateCounter(counter1, 500);
          animateCounter(counter2, 50000);
          animateCounter(counter3, 98);
          
          observer.unobserve(entry.target);
        }
      });
    });

    observer.observe(document.getElementById('counter1'));

    // Slider functionality (enhanced)
    const slider = document.getElementById('slider');
    const slides = slider.children;
    const totalSlides = slides.length;
    const dots = document.querySelectorAll('.slider-dot');
    const prevBtn = document.getElementById('prevSlide');
    const nextBtn = document.getElementById('nextSlide');
    
    let currentSlide = 0;
    let autoSlideInterval;

    function updateSlider() {
      slider.style.transform = `translateX(-${currentSlide * 100}%)`;
      
      dots.forEach((dot, index) => {
        if (index === currentSlide) {
          dot.classList.remove('bg-white/30');
          dot.classList.add('bg-white', 'scale-125', 'shadow-lg');
        } else {
          dot.classList.remove('bg-white', 'scale-125', 'shadow-lg');
          dot.classList.add('bg-white/30');
        }
      });
    }

    function nextSlide() {
      currentSlide = (currentSlide + 1) % totalSlides;
      updateSlider();
    }

    function prevSlide() {
      currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
      updateSlider();
    }

    function goToSlide(index) {
      currentSlide = index;
      updateSlider();
    }

    function startAutoSlide() {
      autoSlideInterval = setInterval(nextSlide, 4000);
    }

    function stopAutoSlide() {
      clearInterval(autoSlideInterval);
    }

    // Event listeners
    nextBtn.addEventListener('click', () => {
      nextSlide();
      stopAutoSlide();
      startAutoSlide();
    });

    prevBtn.addEventListener('click', () => {
      prevSlide();
      stopAutoSlide();
      startAutoSlide();
    });

    dots.forEach((dot, index) => {
      dot.addEventListener('click', () => {
        goToSlide(index);
        stopAutoSlide();
        startAutoSlide();
      });
    });

    // Touch support
    let startX = 0;
    let endX = 0;

    slider.addEventListener('touchstart', (e) => {
      startX = e.touches[0].clientX;
      stopAutoSlide();
    });

    slider.addEventListener('touchend', (e) => {
      endX = e.changedTouches[0].clientX;
      const difference = startX - endX;
      
      if (Math.abs(difference) > 50) {
        if (difference > 0) {
          nextSlide();
        } else {
          prevSlide();
        }
      }
      startAutoSlide();
    });

    // Initialize slider
    updateSlider();
    startAutoSlide();

    // Search functionality
    function openSearch() {
      document.getElementById('search-overlay').classList.remove('hidden');
      document.getElementById('search-overlay').classList.add('flex');
    }

    function closeSearch() {
      document.getElementById('search-overlay').classList.add('hidden');
      document.getElementById('search-overlay').classList.remove('flex');
    }

    // Product filter
    const filterBtns = document.querySelectorAll('.filter-btn');
    const productCards = document.querySelectorAll('.product-card');

    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        // Remove active class from all buttons
        filterBtns.forEach(b => b.classList.remove('active', 'bg-purple-600'));
        // Add active class to clicked button
        btn.classList.add('active', 'bg-purple-600');

        const filter = btn.dataset.filter;

        productCards.forEach(card => {
          if (filter === 'all' || card.dataset.category === filter) {
            card.style.display = 'block';
            card.style.animation = 'fadeInUp 0.5s ease-out';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });

    // Add to cart functionality
    function addToCart(button) {
      button.innerHTML = '<i class="fas fa-check"></i> Ditambahkan!';
      button.classList.add('bg-green-600');
      
      setTimeout(() => {
        button.innerHTML = '<i class="fas fa-shopping-cart"></i> Tambah ke Keranjang';
        button.classList.remove('bg-green-600');
      }, 2000);

      showNotification('Produk berhasil ditambahkan ke keranjang!');
    }

    // Add to wishlist functionality
    function addToWishlist(button) {
      const icon = button.querySelector('i');
      icon.classList.toggle('fas');
      icon.classList.toggle('far');
      
      if (icon.classList.contains('fas')) {
        button.style.color = '#ec4899';
        showNotification('Produk berhasil ditambahkan ke wishlist!');
      } else {
        button.style.color = '';
        showNotification('Produk dihapus dari wishlist!');
      }
    }

    // Notification system
    function showNotification(message) {
      const notification = document.getElementById('notification');
      const notificationText = document.getElementById('notification-text');
      
      notificationText.textContent = message;
      notification.classList.add('show');

      setTimeout(() => {
        notification.classList.remove('show');
      }, 3000);
    }

    function closeNotification() {
      document.getElementById('notification').classList.remove('show');
    }

    // Back to top functionality
    const backToTopBtn = document.getElementById('back-to-top');

    window.addEventListener('scroll', () => {
      if (window.pageYOffset > 300) {
        backToTopBtn.style.transform = 'scale(1)';
      } else {
        backToTopBtn.style.transform = 'scale(0)';
      }
    });

    backToTopBtn.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });

    // Size selection
    document.querySelectorAll('.size-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        // Remove active from siblings
        btn.parentElement.querySelectorAll('.size-btn').forEach(b => {
          b.classList.remove('border-purple-500', 'bg-purple-500/20');
          b.classList.add('border-slate-600');
        });
        // Add active to clicked
        btn.classList.remove('border-slate-600');
        btn.classList.add('border-purple-500', 'bg-purple-500/20');
      });
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
      if (e.ctrlKey && e.key === 'k') {
        e.preventDefault();
        openSearch();
      }
      if (e.key === 'Escape') {
        closeSearch();
        closeNotification();
      }
    });
  </script>
</body>
</html>