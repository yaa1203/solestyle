@extends('user.layouts.app')

@section('title', 'Kontak - SoleStyle')

@section('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
* {
    font-family: 'Inter', sans-serif;
}

/* Animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

@keyframes float-delayed {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
}

@keyframes gradient-shift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

@keyframes pulse-glow {
    0%, 100% { 
        box-shadow: 0 0 20px rgba(139, 92, 246, 0.4);
        transform: scale(1);
    }
    50% { 
        box-shadow: 0 0 40px rgba(139, 92, 246, 0.8);
        transform: scale(1.02);
    }
}

@keyframes shine {
    0% { background-position: -200px 0; }
    100% { background-position: 200px 0; }
}

/* Base Styles */
.gradient-text {
    background: linear-gradient(45deg, #8b5cf6, #ec4899, #06b6d4);
    background-size: 200% 200%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: gradient-shift 4s ease infinite;
}

.glass-effect {
    backdrop-filter: blur(20px);
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid rgba(148, 163, 184, 0.2);
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

/* Form Styles */
.form-input {
    background: rgba(30, 41, 59, 0.6);
    border: 2px solid rgba(148, 163, 184, 0.2);
    transition: all 0.3s ease;
    color: white;
}

.form-input:focus {
    border-color: #8b5cf6;
    box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.2);
    background: rgba(30, 41, 59, 0.8);
    outline: none;
}

.form-input::placeholder {
    color: rgba(148, 163, 184, 0.7);
}

/* Contact Cards */
.contact-card {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.contact-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(236, 72, 153, 0.1));
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: -1;
}

.contact-card:hover::before {
    opacity: 1;
}

.contact-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 25px 50px -12px rgba(139, 92, 246, 0.4);
    border-color: rgba(139, 92, 246, 0.4);
}

/* Icon Containers */
.icon-container {
    position: relative;
    overflow: hidden;
}

.icon-container::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transform: rotate(45deg);
    transition: transform 0.6s ease;
}

.contact-card:hover .icon-container::before {
    transform: rotate(45deg) translate(100%, 100%);
}

/* Button Styles */
.btn-primary {
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    background-size: 200% 200%;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.6s ease;
}

.btn-primary:hover::before {
    left: 100%;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 40px rgba(139, 92, 246, 0.4);
    background-position: 100% 0;
}

/* Section Divider */
.section-divider {
    height: 120px;
    background: linear-gradient(to right, transparent, rgba(139, 92, 246, 0.15), transparent);
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.section-divider::after {
    content: '';
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #8b5cf6, #ec4899);
    border-radius: 2px;
    box-shadow: 0 0 20px rgba(139, 92, 246, 0.5);
}

/* Map Container */
.map-container {
    height: 450px;
    border-radius: 1.5rem;
    overflow: hidden;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    border: 2px solid rgba(148, 163, 184, 0.2);
    transition: all 0.3s ease;
}

.map-container:hover {
    transform: translateY(-5px);
    box-shadow: 0 35px 70px -12px rgba(139, 92, 246, 0.3);
}

/* Floating Elements */
.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-float-delayed {
    animation: float-delayed 8s ease-in-out infinite;
}

/* Pulse Effect */
.pulse-effect {
    animation: pulse-glow 3s ease-in-out infinite;
}

/* Feature Cards */
.feature-card {
    background: linear-gradient(145deg, rgba(30, 41, 59, 0.8), rgba(15, 23, 42, 0.9));
    border: 1px solid rgba(148, 163, 184, 0.2);
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
}

.feature-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, #8b5cf6, #ec4899, #06b6d4);
    transform: translateX(-100%);
    transition: transform 0.6s ease;
}

.feature-card:hover::before {
    transform: translateX(0);
}

.feature-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(139, 92, 246, 0.2);
    border-color: rgba(139, 92, 246, 0.3);
}

/* FAQ Section */
.faq-item {
    background: rgba(30, 41, 59, 0.6);
    border: 1px solid rgba(148, 163, 184, 0.2);
    transition: all 0.3s ease;
}

.faq-item:hover {
    background: rgba(30, 41, 59, 0.8);
    border-color: rgba(139, 92, 246, 0.3);
}

/* Social Media Icons */
.social-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.social-icon::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 50%;
    background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.social-icon:hover::before {
    opacity: 1;
}

.social-icon:hover {
    transform: translateY(-3px) scale(1.1);
}

/* Responsive */
@media (max-width: 768px) {
    .map-container {
        height: 300px;
    }
    
    .hero-bg {
        min-height: 80vh;
    }
    
    .contact-card {
        margin-bottom: 2rem;
    }
}

@media (max-width: 640px) {
    .contact-info-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
}
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="hero-bg relative overflow-hidden min-h-screen flex items-center">
  <!-- Enhanced Floating Elements -->
  <div class="absolute top-20 left-10 w-32 h-32 bg-purple-500/20 rounded-full blur-xl animate-float"></div>
  <div class="absolute bottom-20 right-10 w-48 h-48 bg-pink-500/20 rounded-full blur-xl animate-float-delayed"></div>
  <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
  
  <div class="container mx-auto px-4 py-20 relative z-10">
    <div class="text-center max-w-4xl mx-auto">
      <span class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600/80 to-pink-600/80 backdrop-blur-sm text-white px-6 py-3 rounded-full text-sm font-semibold border border-white/20 pulse-effect mb-8">
        <i class="fas fa-headset"></i>
        HUBUNGI KAMI 24/7
      </span>
      
      <h1 class="text-5xl md:text-7xl font-black mb-6 leading-tight">
        <span class="gradient-text">Hubungi</span><br>
        <span class="text-white">Tim Kami</span>
      </h1>
      
      <p class="text-slate-300 text-xl mb-8 leading-relaxed max-w-3xl mx-auto">
        Kami berkomitmen memberikan pelayanan terbaik. Tim customer service profesional siap membantu Anda dengan segala kebutuhan dan pertanyaan.
      </p>
      
      <!-- Quick Stats -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-2xl mx-auto mb-12">
        <div class="glass-effect rounded-xl p-4 text-center border border-white/10">
          <div class="text-2xl font-bold gradient-text">24/7</div>
          <div class="text-slate-400 text-sm">Support</div>
        </div>
        <div class="glass-effect rounded-xl p-4 text-center border border-white/10">
          <div class="text-2xl font-bold gradient-text">&lt; 2h</div>
          <div class="text-slate-400 text-sm">Response</div>
        </div>
        <div class="glass-effect rounded-xl p-4 text-center border border-white/10">
          <div class="text-2xl font-bold gradient-text">99%</div>
          <div class="text-slate-400 text-sm">Satisfied</div>
        </div>
      </div>
      
      <!-- Scroll Indicator -->
      <div class="animate-bounce">
        <i class="fas fa-chevron-down text-purple-400 text-2xl"></i>
      </div>
    </div>
  </div>
</div>

<!-- Section Divider -->
<div class="section-divider"></div>

<!-- Contact Methods Section -->
<div class="bg-slate-900 py-20 relative overflow-hidden">
  <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>
  <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-purple-900/5 via-transparent to-transparent"></div>
  
  <div class="container mx-auto px-4 relative z-10">
    <div class="text-center mb-16">
      <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
        Cara <span class="gradient-text">Menghubungi</span> Kami
      </h2>
      <p class="text-slate-400 text-xl leading-relaxed max-w-2xl mx-auto">
        Pilih cara yang paling nyaman untuk Anda berkomunikasi dengan tim kami
      </p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
      <!-- WhatsApp -->
      <div class="feature-card rounded-2xl p-6 text-center">
        <div class="icon-container w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fab fa-whatsapp text-white text-2xl"></i>
        </div>
        <h3 class="text-lg font-bold text-white mb-2">WhatsApp</h3>
        <p class="text-slate-400 text-sm mb-4">Chat langsung dengan tim kami</p>
        <a href="https://wa.me/6281234567890" class="text-green-400 hover:text-green-300 text-sm font-medium">
          +62 812-3456-7890 <i class="fas fa-external-link-alt ml-1"></i>
        </a>
      </div>
      
      <!-- Live Chat -->
      <div class="feature-card rounded-2xl p-6 text-center">
        <div class="icon-container w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-comments text-white text-2xl"></i>
        </div>
        <h3 class="text-lg font-bold text-white mb-2">Live Chat</h3>
        <p class="text-slate-400 text-sm mb-4">Chat real-time di website</p>
        <button class="text-blue-400 hover:text-blue-300 text-sm font-medium">
          Mulai Chat <i class="fas fa-arrow-right ml-1"></i>
        </button>
      </div>
      
      <!-- Email -->
      <div class="feature-card rounded-2xl p-6 text-center">
        <div class="icon-container w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-envelope text-white text-2xl"></i>
        </div>
        <h3 class="text-lg font-bold text-white mb-2">Email</h3>
        <p class="text-slate-400 text-sm mb-4">Kirim detail pertanyaan Anda</p>
        <a href="mailto:support@solestyle.com" class="text-purple-400 hover:text-purple-300 text-sm font-medium">
          support@solestyle.com <i class="fas fa-external-link-alt ml-1"></i>
        </a>
      </div>
      
      <!-- Phone -->
      <div class="feature-card rounded-2xl p-6 text-center">
        <div class="icon-container w-16 h-16 bg-gradient-to-r from-pink-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-phone text-white text-2xl"></i>
        </div>
        <h3 class="text-lg font-bold text-white mb-2">Telepon</h3>
        <p class="text-slate-400 text-sm mb-4">Bicara langsung dengan agent</p>
        <a href="tel:+622112345678" class="text-pink-400 hover:text-pink-300 text-sm font-medium">
          +62 21 1234-5678 <i class="fas fa-external-link-alt ml-1"></i>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Contact Form Section -->
<div class="bg-slate-900 py-20 relative overflow-hidden">
  <div class="container mx-auto px-4 relative z-10">
    <div class="max-w-4xl mx-auto">
      <div class="text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
          Kirim <span class="gradient-text">Pesan</span>
        </h2>
        <p class="text-slate-400 text-xl leading-relaxed">
          Isi formulir di bawah ini dan kami akan merespons dalam waktu kurang dari 2 jam
        </p>
      </div>
      
      <div class="glass-effect rounded-3xl p-8 md:p-12 border border-white/10">
        <form id="contactForm" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="name" class="block text-slate-300 font-medium mb-3">
                <i class="fas fa-user mr-2 text-purple-400"></i>Nama Lengkap
              </label>
              <input type="text" id="name" name="name" required
                     placeholder="Masukkan nama lengkap Anda"
                     class="form-input w-full px-6 py-4 rounded-xl focus:outline-none">
            </div>
            
            <div>
              <label for="email" class="block text-slate-300 font-medium mb-3">
                <i class="fas fa-envelope mr-2 text-purple-400"></i>Email
              </label>
              <input type="email" id="email" name="email" required
                     placeholder="nama@email.com"
                     class="form-input w-full px-6 py-4 rounded-xl focus:outline-none">
            </div>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="phone" class="block text-slate-300 font-medium mb-3">
                <i class="fas fa-phone mr-2 text-purple-400"></i>Nomor Telepon
              </label>
              <input type="tel" id="phone" name="phone"
                     placeholder="+62 812-3456-7890"
                     class="form-input w-full px-6 py-4 rounded-xl focus:outline-none">
            </div>
            
            <div>
              <label for="category" class="block text-slate-300 font-medium mb-3">
                <i class="fas fa-tag mr-2 text-purple-400"></i>Kategori
              </label>
              <select id="category" name="category" required
                      class="form-input w-full px-6 py-4 rounded-xl focus:outline-none">
                <option value="">Pilih kategori</option>
                <option value="product">Pertanyaan Produk</option>
                <option value="order">Status Pesanan</option>
                <option value="complaint">Keluhan</option>
                <option value="suggestion">Saran</option>
                <option value="other">Lainnya</option>
              </select>
            </div>
          </div>
          
          <div>
            <label for="subject" class="block text-slate-300 font-medium mb-3">
              <i class="fas fa-heading mr-2 text-purple-400"></i>Subjek
            </label>
            <input type="text" id="subject" name="subject" required
                   placeholder="Ringkasan singkat pertanyaan Anda"
                   class="form-input w-full px-6 py-4 rounded-xl focus:outline-none">
          </div>
          
          <div>
            <label for="message" class="block text-slate-300 font-medium mb-3">
              <i class="fas fa-comment mr-2 text-purple-400"></i>Pesan
            </label>
            <textarea id="message" name="message" rows="6" required
                      placeholder="Jelaskan pertanyaan atau kebutuhan Anda secara detail..."
                      class="form-input w-full px-6 py-4 rounded-xl focus:outline-none resize-none"></textarea>
            <div class="text-right text-slate-500 text-sm mt-2">
              <span id="charCount">0</span>/500 karakter
            </div>
          </div>
          
          <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-6">
            <button type="submit" 
                    class="btn-primary text-white px-8 py-4 rounded-xl font-semibold transition-all text-lg shadow-2xl w-full sm:w-auto">
              <i class="fas fa-paper-plane mr-3"></i>Kirim Pesan
            </button>
            <button type="reset" 
                    class="bg-slate-700 hover:bg-slate-600 text-white px-8 py-4 rounded-xl font-semibold transition-all text-lg w-full sm:w-auto">
              <i class="fas fa-undo mr-3"></i>Reset
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Section Divider -->
<div class="section-divider"></div>

<!-- Contact Info Section -->
<div class="bg-slate-900 py-20 relative overflow-hidden">
  <div class="absolute inset-0 bg-gradient-to-br from-blue-900/10 via-transparent to-cyan-900/10"></div>
  
  <div class="container mx-auto px-4 relative z-10">
    <div class="text-center mb-16">
      <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
        Informasi <span class="gradient-text">Kontak</span>
      </h2>
      <p class="text-slate-400 text-xl leading-relaxed">
        Kunjungi toko kami atau hubungi melalui berbagai channel yang tersedia
      </p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto mb-16">
      <!-- Contact Card 1 - Address -->
      <div class="contact-card glass-effect rounded-2xl p-8 border border-white/10 text-center">
        <div class="icon-container w-20 h-20 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-6">
          <i class="fas fa-map-marker-alt text-white text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-white mb-4">Alamat Toko</h3>
        <div class="space-y-2 text-slate-400">
          <p><i class="fas fa-building mr-2 text-purple-400"></i>SoleStyle Store</p>
          <p><i class="fas fa-road mr-2 text-purple-400"></i>Jl. Fashion Boulevard No. 123</p>
          <p><i class="fas fa-city mr-2 text-purple-400"></i>Jakarta Selatan, 12345</p>
          <p><i class="fas fa-flag mr-2 text-purple-400"></i>Indonesia</p>
        </div>
        <div class="mt-6">
          <a href="#" class="inline-flex items-center text-purple-400 hover:text-purple-300 font-medium">
            <i class="fas fa-directions mr-2"></i>Lihat di Maps
          </a>
        </div>
      </div>
      
      <!-- Contact Card 2 - Phone -->
      <div class="contact-card glass-effect rounded-2xl p-8 border border-white/10 text-center">
        <div class="icon-container w-20 h-20 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-full flex items-center justify-center mx-auto mb-6">
          <i class="fas fa-phone-alt text-white text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-white mb-4">Telepon & Fax</h3>
        <div class="space-y-2 text-slate-400">
          <p><i class="fas fa-phone mr-2 text-blue-400"></i>+62 21 1234-5678</p>
          <p><i class="fab fa-whatsapp mr-2 text-green-400"></i>+62 812-3456-7890</p>
          <p><i class="fas fa-fax mr-2 text-blue-400"></i>+62 21 1234-5679</p>
          <p><i class="fas fa-clock mr-2 text-blue-400"></i>Senin - Sabtu (09:00 - 21:00)</p>
        </div>
        <div class="mt-6">
          <a href="tel:+622112345678" class="inline-flex items-center text-blue-400 hover:text-blue-300 font-medium">
            <i class="fas fa-phone mr-2"></i>Hubungi Sekarang
          </a>
        </div>
      </div>
      
      <!-- Contact Card 3 - Email -->
      <div class="contact-card glass-effect rounded-2xl p-8 border border-white/10 text-center">
        <div class="icon-container w-20 h-20 bg-gradient-to-r from-green-600 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-6">
          <i class="fas fa-envelope text-white text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-white mb-4">Email Support</h3>
        <div class="space-y-2 text-slate-400">
          <p><i class="fas fa-inbox mr-2 text-green-400"></i>info@solestyle.com</p>
          <p><i class="fas fa-headset mr-2 text-green-400"></i>support@solestyle.com</p>
          <p><i class="fas fa-star mr-2 text-green-400"></i>custom@solestyle.com</p>
          <p><i class="fas fa-reply mr-2 text-green-400"></i>Response &lt; 2 jam</p>
        </div>
        <div class="mt-6">
          <a href="mailto:support@solestyle.com" class="inline-flex items-center text-green-400 hover:text-green-300 font-medium">
            <i class="fas fa-envelope mr-2"></i>Kirim Email
          </a>
        </div>
      </div>
    </div>
    
    <!-- Social Media -->
    <div class="text-center">
      <h3 class="text-2xl font-bold text-white mb-6">Ikuti Kami</h3>
      <div class="flex justify-center space-x-4">
        <a href="#" class="social-icon bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600">
          <i class="fab fa-facebook-f text-white"></i>
        </a>
        <a href="#" class="social-icon bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-500 hover:to-rose-500">
          <i class="fab fa-instagram text-white"></i>
        </a>
        <a href="#" class="social-icon bg-gradient-to-r from-blue-400 to-blue-500 hover:from-blue-300 hover:to-blue-400">
          <i class="fab fa-twitter text-white"></i>
        </a>
        <a href="#" class="social-icon bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600">
          <i class="fab fa-youtube text-white"></i>
        </a>
        <a href="#" class="social-icon bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-500 hover:to-purple-600">
          <i class="fab fa-tiktok text-white"></i>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Section Divider -->
<div class="section-divider"></div>

<!-- Map Section -->
<div class="bg-slate-900 py-20 relative overflow-hidden">
  <div class="container mx-auto px-4 relative z-10">
    <div class="text-center mb-16">
      <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
        Lokasi <span class="gradient-text">Toko</span>
      </h2>
      <p class="text-slate-400 text-xl leading-relaxed">
        Kunjungi showroom kami untuk pengalaman berbelanja yang tak terlupakan
      </p>
    </div>
    
    <div class="max-w-6xl mx-auto">
      <!-- Store Hours & Info -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <div class="glass-effect rounded-2xl p-6 border border-white/10">
          <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mr-4">
              <i class="fas fa-clock text-white"></i>
            </div>
            <h3 class="text-lg font-bold text-white">Jam Operasional</h3>
          </div>
          <div class="space-y-2 text-slate-400">
            <div class="flex justify-between">
              <span>Senin - Jumat</span>
              <span class="text-green-400">09:00 - 21:00</span>
            </div>
            <div class="flex justify-between">
              <span>Sabtu</span>
              <span class="text-green-400">09:00 - 22:00</span>
            </div>
            <div class="flex justify-between">
              <span>Minggu</span>
              <span class="text-green-400">10:00 - 20:00</span>
            </div>
            <div class="flex justify-between">
              <span>Hari Libur</span>
              <span class="text-yellow-400">10:00 - 18:00</span>
            </div>
          </div>
        </div>
        
        <div class="glass-effect rounded-2xl p-6 border border-white/10">
          <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center mr-4">
              <i class="fas fa-car text-white"></i>
            </div>
            <h3 class="text-lg font-bold text-white">Parkir & Akses</h3>
          </div>
          <div class="space-y-2 text-slate-400">
            <div class="flex items-center">
              <i class="fas fa-check text-green-400 mr-2"></i>
              <span>Parkir gratis 200+ mobil</span>
            </div>
            <div class="flex items-center">
              <i class="fas fa-check text-green-400 mr-2"></i>
              <span>Akses kursi roda</span>
            </div>
            <div class="flex items-center">
              <i class="fas fa-check text-green-400 mr-2"></i>
              <span>Dekat stasiun MRT</span>
            </div>
            <div class="flex items-center">
              <i class="fas fa-check text-green-400 mr-2"></i>
              <span>Area bermain anak</span>
            </div>
          </div>
        </div>
        
        <div class="glass-effect rounded-2xl p-6 border border-white/10">
          <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mr-4">
              <i class="fas fa-gift text-white"></i>
            </div>
            <h3 class="text-lg font-bold text-white">Layanan Khusus</h3>
          </div>
          <div class="space-y-2 text-slate-400">
            <div class="flex items-center">
              <i class="fas fa-star text-yellow-400 mr-2"></i>
              <span>Personal shopper</span>
            </div>
            <div class="flex items-center">
              <i class="fas fa-star text-yellow-400 mr-2"></i>
              <span>Fitting 3D</span>
            </div>
            <div class="flex items-center">
              <i class="fas fa-star text-yellow-400 mr-2"></i>
              <span>Custom design</span>
            </div>
            <div class="flex items-center">
              <i class="fas fa-star text-yellow-400 mr-2"></i>
              <span>Express repair</span>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Map Container -->
      <div class="map-container">
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322283!2d106.8195613!3d-6.2297465!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d%3A0x5371bf0fdad786a2!2sJl.%20H.%20R.%20Rasuna%20Said%2C%20Kuningan%2C%20Setiabudi%2C%20South%20Jakarta%20City%2C%20Jakarta%2C%20Indonesia!5e0!3m2!1sen!2sid!4v1703123456789!5m2!1sen!2sid" 
          height="100%" 
          width="100%"
          style="border:0;" 
          allowfullscreen="" 
          loading="lazy" 
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
      
      <!-- Get Directions Button -->
      <div class="text-center mt-8">
        <a href="https://maps.google.com/?q=SoleStyle+Store+Jakarta" 
           target="_blank"
           class="inline-flex items-center btn-primary text-white px-8 py-4 rounded-xl font-semibold text-lg">
          <i class="fas fa-directions mr-3"></i>
          Dapatkan Arah ke Toko
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Section Divider -->
<div class="section-divider"></div>

<!-- FAQ Section -->
<div class="bg-slate-900 py-20 relative overflow-hidden">
  <div class="container mx-auto px-4 relative z-10">
    <div class="text-center mb-16">
      <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
        <span class="gradient-text">FAQ</span> Umum
      </h2>
      <p class="text-slate-400 text-xl leading-relaxed">
        Pertanyaan yang sering diajukan pelanggan kami
      </p>
    </div>
    
    <div class="max-w-4xl mx-auto">
      <div class="space-y-6">
        <div class="faq-item rounded-2xl p-6 border">
          <div class="flex items-center justify-between cursor-pointer" onclick="toggleFaq(this)">
            <h3 class="text-lg font-semibold text-white">Berapa lama waktu pengiriman?</h3>
            <i class="fas fa-chevron-down text-purple-400 transform transition-transform"></i>
          </div>
          <div class="faq-content hidden mt-4 text-slate-400">
            <p>Untuk area Jakarta dan sekitarnya, pengiriman membutuhkan waktu 1-2 hari kerja. Untuk luar kota, 3-5 hari kerja. Kami juga menyediakan layanan same day delivery untuk area tertentu.</p>
          </div>
        </div>
        
        <div class="faq-item rounded-2xl p-6 border">
          <div class="flex items-center justify-between cursor-pointer" onclick="toggleFaq(this)">
            <h3 class="text-lg font-semibold text-white">Apakah ada garansi untuk produk?</h3>
            <i class="fas fa-chevron-down text-purple-400 transform transition-transform"></i>
          </div>
          <div class="faq-content hidden mt-4 text-slate-400">
            <p>Ya, semua produk kami memiliki garansi kualitas selama 6-12 bulan tergantung jenis produk. Garansi mencakup cacat produksi dan kerusakan material dalam penggunaan normal.</p>
          </div>
        </div>
        
        <div class="faq-item rounded-2xl p-6 border">
          <div class="flex items-center justify-between cursor-pointer" onclick="toggleFaq(this)">
            <h3 class="text-lg font-semibold text-white">Bagaimana cara return/tukar barang?</h3>
            <i class="fas fa-chevron-down text-purple-400 transform transition-transform"></i>
          </div>
          <div class="faq-content hidden mt-4 text-slate-400">
            <p>Anda dapat melakukan return/tukar dalam 7 hari setelah pembelian dengan syarat barang dalam kondisi baru dan memiliki struk pembelian. Proses return dapat dilakukan di toko atau melalui pickup service.</p>
          </div>
        </div>
        
        <div class="faq-item rounded-2xl p-6 border">
          <div class="flex items-center justify-between cursor-pointer" onclick="toggleFaq(this)">
            <h3 class="text-lg font-semibold text-white">Apakah bisa pesan custom design?</h3>
            <i class="fas fa-chevron-down text-purple-400 transform transition-transform"></i>
          </div>
          <div class="faq-content hidden mt-4 text-slate-400">
            <p>Tentu saja! Kami menyediakan layanan custom design dengan berbagai pilihan warna, material, dan desain sesuai keinginan Anda. Waktu produksi custom adalah 2-3 minggu.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const messageTextarea = document.getElementById('message');
    const charCount = document.getElementById('charCount');
    
    // Character counter for message textarea
    if (messageTextarea && charCount) {
        messageTextarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            const maxLength = 500;
            charCount.textContent = currentLength;
            
            if (currentLength > maxLength) {
                charCount.classList.add('text-red-400');
                charCount.classList.remove('text-slate-500');
            } else if (currentLength > maxLength * 0.8) {
                charCount.classList.add('text-yellow-400');
                charCount.classList.remove('text-slate-500', 'text-red-400');
            } else {
                charCount.classList.add('text-slate-500');
                charCount.classList.remove('text-yellow-400', 'text-red-400');
            }
        });
    }
    
    // Form submission
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Mengirim...';
            submitBtn.disabled = true;
            
            // Simulate form submission
            setTimeout(() => {
                const formData = new FormData(contactForm);
                const data = Object.fromEntries(formData);
                
                // Here you would normally send the data to your server
                console.log('Form data:', data);
                
                // Show success notification
                showNotification('Pesan Anda berhasil dikirim! Tim kami akan merespons dalam waktu kurang dari 2 jam.', 'success');
                
                // Reset form
                contactForm.reset();
                charCount.textContent = '0';
                
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });
    }
});

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
    toast.className = `fixed top-6 right-6 bg-gradient-to-r ${colors[type]} text-white px-8 py-4 rounded-2xl shadow-2xl z-50 transform translate-x-full transition-all duration-500 backdrop-blur-sm border border-white/20 max-w-md`;
    toast.innerHTML = `
        <div class="flex items-start space-x-4">
            <i class="fas ${icons[type]} text-xl mt-0.5 flex-shrink-0"></i>
            <div class="flex-1">
                <div class="font-semibold text-sm mb-1">
                    ${type === 'success' ? 'Berhasil!' : type === 'error' ? 'Error!' : type === 'warning' ? 'Peringatan!' : 'Info'}
                </div>
                <div class="text-sm opacity-90">${message}</div>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-white/80 hover:text-white ml-2 flex-shrink-0">
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
    }, 5000);
}

// FAQ Toggle Function
function toggleFaq(element) {
    const faqContent = element.nextElementSibling;
    const icon = element.querySelector('i');
    
    if (faqContent.classList.contains('hidden')) {
        faqContent.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
        
        // Animate content appearance
        faqContent.style.maxHeight = '0';
        faqContent.style.opacity = '0';
        faqContent.offsetHeight; // Force reflow
        faqContent.style.transition = 'all 0.3s ease';
        faqContent.style.maxHeight = faqContent.scrollHeight + 'px';
        faqContent.style.opacity = '1';
    } else {
        faqContent.style.maxHeight = '0';
        faqContent.style.opacity = '0';
        icon.style.transform = 'rotate(0deg)';
        
        setTimeout(() => {
            faqContent.classList.add('hidden');
        }, 300);
    }
}

// Smooth scrolling for anchor links
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

// Form validation enhancements
document.querySelectorAll('.form-input').forEach(input => {
    input.addEventListener('blur', function() {
        if (this.hasAttribute('required') && !this.value.trim()) {
            this.style.borderColor = '#ef4444';
        } else if (this.type === 'email' && this.value && !isValidEmail(this.value)) {
            this.style.borderColor = '#ef4444';
        } else {
            this.style.borderColor = '#8b5cf6';
        }
    });
    
    input.addEventListener('focus', function() {
        this.style.borderColor = '#8b5cf6';
    });
});

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Add loading animation to map
document.addEventListener('DOMContentLoaded', function() {
    const mapContainer = document.querySelector('.map-container');
    if (mapContainer) {
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'absolute inset-0 flex items-center justify-center bg-slate-800 rounded-xl';
        loadingDiv.innerHTML = `
            <div class="text-center">
                <div class="w-12 h-12 border-4 border-purple-500 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-slate-400">Memuat peta...</p>
            </div>
        `;
        
        mapContainer.style.position = 'relative';
        mapContainer.appendChild(loadingDiv);
        
        const iframe = mapContainer.querySelector('iframe');
        if (iframe) {
            iframe.addEventListener('load', function() {
                setTimeout(() => {
                    loadingDiv.remove();
                }, 1000);
            });
        }
    }
});
</script>