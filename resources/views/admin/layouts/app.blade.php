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
      margin-left: 280px;
    }
    
    .main-content.expanded {
      margin-left: 80px;
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
    
    /* Status badge protection - prevent auto-hide scripts from affecting these */
    .status-badge,
    .status-container,
    .order-status,
    [data-status],
    .bg-amber-500\/20,
    .bg-cyan-500\/20,
    .bg-violet-500\/20,
    .bg-blue-500\/20,
    .bg-emerald-500\/20,
    .bg-rose-500\/20 {
      visibility: visible !important;
      opacity: 1 !important;
      display: inline-flex !important;
      position: static !important;
      transform: none !important;
      animation: none !important;
      transition: none !important;
    }
    
    /* Prevent alert auto-hide from affecting status elements */
    .status-badge:not(.alert-message),
    .status-container:not(.alert-message),
    table .status-badge,
    table .status-container {
      pointer-events: auto !important;
      user-select: auto !important;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
        position: fixed;
        transform: translateX(-100%);
        z-index: 40;
      }
      
      .sidebar.show {
        transform: translateX(0);
      }
      
      .sidebar.collapsed {
        width: 80px;
        transform: translateX(0);
      }
      
      .main-content {
        margin-left: 0;
      }
      
      .main-content.expanded {
        margin-left: 0;
      }
    }
    
    @yield('styles')
  </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white min-h-screen">

  <!-- Mobile Overlay -->
  <div id="mobile-overlay" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden"></div>

  <!-- Sidebar -->
  <aside class="sidebar glass-effect h-screen fixed left-0 top-0 z-40 overflow-y-auto flex flex-col">
    <!-- Header -->
    <div class="p-6 flex items-center justify-between border-b border-slate-700">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl flex items-center justify-center">
          <i class="fas fa-crown text-white"></i>
        </div>
        <h1 class="text-xl font-bold text-gradient sidebar-text">SoleStyle</h1>
      </div>
      <button id="sidebar-toggle" class="text-slate-400 hover:text-white transition-colors">
        <i class="fas fa-bars"></i>
      </button>
    </div>
    
    <!-- Navigation Menu -->
    <div class="p-4 flex-1">
      <div class="mb-6">
        <div class="text-slate-400 text-xs uppercase font-semibold mb-3 px-4 sidebar-text">Menu Utama</div>
        <ul class="space-y-2">
          <li>
            <a href="{{ route('admin.dashboard') }}" 
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-700/50 group">
              <i class="fas fa-chart-pie w-5 text-center text-slate-400 group-hover:text-purple-400 transition-colors"></i>
              <span class="sidebar-text">Dashboard</span>
            </a>
          </li>
          <li>
            <a href="{{ url('products') }}" 
               class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-700/50 group">
              <i class="fas fa-shopping-bag w-5 text-center text-slate-400 group-hover:text-purple-400 transition-colors"></i>
              <span class="sidebar-text">Produk</span>
            </a>
          </li>
          <li>
            <a href="{{ url('category') }}" 
               class="nav-item {{ request()->routeIs('category.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-700/50 group">
              <i class="fas fa-tags w-5 text-center text-slate-400 group-hover:text-purple-400 transition-colors"></i>
              <span class="sidebar-text">Kategori</span>
            </a>
          </li>
          <li>
            <a href="{{ url('order') }}" 
               class="nav-item {{ request()->routeIs('order.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-slate-700/50 group">
              <i class="fas fa-shopping-cart w-5 text-center text-slate-400 group-hover:text-purple-400 transition-colors"></i>
              <span class="sidebar-text">Pesanan</span>
              @if(isset($pendingOrdersCount) && $pendingOrdersCount > 0)
                <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 ml-auto sidebar-text">{{ $pendingOrdersCount }}</span>
              @endif
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- User Info and Logout at Bottom -->
    @auth
    <div class="border-t border-slate-700 p-4 mt-auto">
      <!-- Logout Button -->
      <form method="POST" action="{{ route('logout') }}" class="block">
        @csrf
        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-300 hover:text-red-200 hover:bg-red-500/10 rounded-xl transition-all group">
          <i class="fas fa-sign-out-alt w-5 text-center group-hover:text-red-200 transition-colors"></i>
          <span class="sidebar-text">Logout</span>
        </button>
      </form>
    </div>
    @endauth
  </aside>

  <!-- Main Content -->
  <main class="main-content min-h-screen">
    <!-- Header -->
    <header class="glass-effect sticky top-0 z-20 p-4 border-b border-slate-700/30">
      <div class="flex items-center justify-between">
        <!-- Mobile Menu Button -->
        <button id="mobile-menu-btn" class="lg:hidden text-slate-300 hover:text-white transition-colors">
          <i class="fas fa-bars text-xl"></i>
        </button>
        
        <!-- Search Bar -->
        <div class="flex-1 max-w-md mx-4">
          <div class="relative">
            <input type="text" 
                   placeholder="Cari sesuatu..." 
                   class="w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3 pl-12 focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20 transition-all">
            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
          </div>
        </div>
        
        <!-- User Profile -->
        @auth
        <div class="dropdown">
          <button class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-700/50 transition-all">
            <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center font-semibold text-sm">
              {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="hidden md:block text-left">
              <div class="font-semibold text-sm text-white">{{ Auth::user()->name }}</div>
              <div class="text-slate-400 text-xs flex items-center gap-1">
                <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                Online
              </div>
            </div>
            <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200"></i>
          </button>
          
          <div class="dropdown-menu">
            <div class="p-4 border-b border-slate-600">
              <div class="font-semibold text-sm text-white">{{ Auth::user()->name }}</div>
              <div class="text-xs text-slate-400">{{ Auth::user()->email }}</div>
            </div>
            <div class="py-2">
              <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:text-white hover:bg-slate-600/50 transition-all">
                <i class="fas fa-user w-4 text-center"></i>
                Profil Saya
              </a>
              <div class="border-t border-slate-600 my-2"></div>
              <a href="{{ url('dashboard') }}" target="_blank" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:text-white hover:bg-slate-600/50 transition-all">
                <i class="fas fa-external-link-alt w-4 text-center"></i>
                Lihat Website
              </a>
            </div>
          </div>
        </div>
        @endauth
      </div>
    </header>
    
    <!-- Content Area -->
    <div class="p-6">
      <!-- Alert Messages -->
      @if(session('success'))
        <div class="alert-message bg-green-500/20 border border-green-500/50 text-green-300 px-6 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-lg" data-alert="success">
          <i class="fas fa-check-circle text-green-400"></i>
          <span>{{ session('success') }}</span>
          <button onclick="this.parentElement.remove()" class="ml-auto text-green-400 hover:text-green-300">
            <i class="fas fa-times"></i>
          </button>
        </div>
      @endif
      
      @if(session('error'))
        <div class="alert-message bg-red-500/20 border border-red-500/50 text-red-300 px-6 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-lg" data-alert="error">
          <i class="fas fa-exclamation-circle text-red-400"></i>
          <span>{{ session('error') }}</span>
          <button onclick="this.parentElement.remove()" class="ml-auto text-red-400 hover:text-red-300">
            <i class="fas fa-times"></i>
          </button>
        </div>
      @endif
      
      @if($errors->any())
        <div class="alert-message bg-red-500/20 border border-red-500/50 text-red-300 px-6 py-4 rounded-xl mb-6 shadow-lg" data-alert="validation">
          <div class="flex items-center gap-3 mb-3">
            <i class="fas fa-exclamation-triangle text-red-400"></i>
            <span class="font-medium">Terdapat kesalahan:</span>
          </div>
          <ul class="list-disc list-inside space-y-1 text-sm ml-6">
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
    <div class="bg-slate-800/90 border border-slate-700 rounded-xl p-8 text-center shadow-2xl">
      <div class="w-12 h-12 border-4 border-purple-600 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
      <div class="text-white font-medium">Memproses...</div>
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
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    const mobileOverlay = document.getElementById('mobile-overlay');
    const sidebarTexts = document.querySelectorAll('.sidebar-text');

    // Desktop sidebar toggle
    if (sidebarToggle) {
      sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
        
        sidebarTexts.forEach(text => {
          text.classList.toggle('hidden');
        });
        
        // Save state to localStorage
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
      });
    }

    // Mobile menu toggle
    if (mobileMenuBtn) {
      mobileMenuBtn.addEventListener('click', () => {
        sidebar.classList.add('show');
        mobileOverlay.classList.remove('hidden');
      });
    }

    // Close mobile menu
    if (mobileOverlay) {
      mobileOverlay.addEventListener('click', () => {
        sidebar.classList.remove('show');
        mobileOverlay.classList.add('hidden');
      });
    }
    
    // Restore sidebar state on desktop
    if (window.innerWidth >= 1024 && localStorage.getItem('sidebarCollapsed') === 'true') {
      sidebar.classList.add('collapsed');
      mainContent.classList.add('expanded');
      sidebarTexts.forEach(text => {
        text.classList.add('hidden');
      });
    }
    
    // Handle window resize
    window.addEventListener('resize', () => {
      if (window.innerWidth >= 1024) {
        sidebar.classList.remove('show');
        mobileOverlay.classList.add('hidden');
      }
    });
    
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
        e.stopPropagation();const dropdown = this.nextElementSibling;
        const allDropdowns = document.querySelectorAll('.dropdown-menu');
        
        // Close all other dropdowns
        allDropdowns.forEach(menu => {
          if (menu !== dropdown) {
            menu.classList.remove('show');
          }
        });
        
        // Toggle current dropdown
        dropdown.classList.toggle('show');
      });
    });
    
    // Auto-hide success alerts after 5 seconds
    document.querySelectorAll('[data-alert="success"]').forEach(alert => {
      setTimeout(() => {
        if (alert && alert.parentElement) {
          alert.style.transition = 'all 0.3s ease';
          alert.style.opacity = '0';
          alert.style.transform = 'translateY(-10px)';
          setTimeout(() => {
            if (alert.parentElement) {
              alert.remove();
            }
          }, 300);
        }
      }, 5000);
    });
    
    // Enhanced form submission with loading
    document.querySelectorAll('form[data-loading="true"]').forEach(form => {
      form.addEventListener('submit', function() {
        showLoading();
      });
    });
    
    // AJAX form handler
    window.submitForm = function(formElement, options = {}) {
      const formData = new FormData(formElement);
      const method = formElement.method || 'POST';
      const action = formElement.action;
      
      // Show loading if not disabled
      if (options.showLoading !== false) {
        showLoading();
      }
      
      fetch(action, {
        method: method,
        body: formData,
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(response => response.json())
      .then(data => {
        hideLoading();
        
        if (data.success) {
          if (data.message) {
            showAlert('success', data.message);
          }
          if (options.onSuccess) {
            options.onSuccess(data);
          }
          if (data.redirect) {
            window.location.href = data.redirect;
          }
        } else {
          if (data.message) {
            showAlert('error', data.message);
          }
          if (data.errors) {
            showValidationErrors(data.errors);
          }
          if (options.onError) {
            options.onError(data);
          }
        }
      })
      .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        if (options.onError) {
          options.onError(error);
        }
      });
    };
    
    // Alert system
    window.showAlert = function(type, message) {
      // Remove existing alerts of the same type
      document.querySelectorAll(`[data-alert="${type}"]`).forEach(alert => {
        alert.remove();
      });
      
      const alertClass = type === 'success' 
        ? 'bg-green-500/20 border-green-500/50 text-green-300' 
        : 'bg-red-500/20 border-red-500/50 text-red-300';
      
      const iconClass = type === 'success' 
        ? 'fas fa-check-circle text-green-400' 
        : 'fas fa-exclamation-circle text-red-400';
      
      const alertHtml = `
        <div class="alert-message ${alertClass} px-6 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-lg" data-alert="${type}">
          <i class="${iconClass}"></i>
          <span>${message}</span>
          <button onclick="this.parentElement.remove()" class="ml-auto text-current hover:opacity-70">
            <i class="fas fa-times"></i>
          </button>
        </div>
      `;
      
      const contentArea = document.querySelector('.p-6');
      if (contentArea) {
        contentArea.insertAdjacentHTML('afterbegin', alertHtml);
        
        // Auto-hide success alerts
        if (type === 'success') {
          setTimeout(() => {
            const alert = document.querySelector(`[data-alert="${type}"]`);
            if (alert) {
              alert.style.transition = 'all 0.3s ease';
              alert.style.opacity = '0';
              alert.style.transform = 'translateY(-10px)';
              setTimeout(() => alert.remove(), 300);
            }
          }, 5000);
        }
      }
    };
    
    // Validation errors display
    window.showValidationErrors = function(errors) {
      let errorList = '';
      Object.values(errors).flat().forEach(error => {
        errorList += `<li>${error}</li>`;
      });
      
      const errorHtml = `
        <div class="alert-message bg-red-500/20 border-red-500/50 text-red-300 px-6 py-4 rounded-xl mb-6 shadow-lg" data-alert="validation">
          <div class="flex items-center gap-3 mb-3">
            <i class="fas fa-exclamation-triangle text-red-400"></i>
            <span class="font-medium">Terdapat kesalahan:</span>
          </div>
          <ul class="list-disc list-inside space-y-1 text-sm ml-6">
            ${errorList}
          </ul>
        </div>
      `;
      
      const contentArea = document.querySelector('.p-6');
      if (contentArea) {
        // Remove existing validation errors
        document.querySelectorAll('[data-alert="validation"]').forEach(alert => {
          alert.remove();
        });
        contentArea.insertAdjacentHTML('afterbegin', errorHtml);
      }
    };
    
    // Confirmation dialog
    window.confirmAction = function(message, callback) {
      if (confirm(message)) {
        if (typeof callback === 'function') {
          callback();
        }
        return true;
      }
      return false;
    };
    
    // Enhanced confirmation with custom modal (optional)
    window.confirmDelete = function(itemName, deleteUrl, options = {}) {
      const message = options.message || `Apakah Anda yakin ingin menghapus "${itemName}"? Tindakan ini tidak dapat dibatalkan.`;
      
      if (confirm(message)) {
        showLoading();
        
        fetch(deleteUrl, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
          }
        })
        .then(response => response.json())
        .then(data => {
          hideLoading();
          
          if (data.success) {
            showAlert('success', data.message || 'Data berhasil dihapus');
            if (options.onSuccess) {
              options.onSuccess(data);
            } else {
              // Default: reload page after short delay
              setTimeout(() => {
                window.location.reload();
              }, 1000);
            }
          } else {
            showAlert('error', data.message || 'Gagal menghapus data');
            if (options.onError) {
              options.onError(data);
            }
          }
        })
        .catch(error => {
          hideLoading();
          console.error('Delete error:', error);
          showAlert('error', 'Terjadi kesalahan saat menghapus data');
          if (options.onError) {
            options.onError(error);
          }
        });
      }
    };
    
    // Data table utilities
    window.initDataTable = function(tableSelector, options = {}) {
      const table = document.querySelector(tableSelector);
      if (!table) return;
      
      // Add search functionality
      if (options.searchable !== false) {
        const searchInput = document.querySelector(options.searchSelector || '#table-search');
        if (searchInput) {
          searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
              const text = row.textContent.toLowerCase();
              row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
          });
        }
      }
      
      // Add sorting functionality
      if (options.sortable !== false) {
        const headers = table.querySelectorAll('th[data-sort]');
        headers.forEach(header => {
          header.style.cursor = 'pointer';
          header.addEventListener('click', function() {
            sortTable(table, this.dataset.sort, this.dataset.type || 'string');
          });
        });
      }
    };
    
    // Table sorting function
    function sortTable(table, column, type = 'string') {
      const tbody = table.querySelector('tbody');
      const rows = Array.from(tbody.querySelectorAll('tr'));
      const columnIndex = Array.from(table.querySelectorAll('th')).findIndex(th => th.dataset.sort === column);
      
      if (columnIndex === -1) return;
      
      const isAsc = tbody.dataset.sortDirection !== 'asc';
      tbody.dataset.sortDirection = isAsc ? 'asc' : 'desc';
      
      rows.sort((a, b) => {
        let aVal = a.cells[columnIndex].textContent.trim();
        let bVal = b.cells[columnIndex].textContent.trim();
        
        if (type === 'number') {
          aVal = parseFloat(aVal) || 0;
          bVal = parseFloat(bVal) || 0;
        } else if (type === 'date') {
          aVal = new Date(aVal);
          bVal = new Date(bVal);
        }
        
        if (aVal < bVal) return isAsc ? -1 : 1;
        if (aVal > bVal) return isAsc ? 1 : -1;
        return 0;
      });
      
      // Clear tbody and append sorted rows
      tbody.innerHTML = '';
      rows.forEach(row => tbody.appendChild(row));
      
      // Update sort indicators
      table.querySelectorAll('th[data-sort]').forEach(th => {
        th.classList.remove('sort-asc', 'sort-desc');
      });
      table.querySelector(`th[data-sort="${column}"]`).classList.add(isAsc ? 'sort-asc' : 'sort-desc');
    }
    
    // Image preview utility
    window.previewImage = function(input, previewSelector) {
      const file = input.files[0];
      const preview = document.querySelector(previewSelector);
      
      if (file && preview) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
      }
    };
    
    // Currency formatting
    window.formatCurrency = function(amount) {
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
      }).format(amount);
    };
    
    // Date formatting
    window.formatDate = function(dateString) {
      return new Intl.DateTimeFormat('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      }).format(new Date(dateString));
    };
    
    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize any data tables
      if (document.querySelector('[data-table]')) {
        document.querySelectorAll('[data-table]').forEach(table => {
          initDataTable(`#${table.id}`);
        });
      }
      
      // Initialize image previews
      document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
        input.addEventListener('change', function() {
          previewImage(this, this.dataset.preview);
        });
      });
    });
    
    @yield('scripts')
  </script>
</body>
</html>