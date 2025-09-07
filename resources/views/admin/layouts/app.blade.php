<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
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
    
    .dropdown {
      position: relative;
    }
    
    .dropdown-menu {
      position: absolute;
      top: 100%;
      right: 0;
      background: rgba(30, 41, 59, 0.95);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(100, 116, 139, 0.3);
      border-radius: 12px;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
      z-index: 50;
      min-width: 200px;
      opacity: 0;
      visibility: hidden;
      transform: translateY(-10px);
      transition: all 0.2s ease;
    }
    
    .dropdown:hover .dropdown-menu,
    .dropdown-menu.show {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
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
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-700/50">
              <i class="fas fa-chart-pie w-5 text-center"></i>
              <span class="sidebar-text">Dashboard</span>
            </a>
          </li>
          <li>
            <a href="{{ url('products') }}" class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-700/50">
              <i class="fas fa-shopping-bag w-5 text-center"></i>
              <span class="sidebar-text">Produk</span>
            </a>
          </li>
          <li>
            <a href="{{ url('category') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-700/50">
              <i class="fas fa-tags w-5 text-center"></i>
              <span class="sidebar-text">Kategori</span>
            </a>
          </li>
          <li>
            <a href="{{ url('pesanan') }}" class="nav-item {{ request()->routeIs('pesanan.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-700/50">
              <i class="fas fa-shopping-cart w-5 text-center"></i>
              <span class="sidebar-text">Pesanan</span>
              @if(isset($pendingOrdersCount) && $pendingOrdersCount > 0)
                <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 ml-auto">{{ $pendingOrdersCount }}</span>
              @endif
            </a>
          </li>
          <li>
            <a href="#" class="nav-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-700/50">
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
            <a href="#" class="nav-item {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-700/50">
              <i class="fas fa-chart-bar w-5 text-center"></i>
              <span class="sidebar-text">Analitik</span>
            </a>
          </li>
          <li>
            <a href="#" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-700/50">
              <i class="fas fa-cog w-5 text-center"></i>
              <span class="sidebar-text">Pengaturan</span>
            </a>
          </li>
          <li>
            <a href="#" class="nav-item {{ request()->routeIs('admin.help.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-700/50">
              <i class="fas fa-question-circle w-5 text-center"></i>
              <span class="sidebar-text">Bantuan</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
    
    <!-- User Info at Bottom -->
    @auth
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-slate-700">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center font-semibold text-sm">
          {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
        <div class="flex-1 sidebar-text">
          <div class="font-semibold text-sm text-white truncate">{{ Auth::user()->name }}</div>
          <div class="text-slate-400 text-xs">{{ Auth::user()->role ?? 'Admin' }}</div>
        </div>
      </div>
    </div>
    @endauth
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
        <!-- Notifications -->
        <div class="dropdown">
          <button class="relative text-slate-300 hover:text-white transition-all p-2 rounded-lg hover:bg-slate-700/50">
            <i class="fas fa-bell text-lg"></i>
            @if(isset($notificationCount) && $notificationCount > 0)
              <span class="absolute -top-1 -right-1 bg-pink-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center text-xs">{{ $notificationCount }}</span>
            @endif
          </button>
          <div class="dropdown-menu">
            <div class="p-3 border-b border-slate-600">
              <h6 class="font-semibold text-sm text-white">Notifikasi</h6>
            </div>
            <div class="max-h-64 overflow-y-auto">
              <a href="#" class="flex items-center gap-3 p-3 hover:bg-slate-600/50 transition-all">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                  <i class="fas fa-shopping-cart text-xs"></i>
                </div>
                <div class="flex-1">
                  <div class="text-sm font-medium text-white">Pesanan Baru</div>
                  <div class="text-xs text-slate-400">2 menit yang lalu</div>
                </div>
              </a>
              <a href="#" class="flex items-center gap-3 p-3 hover:bg-slate-600/50 transition-all">
                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                  <i class="fas fa-user-plus text-xs"></i>
                </div>
                <div class="flex-1">
                  <div class="text-sm font-medium text-white">Pelanggan Baru</div>
                  <div class="text-xs text-slate-400">15 menit yang lalu</div>
                </div>
              </a>
            </div>
            <div class="p-3 border-t border-slate-600">
              <a href="#" class="text-sm text-purple-400 hover:text-purple-300">Lihat Semua</a>
            </div>
          </div>
        </div>
        
        <!-- Messages -->
        <div class="dropdown">
          <button class="relative text-slate-300 hover:text-white transition-all p-2 rounded-lg hover:bg-slate-700/50">
            <i class="fas fa-envelope text-lg"></i>
            @if(isset($messageCount) && $messageCount > 0)
              <span class="absolute -top-1 -right-1 bg-purple-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center text-xs">{{ $messageCount }}</span>
            @endif
          </button>
          <div class="dropdown-menu">
            <div class="p-3 border-b border-slate-600">
              <h6 class="font-semibold text-sm text-white">Pesan</h6>
            </div>
            <div class="max-h-64 overflow-y-auto">
              <a href="#" class="flex items-center gap-3 p-3 hover:bg-slate-600/50 transition-all">
                <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center font-semibold text-xs">
                  JD
                </div>
                <div class="flex-1">
                  <div class="text-sm font-medium text-white">John Doe</div>
                  <div class="text-xs text-slate-400 truncate">Kapan produk ready?</div>
                </div>
              </a>
            </div>
            <div class="p-3 border-t border-slate-600">
              <a href="#" class="text-sm text-purple-400 hover:text-purple-300">Lihat Semua</a>
            </div>
          </div>
        </div>
        
        <!-- User Profile -->
        @auth
        <div class="dropdown">
          <button class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-700/50 transition-all">
            <div class="w-8 h-8 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center font-semibold text-sm">
              {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="hidden md:block">
              <div class="font-semibold text-sm">{{ Auth::user()->name }}</div>
              <div class="text-slate-400 text-xs flex items-center gap-1">
                <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                Online
              </div>
            </div>
            <i class="fas fa-chevron-down text-xs text-slate-400"></i>
          </button>
          <div class="dropdown-menu">
            <div class="p-3 border-b border-slate-600">
              <div class="font-semibold text-sm text-white">{{ Auth::user()->name }}</div>
              <div class="text-xs text-slate-400">{{ Auth::user()->email }}</div>
            </div>
            <div class="py-2">
              <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-600/50 transition-all">
                <i class="fas fa-user w-4"></i>
                Profil Saya
              </a>
              <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-600/50 transition-all">
                <i class="fas fa-cog w-4"></i>
                Pengaturan
              </a>
              <div class="border-t border-slate-600 my-2"></div>
              <a href="#" target="_blank" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-600/50 transition-all">
                <i class="fas fa-external-link-alt w-4"></i>
                Lihat Website
              </a>
              <form method="POST" action="{{ route('logout') }}" class="block">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-300 hover:text-red-200 hover:bg-red-500/10 transition-all">
                  <i class="fas fa-sign-out-alt w-4"></i>
                  Logout
                </button>
              </form>
            </div>
          </div>
        </div>
        @endauth
      </div>
    </header>
    
    <!-- Content Area -->
    <div class="p-6">
      @if(session('success'))
        <div class="bg-green-500/20 border border-green-500 text-green-300 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
          <i class="fas fa-check-circle"></i>
          {{ session('success') }}
        </div>
      @endif
      
      @if(session('error'))
        <div class="bg-red-500/20 border border-red-500 text-red-300 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
          <i class="fas fa-exclamation-circle"></i>
          {{ session('error') }}
        </div>
      @endif
      
      @if($errors->any())
        <div class="bg-red-500/20 border border-red-500 text-red-300 px-4 py-3 rounded-xl mb-6">
          <div class="flex items-center gap-3 mb-2">
            <i class="fas fa-exclamation-triangle"></i>
            <span class="font-medium">Terdapat kesalahan:</span>
          </div>
          <ul class="list-disc list-inside space-y-1 text-sm">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      
      @yield('content')
    </div>
  </main>

  <!-- Loading Overlay -->
  <div id="loading-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 text-center">
      <div class="w-10 h-10 border-4 border-purple-600 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
      <div class="text-white">Memproses...</div>
    </div>
  </div>

  <script>
    // Global functions
    window.showLoading = function() {
      document.getElementById('loading-overlay').classList.remove('hidden');
    };
    
    window.hideLoading = function() {
      document.getElementById('loading-overlay').classList.add('hidden');
    };
    
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
      
      // Save state to localStorage
      localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    });
    
    // Restore sidebar state
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
      sidebar.classList.add('collapsed');
      mainContent.classList.add('expanded');
      sidebarTexts.forEach(text => {
        text.classList.add('hidden');
      });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
      const dropdowns = document.querySelectorAll('.dropdown-menu.show');
      dropdowns.forEach(dropdown => {
        if (!dropdown.closest('.dropdown').contains(event.target)) {
          dropdown.classList.remove('show');
        }
      });
    });
    
    // Handle dropdown clicks
    document.querySelectorAll('.dropdown > button').forEach(button => {
      button.addEventListener('click', function(e) {
        e.stopPropagation();
        const dropdown = this.nextElementSibling;
        dropdown.classList.toggle('show');
        
        // Close other dropdowns
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
          if (menu !== dropdown) {
            menu.classList.remove('show');
          }
        });
      });
    });
    
    // Auto-hide alerts
    setTimeout(() => {
      const alerts = document.querySelectorAll('[class*="bg-green-500/20"], [class*="bg-red-500/20"]');
      alerts.forEach(alert => {
        alert.style.transition = 'all 0.5s ease';
        alert.style.opacity = '0';
        alert.style.transform = 'translateX(100%)';
        setTimeout(() => alert.remove(), 500);
      });
    }, 5000);

    console.log('Admin Layout Ready!');
    
    @yield('scripts')
  </script>
</body>
</html>