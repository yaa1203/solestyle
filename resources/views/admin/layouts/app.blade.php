<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin Dashboard') - SoleStyle</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <style>
    .glass-effect {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .sidebar {
      width: 280px;
      transition: all 0.3s ease;
    }
    
    .sidebar.collapsed {
      width: 80px;
    }
    
    .main-content {
      transition: all 0.3s ease;
      width: calc(100% - 280px);
    }
    
    .main-content.expanded {
      width: calc(100% - 80px);
    }
    
    .text-gradient {
      background: linear-gradient(135deg, #9333ea, #ec4899);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .nav-item.active {
      background: linear-gradient(135deg, rgba(147, 51, 234, 0.2), rgba(236, 72, 153, 0.2));
      border-left: 4px solid #9333ea;
    }
    
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    @yield('styles')
  </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white min-h-screen flex overflow-hidden">



  <!-- Sidebar -->
  <aside class="sidebar glass-effect h-screen fixed left-0 top-0 z-30 overflow-y-auto">
    <div class="p-6 flex items-center justify-between border-b border-slate-700">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl flex items-center justify-center">
          <i class="fas fa-crown text-white"></i>
        </div>
        <h1 class="text-xl font-bold text-gradient sidebar-text">SoleStyle</h1>
      </div>
      <button id="sidebar-toggle" class="text-slate-400 hover:text-white">
        <i class="fas fa-bars"></i>
      </button>
    </div>
    
    <div class="p-4">
      <div class="mb-6 mt-4">
        <div class="text-slate-400 text-xs uppercase font-semibold mb-3 px-4 sidebar-text">Menu Utama</div>
        <ul class="space-y-1">
          <li>
            <a href="/admin/dashboard" class="nav-item @yield('dashboard-active') flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-700/50">
              <i class="fas fa-chart-pie w-5 text-center"></i>
              <span class="sidebar-text">Dashboard</span>
            </a>
          </li>
          <li>
            <a href="products" class="nav-item @yield('products-active') flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-700/50">
              <i class="fas fa-shopping-bag w-5 text-center"></i>
              <span class="sidebar-text">Produk</span>
            </a>
          </li>
          <li>
            <a href="category" class="nav-item @yield('categories-active') flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-700/50">
              <i class="fas fa-tags w-5 text-center"></i>
              <span class="sidebar-text">Kategori</span>
            </a>
          </li>
          <li>
            <a href="/admin/orders" class="nav-item @yield('orders-active') flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-700/50">
              <i class="fas fa-shopping-cart w-5 text-center"></i>
              <span class="sidebar-text">Pesanan</span>
            </a>
          </li>
          <li>
            <a href="/admin/customers" class="nav-item @yield('customers-active') flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-700/50">
              <i class="fas fa-users w-5 text-center"></i>
              <span class="sidebar-text">Pelanggan</span>
            </a>
          </li>
        </ul>
      </div>
      
      <div class="mb-6">
        <div class="text-slate-400 text-xs uppercase font-semibold mb-3 px-4 sidebar-text">Lainnya</div>
        <ul class="space-y-1">
          <li>
            <a href="/admin/analytics" class="nav-item @yield('analytics-active') flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-700/50">
              <i class="fas fa-chart-bar w-5 text-center"></i>
              <span class="sidebar-text">Analitik</span>
            </a>
          </li>
          <li>
            <a href="/admin/settings" class="nav-item @yield('settings-active') flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-700/50">
              <i class="fas fa-cog w-5 text-center"></i>
              <span class="sidebar-text">Pengaturan</span>
            </a>
          </li>
          <li>
            <a href="/admin/help" class="nav-item @yield('help-active') flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-700/50">
              <i class="fas fa-question-circle w-5 text-center"></i>
              <span class="sidebar-text">Bantuan</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="main-content ml-80 h-screen overflow-y-auto">
    <!-- Header -->
    <header class="glass-effect sticky top-0 z-20 p-4 flex items-center justify-between">
      <div class="flex-1 max-w-md">
        <div class="relative">
          <input type="text" placeholder="Cari sesuatu..." class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-2 pl-12 focus:border-purple-500 focus:outline-none">
          <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
        </div>
      </div>
      
      <div class="flex items-center gap-4">
        <button class="relative text-slate-300 hover:text-white transition-all p-2">
          <i class="fas fa-bell text-lg"></i>
          <span class="absolute -top-1 -right-1 bg-pink-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center text-xs">3</span>
        </button>
        
        <button class="relative text-slate-300 hover:text-white transition-all p-2">
          <i class="fas fa-envelope text-lg"></i>
          <span class="absolute -top-1 -right-1 bg-purple-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center text-xs">5</span>
        </button>
        
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center font-semibold text-sm">AS</div>
          <div class="hidden md:block">
            <div class="font-semibold text-sm">Admin</div>
            <div class="text-slate-400 text-xs">Online</div>
          </div>
        </div>
      </div>
    </header>
    
    <!-- Content Area -->
    <div class="p-6">
      @yield('content')
    </div>
  </main>

  <script>
    // Sidebar Toggle
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    const sidebarTexts = document.querySelectorAll('.sidebar-text');

    sidebarToggle.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');
      mainContent.classList.toggle('expanded');
      
      sidebarTexts.forEach(text => {
        text.classList.toggle('hidden');
      });
    });

    console.log('Layout ready!');
    
    @yield('scripts')
  </script>
</body>
</html>