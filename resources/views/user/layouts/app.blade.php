<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SoleStyle - Toko Sepatu Online')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .navbar-glass {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(99, 102, 241, 0.2);
        }
        
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #8b5cf6, #ec4899);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
        
        .search-container {
            position: relative;
        }
        
        .search-input {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(99, 102, 241, 0.3);
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }
        
        .cart-badge, .wishlist-badge {
            animation: pulse 1s ease-in-out infinite;
        }
        
        .mobile-menu {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(12px);
            transform: translateY(-100%);
            transition: transform 0.3s ease;
        }
        
        .mobile-menu.active {
            transform: translateY(0);
        }
        
        .dropdown {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(99, 102, 241, 0.2);
        }
        
        .logo-text {
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
    </style>
    
    @yield('styles')
</head>
<body class="bg-slate-900 text-white min-h-screen">
    <!-- Navbar -->
    <nav class="navbar-glass fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-8">
                    <a href="{{ url('dashboard') }}" class="flex items-center space-x-2 group">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center transform group-hover:scale-105 transition-transform">
                            <i class="fas fa-shoe-prints text-white text-lg"></i>
                        </div>
                        <span class="logo-text text-xl font-bold">SoleStyle</span>
                    </a>
                    
                    <!-- Desktop Navigation -->
                    <div class="hidden lg:flex items-center space-x-1">
                        <a href="{{ url('dashboard') }}" class="nav-link px-4 py-2 rounded-lg hover:bg-slate-700/50 {{ request()->is('dashboard*') ? 'active' : '' }}">
                            <i class="fas fa-home mr-2"></i>Beranda
                        </a>
                        <a href="{{ url('produk') }}" class="nav-link px-4 py-2 rounded-lg hover:bg-slate-700/50 {{ request()->is('produk*') ? 'active' : '' }}">
                            <i class="fas fa-th-large mr-2"></i>Produk
                        </a>
                        
                        <a href="{{ url('kategori') }}" class="nav-link px-4 py-2 rounded-lg hover:bg-slate-700/50 {{ request()->is('kategori*') ? 'active' : '' }}">
                            <i class="fas fa-th-large mr-2"></i>Kategori
                        </a>
                        <a href="{{ url('contact') }}" class="nav-link px-4 py-2 rounded-lg hover:bg-slate-700/50">
                            <i class="fas fa-envelope mr-2"></i>Kontak
                        </a>
                    </div>
                </div>

                <!-- Right Section -->
                <div class="flex items-center space-x-4">
                    
                    <!-- Wishlist -->
                    <a href="{{ url('wishlist') }}" class="relative p-2 rounded-lg hover:bg-slate-700/50 transition-colors group">
                        <i class="fas fa-heart text-lg group-hover:text-red-400 transition-colors"></i>
                        <span class="wishlist-badge absolute -top-1 -right-1 bg-gradient-to-r from-purple-600 to-pink-600 text-xs rounded-full w-5 h-5 flex items-center justify-center font-medium" id="wishlist-count">0</span>
                    </a>
                    
                    <!-- Cart dengan Cart Count yang Berfungsi -->
                    <a href="{{ url('cart') }}" class="relative p-2 rounded-lg hover:bg-slate-700/50 transition-colors group">
                        <i class="fas fa-shopping-cart text-lg group-hover:text-purple-400 transition-colors"></i>
                        <span class="cart-badge absolute -top-1 -right-1 bg-gradient-to-r from-pink-600 to-purple-600 text-xs rounded-full w-5 h-5 flex items-center justify-center font-medium" id="cart-count">
                            @php
                                use App\Models\Cart;
                                $cartCount = 0;
                                if (auth()->check()) {
                                    $cartCount = Cart::where('user_id', auth()->id())->sum('quantity');
                                } else {
                                    $cartCount = Cart::where('session_id', session()->getId())->sum('quantity');
                                }
                            @endphp
                            {{ $cartCount }}
                        </span>
                    </a>
                    
                    <!-- User Menu -->
                    @auth
                    <div class="relative group">
                        <button class="flex items-center space-x-2 p-2 rounded-lg hover:bg-slate-700/50 transition-colors">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                            <span class="hidden sm:block">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        
                        <div class="dropdown absolute right-0 top-full mt-2 w-48 rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <div class="p-2">
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-700/50 transition-colors">
                                    <i class="fas fa-user-circle mr-2 text-purple-400"></i>Profil
                                </a>
                                <a href="{{ url('orders') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-700/50 transition-colors">
                                    <i class="fas fa-box mr-2 text-purple-400"></i>Pesanan
                                </a>
                                <hr class="my-2 border-slate-700">
                                <form method="POST" action="{{ url('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 rounded-lg hover:bg-slate-700/50 transition-colors text-red-400">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center space-x-2">
                        <a href="{{ url('login') }}" class="px-4 py-2 rounded-lg border border-purple-500/50 hover:bg-purple-500/10 transition-colors">
                            Masuk
                        </a>
                        <a href="{{ url('register') }}" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all font-medium">
                            Daftar
                        </a>
                    </div>
                    @endauth
                    
                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu lg:hidden absolute top-full left-0 right-0">
            <div class="px-4 py-6 space-y-4">
                <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-lg hover:bg-slate-700/50 transition-colors {{ request()->is('/') ? 'bg-slate-700/30' : '' }}">
                    <i class="fas fa-home mr-3 text-purple-400"></i>Beranda
                </a>
                <a href="{{ url('produk') }}" class="block px-4 py-3 rounded-lg hover:bg-slate-700/50 transition-colors {{ request()->is('produk*') ? 'bg-slate-700/30' : '' }}">
                    <i class="fas fa-th-large mr-3 text-purple-400"></i>Produk
                </a>
                
                <!-- Mobile Categories -->
                <div class="space-y-2">
                    <div class="px-4 py-2 text-sm text-slate-400 uppercase tracking-wide">Kategori</div>
                    <a href="{{ url('produk?category[]=sneakers') }}" class="block px-6 py-2 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <i class="fas fa-running mr-2 text-purple-400"></i>Sneakers
                    </a>
                    <a href="{{ url('produk?category[]=formal') }}" class="block px-6 py-2 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <i class="fas fa-user-tie mr-2 text-purple-400"></i>Formal
                    </a>
                    <a href="{{ url('produk?category[]=casual') }}" class="block px-6 py-2 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <i class="fas fa-walking mr-2 text-purple-400"></i>Casual
                    </a>
                    <a href="{{ url('produk?category[]=sport') }}" class="block px-6 py-2 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <i class="fas fa-dumbbell mr-2 text-purple-400"></i>Sport
                    </a>
                </div>
                
                <a href="{{ url('about') }}" class="block px-4 py-3 rounded-lg hover:bg-slate-700/50 transition-colors">
                    <i class="fas fa-info-circle mr-3 text-purple-400"></i>Tentang
                </a>
                <a href="{{ url('contact') }}" class="block px-4 py-3 rounded-lg hover:bg-slate-700/50 transition-colors">
                    <i class="fas fa-envelope mr-3 text-purple-400"></i>Kontak
                </a>
                
                <!-- Mobile User Actions -->
                @auth
                <div class="border-t border-slate-700 pt-4 mt-4 space-y-2">
                    <div class="px-4 py-2 flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <span class="font-medium">{{ Auth::user()->name }}</span>
                    </div>
                    <a href="{{ url('profile') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <i class="fas fa-user-circle mr-3 text-purple-400"></i>Profil
                    </a>
                    <a href="{{ url('orders') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <i class="fas fa-box mr-3 text-purple-400"></i>Pesanan
                    </a>
                    <form method="POST" action="{{ url('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 rounded-lg hover:bg-slate-700/50 transition-colors text-red-400">
                            <i class="fas fa-sign-out-alt mr-3"></i>Keluar
                        </button>
                    </form>
                </div>
                @else
                <div class="border-t border-slate-700 pt-4 mt-4 space-y-2">
                    <a href="{{ url('login') }}" class="block px-4 py-3 text-center rounded-lg border border-purple-500/50 hover:bg-purple-500/10 transition-colors">
                        Masuk
                    </a>
                    <a href="{{ url('register') }}" class="block px-4 py-3 text-center bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all font-medium">
                        Daftar
                    </a>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-800/50 backdrop-blur-sm border-t border-slate-700 mt-16">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shoe-prints text-white text-sm"></i>
                        </div>
                        <span class="logo-text text-lg font-bold">SoleStyle</span>
                    </div>
                    <p class="text-slate-400 text-sm">Toko sepatu online terpercaya dengan koleksi terlengkap dan berkualitas tinggi.</p>
                    <div class="flex space-x-3">
                        <a href="#" class="w-8 h-8 bg-slate-700 rounded-lg flex items-center justify-center hover:bg-purple-600 transition-colors">
                            <i class="fab fa-facebook-f text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 bg-slate-700 rounded-lg flex items-center justify-center hover:bg-purple-600 transition-colors">
                            <i class="fab fa-instagram text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 bg-slate-700 rounded-lg flex items-center justify-center hover:bg-purple-600 transition-colors">
                            <i class="fab fa-twitter text-sm"></i>
                        </a>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <h4 class="font-semibold">Produk</h4>
                    <div class="space-y-2 text-sm text-slate-400">
                        <a href="{{ url('produk?category[]=sneakers') }}" class="block hover:text-purple-400 transition-colors">Sneakers</a>
                        <a href="{{ url('produk?category[]=formal') }}" class="block hover:text-purple-400 transition-colors">Formal</a>
                        <a href="{{ url('produk?category[]=casual') }}" class="block hover:text-purple-400 transition-colors">Casual</a>
                        <a href="{{ url('produk?category[]=sport') }}" class="block hover:text-purple-400 transition-colors">Sport</a>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <h4 class="font-semibold">Bantuan</h4>
                    <div class="space-y-2 text-sm text-slate-400">
                        <a href="{{ url('faq') }}" class="block hover:text-purple-400 transition-colors">FAQ</a>
                        <a href="{{ url('shipping') }}" class="block hover:text-purple-400 transition-colors">Pengiriman</a>
                        <a href="{{ url('returns') }}" class="block hover:text-purple-400 transition-colors">Pengembalian</a>
                        <a href="{{ url('contact') }}" class="block hover:text-purple-400 transition-colors">Hubungi Kami</a>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <h4 class="font-semibold">Hubungi Kami</h4>
                    <div class="space-y-2 text-sm text-slate-400">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-phone text-purple-400"></i>
                            <span>+62 812 3456 7890</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-envelope text-purple-400"></i>
                            <span>hello@solestyle.com</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-map-marker-alt text-purple-400"></i>
                            <span>Jakarta, Indonesia</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-slate-700 mt-8 pt-8 text-center text-sm text-slate-400">
                <p>&copy; 2025 SoleStyle. Semua hak cipta dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            const icon = this.querySelector('i');
            
            mobileMenu.classList.toggle('active');
            
            if (mobileMenu.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            const mobileMenu = document.getElementById('mobile-menu');
            const menuBtn = document.getElementById('mobile-menu-btn');
            
            if (!mobileMenu.contains(e.target) && !menuBtn.contains(e.target)) {
                mobileMenu.classList.remove('active');
                menuBtn.querySelector('i').classList.remove('fa-times');
                menuBtn.querySelector('i').classList.add('fa-bars');
            }
        });

        // Load cart count saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCountFromServer();
        });

        function updateCartCountFromServer() {
            fetch('/cart/count')
                .then(response => response.json())
                .then(data => {
                    updateCartCount(data.count);
                })
                .catch(error => console.error('Error loading cart count:', error));
        }

        // Function untuk update cart count (dipanggil dari halaman lain)
        function updateCartCount(count) {
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = count || 0;
                
                // Animasi bounce saat count berubah
                if (count > 0) {
                    cartCountElement.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        cartCountElement.style.transform = 'scale(1)';
                    }, 200);
                }
            }
        }

        // Smooth scroll for anchor links
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

        // Auto-hide navbar on scroll
        let lastScrollTop = 0;
        const navbar = document.querySelector('nav');
        
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > lastScrollTop && scrollTop > 100) {
                // Scrolling down
                navbar.style.transform = 'translateY(-100%)';
            } else {
                // Scrolling up
                navbar.style.transform = 'translateY(0)';
            }
            
            lastScrollTop = scrollTop;
        });
    </script>

    @yield('scripts')
</body>
</html>