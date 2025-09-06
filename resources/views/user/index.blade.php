@extends('user.layouts.app')

@section('title', 'SoleStyle - Toko Sepatu Premium')

@section('content')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .fade-in-up { animation: fadeInUp 1s ease-out; }
    
    .text-gradient {
        background: linear-gradient(135deg, #9333ea, #ec4899);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .glass-effect {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .product-card {
        transition: all 0.3s ease;
    }
    
    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(147, 51, 234, 0.2);
    }
    
    .category-card {
        transition: all 0.3s ease;
    }
    
    .category-card:hover {
        background: linear-gradient(135deg, rgba(147, 51, 234, 0.2), rgba(236, 72, 153, 0.2));
        transform: scale(1.05);
    }
</style>

<!-- Hero Section -->
<section id="home" class="relative min-h-screen flex items-center">
    <div class="absolute inset-0 -z-20">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900/95 via-slate-800/85 to-slate-900/95 z-10"></div>
        <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
             alt="Premium Sneakers" class="w-full h-full object-cover">
    </div>

    <div class="max-w-7xl mx-auto px-6 py-24 grid lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-8 fade-in-up">
            <span class="inline-flex items-center gap-2 px-4 py-2 glass-effect rounded-full text-purple-400 font-semibold text-sm">
                <i class="fas fa-star"></i>
                KOLEKSI PREMIUM 2025
            </span>
            
            <h1 class="text-5xl md:text-6xl font-bold leading-tight">
                Gaya Hidup<br>
                <span class="text-gradient">Modern</span><br>
                Dimulai dari <span class="underline decoration-purple-500">Kaki</span>
            </h1>
            
            <p class="text-slate-300 text-xl leading-relaxed max-w-lg">
                Jelajahi koleksi sepatu eksklusif dari brand terkemuka dunia. Kualitas premium, desain terdepan.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <a href="{{ url('produk') }}" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 px-8 py-4 rounded-xl font-bold text-lg transition-all transform hover:scale-105 text-center">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Belanja Sekarang
                </a>
                <a href="{{ url('kategori') }}" class="px-8 py-4 border-2 border-slate-600 hover:border-purple-500 rounded-xl font-semibold text-lg transition-all hover:bg-purple-500/10 text-center">
                    <i class="fas fa-play mr-2"></i>
                    Lihat Koleksi
                </a>
            </div>
        </div>
        
        <div class="space-y-8 fade-in-up">
            <div class="glass-effect rounded-2xl p-6">
                <h3 class="text-xl font-bold mb-4">Produk Unggulan</h3>
                <div class="bg-slate-800/50 rounded-xl p-4 flex items-center gap-4">
                    <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=80&q=80" 
                         alt="Featured Product" class="w-16 h-16 object-cover rounded-lg">
                    <div>
                        <h4 class="font-semibold">Nike Air Max Pro</h4>
                        <p class="text-purple-400 font-bold">Rp 1.200.000</p>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-3 gap-4">
                <div class="glass-effect rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-gradient">50+</div>
                    <div class="text-slate-400 text-sm">Brand</div>
                </div>
                <div class="glass-effect rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-gradient">500+</div>
                    <div class="text-slate-400 text-sm">Produk</div>
                </div>
                <div class="glass-effect rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-gradient">98%</div>
                    <div class="text-slate-400 text-sm">Puas</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section id="categories" class="max-w-7xl mx-auto px-6 py-20">
    <div class="text-center mb-16">
        <h2 class="text-4xl font-bold mb-4 text-gradient">Kategori Pilihan</h2>
        <p class="text-slate-400 text-xl">Temukan sepatu sesuai gaya Anda</p>
    </div>
    
    <div class="grid md:grid-cols-4 gap-6">
        <div class="category-card glass-effect rounded-2xl p-6 text-center cursor-pointer">
            <div class="text-4xl mb-4 text-purple-400">
                <i class="fas fa-running"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Sneakers</h3>
            <p class="text-slate-400 text-sm">Gaya kasual dan modern</p>
            <p class="text-purple-400 font-semibold mt-2">120+ produk</p>
        </div>
        
        <div class="category-card glass-effect rounded-2xl p-6 text-center cursor-pointer">
            <div class="text-4xl mb-4 text-pink-400">
                <i class="fas fa-dumbbell"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Olahraga</h3>
            <p class="text-slate-400 text-sm">Performance maksimal</p>
            <p class="text-purple-400 font-semibold mt-2">85+ produk</p>
        </div>
        
        <div class="category-card glass-effect rounded-2xl p-6 text-center cursor-pointer">
            <div class="text-4xl mb-4 text-blue-400">
                <i class="fas fa-hiking"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Boots</h3>
            <p class="text-slate-400 text-sm">Tahan lama dan stylish</p>
            <p class="text-purple-400 font-semibold mt-2">60+ produk</p>
        </div>
        
        <div class="category-card glass-effect rounded-2xl p-6 text-center cursor-pointer">
            <div class="text-4xl mb-4 text-green-400">
                <i class="fas fa-user-tie"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Formal</h3>
            <p class="text-slate-400 text-sm">Elegan dan profesional</p>
            <p class="text-purple-400 font-semibold mt-2">45+ produk</p>
        </div>
    </div>
</section>

<!-- Products Section -->
<section id="products" class="max-w-7xl mx-auto px-6 py-20">
    <div class="text-center mb-16">
        <h2 class="text-4xl font-bold mb-4 text-gradient">Produk Unggulan</h2>
        <p class="text-slate-400 text-xl">Koleksi terbaik dengan kualitas premium</p>
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
        <div class="product-card glass-effect rounded-2xl overflow-hidden" data-category="nike">
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=400&q=80" 
                     alt="Nike Air Max Pro" class="w-full h-64 object-cover">
                <div class="absolute top-4 left-4">
                    <span class="bg-purple-600 text-white px-3 py-1 rounded-full text-sm">Trending</span>
                </div>
                <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onclick="addToWishlist(this)" class="p-2 bg-white/20 backdrop-blur-sm rounded-full hover:bg-white/30 transition">
                        <i class="fas fa-heart text-white"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-xl font-bold">Nike Air Max Pro</h3>
                    <div class="text-yellow-400">
                        <i class="fas fa-star"></i>
                        <span class="text-sm text-slate-400">4.8</span>
                    </div>
                </div>
                <p class="text-slate-400 mb-4">Sneaker premium untuk gaya santai maupun olahraga dengan teknologi Air Max terdepan.</p>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <span class="text-purple-400 font-bold text-xl">Rp 2.000.000</span>
                        <span class="text-slate-500 line-through text-sm ml-2">Rp 2.200.000</span>
                    </div>
                    <span class="text-green-400 text-sm">9% OFF</span>
                </div>
                <div class="flex gap-2 mb-4">
                    <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">38</button>
                    <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">39</button>
                    <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">40</button>
                    <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">41</button>
                </div>
                <button onclick="addToCart(this)" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 py-3 rounded-xl font-semibold transition-all">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    Tambah ke Keranjang
                </button>
            </div>
        </div>

        <!-- Product Card 2 -->
        <div class="product-card glass-effect rounded-2xl overflow-hidden" data-category="adidas">
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?auto=format&fit=crop&w=400&q=80" 
                     alt="Adidas Ultraboost" class="w-full h-64 object-cover">
                <div class="absolute top-4 left-4">
                    <span class="bg-green-600 text-white px-3 py-1 rounded-full text-sm">New</span>
                </div>
                <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onclick="addToWishlist(this)" class="p-2 bg-white/20 backdrop-blur-sm rounded-full hover:bg-white/30 transition">
                        <i class="fas fa-heart text-white"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-xl font-bold">Adidas Ultraboost</h3>
                    <div class="text-yellow-400">
                        <i class="fas fa-star"></i>
                        <span class="text-sm text-slate-400">4.9</span>
                    </div>
                </div>
                <p class="text-slate-400 mb-4">Performa maksimal dengan teknologi Boost untuk kenyamanan sepanjang hari.</p>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-purple-400 font-bold text-xl">Rp 1.800.000</span>
                </div>
                <div class="flex gap-2 mb-4">
                    <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">38</button>
                    <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">39</button>
                    <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">40</button>
                    <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">41</button>
                </div>
                <button onclick="addToCart(this)" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 py-3 rounded-xl font-semibold transition-all">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    Tambah ke Keranjang
                </button>
            </div>
        </div>

        <!-- Product Card 3 -->
        <div class="product-card glass-effect rounded-2xl overflow-hidden" data-category="converse">
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1607522370275-f14206abe5d3?auto=format&fit=crop&w=400&q=80" 
                     alt="Converse Chuck Taylor" class="w-full h-64 object-cover">
                <div class="absolute top-4 left-4">
                    <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm">Classic</span>
                </div>
                <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onclick="addToWishlist(this)" class="p-2 bg-white/20 backdrop-blur-sm rounded-full hover:bg-white/30 transition">
                        <i class="fas fa-heart text-white"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-xl font-bold">Converse Chuck Taylor</h3>
                    <div class="text-yellow-400">
                        <i class="fas fa-star"></i>
                        <span class="text-sm text-slate-400">4.7</span>
                    </div>
                </div>
                <p class="text-slate-400 mb-4">Sepatu klasik yang tak lekang oleh waktu dengan desain ikonik.</p>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-purple-400 font-bold text-xl">Rp 850.000</span>
                </div>
                <div class="flex gap-2 mb-4">
                    <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">38</button>
                    <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">39</button>
                    <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">40</button>
                    <button class="size-btn w-8 h-8 border border-slate-600 rounded hover:border-purple-500 hover:bg-purple-500/20 transition text-sm">41</button>
                </div>
                <button onclick="addToCart(this)" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 py-3 rounded-xl font-semibold transition-all">
                    <i class="fas fa-shopping-cart mr-2"></i>
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
        <h2 class="text-3xl font-bold mb-4 text-gradient">Dapatkan Update Terbaru</h2>
        <p class="text-slate-400 text-lg mb-8">Berlangganan newsletter untuk info produk terbaru dan diskon khusus.</p>
        <div class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
            <input type="email" placeholder="Email address" 
                   class="flex-1 bg-slate-800/50 border border-slate-600 rounded-xl px-6 py-4 focus:border-purple-500 focus:outline-none">
            <button class="bg-gradient-to-r from-purple-600 to-pink-600 px-8 py-4 rounded-xl font-semibold transition-all hover:from-purple-700 hover:to-pink-700">
                <i class="fas fa-paper-plane mr-2"></i>
                Subscribe
            </button>
        </div>
        <p class="text-slate-500 text-sm mt-4">*Dapatkan diskon 10% untuk pembelian pertama!</p>
    </div>
</section>

<!-- Testimonial Section -->
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="text-center mb-16">
        <h2 class="text-4xl font-bold mb-4 text-gradient">Apa Kata Mereka</h2>
        <p class="text-slate-400 text-xl">Testimoni dari pelanggan setia SoleStyle</p>
    </div>
    
    <div class="grid md:grid-cols-3 gap-8">
        <div class="glass-effect rounded-2xl p-6">
            <div class="flex items-center mb-4">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=80&q=80" 
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
                <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?auto=format&fit=crop&w=80&q=80" 
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
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=80&q=80" 
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

<!-- Search Overlay -->
<div id="search-overlay" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center">
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

<!-- Notification -->
<div id="notification" class="fixed top-20 right-4 glass-effect text-white p-4 rounded-lg shadow-lg transform translate-x-full transition-transform z-50">
    <div class="flex items-center gap-3">
        <i class="fas fa-check-circle text-green-400"></i>
        <span id="notification-text">Produk berhasil ditambahkan!</span>
        <button onclick="closeNotification()" class="ml-auto text-slate-400 hover:text-white">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<!-- Back to Top -->
<button id="back-to-top" class="fixed bottom-8 right-8 w-12 h-12 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full items-center justify-center transition-all opacity-0 pointer-events-none z-50 hover:from-purple-700 hover:to-pink-700">
    <i class="fas fa-chevron-up text-white"></i>
</button>

@endsection

@push('scripts')
<script>
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

// Add to cart
function addToCart(button) {
    button.innerHTML = '<i class="fas fa-check mr-2"></i>Ditambahkan!';
    button.classList.add('bg-green-600');
    
    setTimeout(() => {
        button.innerHTML = '<i class="fas fa-shopping-cart mr-2"></i>Tambah ke Keranjang';
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

// Notification
function showNotification(message) {
    const notification = document.getElementById('notification');
    const notificationText = document.getElementById('notification-text');
    
    notificationText.textContent = message;
    notification.classList.remove('translate-x-full');
    
    setTimeout(() => {
        notification.classList.add('translate-x-full');
    }, 3000);
}

function closeNotification() {
    document.getElementById('notification').classList.add('translate-x-full');
}

// Back to top
window.addEventListener('scroll', () => {
    const backToTop = document.getElementById('back-to-top');
    if (window.pageYOffset > 300) {
        backToTop.style.opacity = '1';
        backToTop.style.pointerEvents = 'auto';
        backToTop.classList.add('flex');
    } else {
        backToTop.style.opacity = '0';
        backToTop.style.pointerEvents = 'none';
        backToTop.classList.remove('flex');
    }
});

document.getElementById('back-to-top').addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
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

// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
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
@endpush