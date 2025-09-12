@extends('user.layouts.app')
@section('title', 'Kategori Produk - SoleStyle')
@section('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
* {
    font-family: 'Inter', sans-serif;
}

/* Animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
}
@keyframes gradient-shift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}
@keyframes pulse-glow {
    from { box-shadow: 0 0 20px rgba(139, 92, 246, 0.5); }
    to { box-shadow: 0 0 30px rgba(236, 72, 153, 0.8); }
}

/* Base styles */
.gradient-text {
    background: linear-gradient(45deg, #8b5cf6, #ec4899, #06b6d4);
    background-size: 200% 200%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: gradient-shift 4s ease infinite;
}

.glass-effect {
    backdrop-filter: blur(20px);
    background: rgba(15, 23, 42, 0.7);
    border: 1px solid rgba(148, 163, 184, 0.1);
}

/* Category Cards */
.category-card {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    min-height: 200px;
}

.category-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(139, 92, 246, 0.3);
}

.category-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, transparent 60%, rgba(0,0,0,0.8));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.category-card:hover::before {
    opacity: 1;
}

/* Gradient Background for Cards without Images */
.category-gradient-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, 
        #667eea 0%, 
        #764ba2 25%, 
        #f093fb 50%, 
        #f5576c 75%, 
        #4facfe 100%);
    background-size: 400% 400%;
    animation: gradient-shift 8s ease infinite;
}

.category-gradient-bg.variant-1 {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.category-gradient-bg.variant-2 {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.category-gradient-bg.variant-3 {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.category-gradient-bg.variant-4 {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.category-gradient-bg.variant-5 {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.category-gradient-bg.variant-6 {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}

/* Category Icon for Cards without Images */
.category-icon-container {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 2;
    opacity: 0.3;
}

.category-icon {
    font-size: 4rem;
    color: white;
    text-shadow: 0 4px 8px rgba(0,0,0,0.3);
}

.category-image {
    height: 240px;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.category-card:hover .category-image {
    transform: scale(1.1);
}

.category-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 1.5rem;
    background: linear-gradient(to top, rgba(0,0,0,0.9), rgba(0,0,0,0.6), transparent);
    z-index: 3;
    transform: translateY(20px);
    transition: transform 0.3s ease;
}

.category-card:hover .category-overlay {
    transform: translateY(0);
}

.category-content {
    position: relative;
    height: 100%;
    min-height: 200px;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
}

.category-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 2rem;
    font-size: 0.875rem;
    font-weight: 600;
    box-shadow: 0 4px 10px rgba(139, 92, 246, 0.3);
    z-index: 10;
}

/* Hero Section */
.hero-section {
    position: relative;
    min-height: 70vh;
    display: flex;
    align-items: center;
    overflow: hidden;
}

.hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 25%, #334155 50%, #1e293b 75%, #0f172a 100%);
    z-index: -2;
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

.hero-content {
    position: relative;
    z-index: 1;
}

/* Floating Elements */
.floating-element {
    position: absolute;
    border-radius: 50%;
    filter: blur(40px);
    opacity: 0.7;
    animation: float 6s ease-in-out infinite;
}

.floating-element:nth-child(1) {
    width: 200px;
    height: 200px;
    background: rgba(139, 92, 246, 0.3);
    top: 10%;
    left: 5%;
    animation-delay: 0s;
}

.floating-element:nth-child(2) {
    width: 300px;
    height: 300px;
    background: rgba(236, 72, 153, 0.2);
    bottom: 10%;
    right: 5%;
    animation-delay: 3s;
}

/* Stats Cards */
.stats-card {
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(148, 163, 184, 0.1);
    border-radius: 1rem;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(139, 92, 246, 0.2);
    border-color: rgba(139, 92, 246, 0.3);
}

/* Search and Filter */
.search-container {
    position: relative;
    max-width: 600px;
    margin: 0 auto;
}

.search-input {
    width: 100%;
    padding: 1rem 3rem 1rem 1.5rem;
    background: rgba(30, 41, 59, 0.6);
    border: 1px solid rgba(148, 163, 184, 0.1);
    border-radius: 2rem;
    color: white;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: #8b5cf6;
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
}

.search-icon {
    position: absolute;
    right: 1.5rem;
    top: 50%;
    transform: translateY(-50%);
    color: #8b5cf6;
}

/* Section Styles */
.section-title {
    position: relative;
    display: inline-block;
    margin-bottom: 3rem;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -0.5rem;
    left: 0;
    width: 60px;
    height: 4px;
    background: linear-gradient(90deg, #8b5cf6, #ec4899);
    border-radius: 2px;
}

/* Filter Pills */
.filter-pills {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 2rem;
}

.filter-pill {
    padding: 0.5rem 1rem;
    background: rgba(30, 41, 59, 0.6);
    border: 1px solid rgba(148, 163, 184, 0.1);
    border-radius: 2rem;
    color: #cbd5e1;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.filter-pill:hover {
    background: rgba(139, 92, 246, 0.2);
    border-color: #8b5cf6;
    color: white;
}

.filter-pill.active {
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    border-color: transparent;
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-section {
        min-height: 50vh;
    }
    
    .category-grid {
        grid-template-columns: 1fr !important;
    }
    
    .stats-grid {
        grid-template-columns: 1fr !important;
    }
    
    .category-icon {
        font-size: 3rem;
    }
}
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-bg"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    
    <div class="container mx-auto px-4 relative z-10 mt-10">
        <div class="hero-content text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                <span class="gradient-text">Kategori</span><br>
                <span class="text-white">Produk</span>
            </h1>
            <p class="text-xl text-slate-300 mb-8 max-w-2xl mx-auto">
                Jelajahi koleksi sepatu kami yang terorganisir berdasarkan kategori. Temukan sepatu sempurna untuk setiap gaya dan aktivitas Anda.
            </p>
            
            <!-- Search Bar -->
            <div class="search-container mb-8">
                <input type="text" class="search-input" placeholder="Cari kategori" id="searchInput">
                <i class="fas fa-search search-icon"></i>
            </div>
            
            <!-- Quick Stats -->
            <div class="flex flex-wrap justify-center gap-6 mb-12">
                <div class="stats-card">
                    <div class="text-3xl font-bold gradient-text mb-1">{{ $totalCategories }}</div>
                    <div class="text-slate-400">Kategori</div>
                </div>
                <div class="stats-card">
                    <div class="text-3xl font-bold gradient-text mb-1">{{ $totalProducts }}</div>
                    <div class="text-slate-400">Produk</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <div class="w-6 h-10 border-2 border-purple-400 rounded-full flex justify-center">
            <div class="w-1 h-3 bg-purple-400 rounded-full mt-2 animate-pulse"></div>
        </div>
    </div>
</section>

<!-- Featured Categories Section -->
<section class="py-20 relative overflow-hidden">
    <div class="container mx-auto px-4 relative z-10">
        <h2 class="section-title text-3xl md:text-4xl font-bold text-white text-center mb-12">Kategori Unggulan</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            @foreach($featuredCategories as $index => $category)
            <a href="{{ route('kategori.show', $category->id) }}" class="category-card glass-effect rounded-2xl overflow-hidden group">
                <div class="category-content">
                    @if($category->image_url)
                        <img src="{{ $category->image_url }}" 
                             alt="{{ $category->name }}" 
                             class="category-image w-full absolute inset-0 object-cover">
                    @else
                        <!-- Gradient Background with Icon for Categories without Images -->
                        <div class="category-gradient-bg variant-{{ ($index % 6) + 1 }}"></div>
                        <div class="category-icon-container">
                            @php
                                $icons = ['fas fa-running', 'fas fa-crown', 'fas fa-walking', 'fas fa-mountain', 'fas fa-star', 'fas fa-gem'];
                                $icon = $icons[$index % count($icons)];
                            @endphp
                            <i class="{{ $icon }} category-icon"></i>
                        </div>
                    @endif
                    
                    <div class="category-badge">
                        {{ $category->products_count }} Produk
                    </div>
                    
                    <div class="category-overlay">
                        <h3 class="text-2xl font-bold text-white mb-2">{{ $category->name }}</h3>
                        <p class="text-slate-300 text-sm">{{ Str::limit($category->description ?? 'Temukan koleksi sepatu terbaik dalam kategori ini', 100) }}</p>
                        <button class="mt-4 bg-white text-slate-900 px-4 py-2 rounded-lg font-medium text-sm hover:bg-slate-100 transition-colors">
                            Jelajahi <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- All Categories Grid -->
<section class="py-20 relative overflow-hidden">
    <div class="container mx-auto px-4 relative z-10">
        <h2 class="section-title text-3xl md:text-4xl font-bold text-white text-center mb-12">Semua Kategori</h2>
        
        <div class="category-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="categoryGrid">
            @foreach($categories as $index => $category)
            <a href="{{ route('kategori.show', $category->id) }}" class="category-card glass-effect rounded-xl overflow-hidden group">
                <div class="category-content">
                    @if($category->image_url)
                        <img src="{{ $category->image_url }}" 
                             alt="{{ $category->name }}" 
                             class="w-full h-48 absolute inset-0 object-cover">
                    @else
                        <!-- Gradient Background with Icon for Categories without Images -->
                        <div class="category-gradient-bg variant-{{ ($index % 6) + 1 }}"></div>
                        <div class="category-icon-container">
                            @php
                                $icons = ['fas fa-running', 'fas fa-crown', 'fas fa-walking', 'fas fa-mountain', 'fas fa-star', 'fas fa-gem'];
                                $icon = $icons[$index % count($icons)];
                            @endphp
                            <i class="{{ $icon }} category-icon"></i>
                        </div>
                    @endif
                    
                    <div class="absolute top-3 right-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white px-3 py-1 rounded-full text-xs font-semibold z-10">
                        {{ $category->products_count }} Produk
                    </div>
                    
                    <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/90 via-black/50 to-transparent z-10">
                        <h3 class="text-xl font-bold text-white">{{ $category->name }}</h3>
                        <div class="flex items-center mt-2 text-slate-300 text-sm">
                            <i class="fas fa-shopping-bag mr-2"></i>
                            <span>{{ $category->products_count }} produk tersedia</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Quick Actions Section -->
<section class="py-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-purple-900/30 via-slate-900 to-pink-900/30"></div>
    <div class="absolute top-10 left-10 w-40 h-40 bg-purple-500/20 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-10 right-10 w-56 h-56 bg-pink-500/20 rounded-full blur-3xl animate-float-delayed"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-8">
                Temukan <span class="gradient-text">Sepatu Impian</span> Anda
            </h2>
            <p class="text-slate-300 text-xl mb-12">
                Dengan berbagai kategori yang kami sediakan, Anda akan menemukan sepatu yang sempurna untuk setiap kebutuhan
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="glass-effect rounded-xl p-6 border border-white/10 transform hover:scale-105 transition-transform">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-running text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Olahraga</h3>
                    <p class="text-slate-400">Sepatu untuk berbagai aktivitas olahraga dengan teknologi terkini</p>
                </div>
                
                <div class="glass-effect rounded-xl p-6 border border-white/10 transform hover:scale-105 transition-transform">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-briefcase text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Formal</h3>
                    <p class="text-slate-400">Sepatu formal elegan untuk acara penting dan kantor</p>
                </div>
                
                <div class="glass-effect rounded-xl p-6 border border-white/10 transform hover:scale-105 transition-transform">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-teal-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-walking text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Casual</h3>
                    <p class="text-slate-400">Sepatu kasual nyaman untuk gaya hidup sehari-hari</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const categoryCards = document.querySelectorAll('.category-card');
    
    categoryCards.forEach(card => {
        const categoryName = card.querySelector('h3').textContent.toLowerCase();
        const categoryDesc = card.querySelector('p') ? card.querySelector('p').textContent.toLowerCase() : '';
        
        if (categoryName.includes(searchTerm) || categoryDesc.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Filter functionality
const filterPills = document.querySelectorAll('.filter-pill');
filterPills.forEach(pill => {
    pill.addEventListener('click', function() {
        // Update active state
        filterPills.forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        const categoryCards = document.querySelectorAll('.category-card');
        
        categoryCards.forEach(card => {
            if (filter === 'all') {
                card.style.display = 'block';
            } else {
                // Simulate filtering based on data attributes
                const categoryType = card.dataset.type || 'all';
                card.style.display = categoryType === filter ? 'block' : 'none';
            }
        });
    });
});

// Add smooth scroll behavior
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

// Add parallax effect to hero section
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const parallax = document.querySelector('.hero-bg');
    if (parallax) {
        parallax.style.transform = `translateY(${scrolled * 0.5}px)`;
    }
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

// Observe category cards
document.addEventListener('DOMContentLoaded', () => {
    const categoryCards = document.querySelectorAll('.category-card');
    categoryCards.forEach(card => observer.observe(card));
});
</script>
@endsection