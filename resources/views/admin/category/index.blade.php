{{-- resources/views/admin/category/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Kelola Kategori')
@section('categories-active', 'active')

@section('styles')
<style>
  .glass-effect {
    background: rgba(30, 41, 59, 0.4);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(148, 163, 184, 0.1);
  }
  .swal2-popup {
    background-color: #1e293b !important;
    color: #f1f5f9 !important;
    border: 1px solid rgba(148, 163, 184, 0.1) !important;
  }
</style>
@endsection

@section('content')
<!-- Flash Messages -->
@if(session('success'))
<div class="mb-6 bg-green-500/20 border border-green-500/30 text-green-400 px-4 py-3 rounded-xl" id="success-alert">
  <i class="fas fa-check-circle mr-2"></i>
  {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl" id="error-alert">
  <i class="fas fa-exclamation-circle mr-2"></i>
  {{ session('error') }}
</div>
@endif

<!-- Header -->
<div class="flex items-center justify-between mb-8">
  <div>
    <h2 class="text-2xl font-bold">Kelola Kategori</h2>
    <p class="text-slate-400">Tambahkan dan kelola kategori untuk produk Anda</p>
  </div>
  <div class="flex items-center gap-4">
    <div class="glass-effect px-4 py-2 rounded-xl flex items-center gap-2">
      <i class="fas fa-chart-bar text-purple-400"></i>
      <span class="text-slate-300">{{ $categories->total() }} Total Kategori</span>
    </div>
    <a href="{{ route('category.create') }}" 
       class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 px-4 py-2 rounded-xl font-semibold transition-all transform hover:scale-105 flex items-center gap-2">
      <i class="fas fa-plus"></i>
      <span>Tambah Kategori</span>
    </a>
  </div>
</div>

<!-- Categories Table -->
<div class="glass-effect rounded-2xl p-6 mb-8">
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead>
        <tr class="border-b border-slate-700">
          <th class="pb-4 text-left">
            <div class="flex items-center gap-2">
              <i class="fas fa-tag text-slate-400"></i>
              <span>Nama Kategori</span>
            </div>
          </th>
          <th class="pb-4 text-left">
            <div class="flex items-center gap-2">
              <i class="fas fa-toggle-on text-slate-400"></i>
              <span>Status</span>
            </div>
          </th>
          <th class="pb-4 text-left">
            <div class="flex items-center gap-2">
              <i class="fas fa-boxes text-slate-400"></i>
              <span>Total Produk</span>
            </div>
          </th>
          <th class="pb-4 text-left">
            <div class="flex items-center gap-2">
              <i class="fas fa-calendar text-slate-400"></i>
              <span>Dibuat</span>
            </div>
          </th>
          <th class="pb-4 text-right">
            <div class="flex items-center justify-end gap-2">
              <i class="fas fa-cogs text-slate-400"></i>
              <span>Aksi</span>
            </div>
          </th>
        </tr>
      </thead>
      <tbody>
        @forelse($categories as $category)
        <tr class="border-b border-slate-700 hover:bg-slate-700/30" data-category-id="{{ $category->id }}">
          <td class="py-4">
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-xl flex items-center justify-center border border-purple-500/30">
                <i class="fas fa-folder text-purple-400"></i>
              </div>
              <div>
                <div class="font-semibold">{{ $category->name }}</div>
                <div class="text-slate-400 text-sm">ID: #{{ $category->id }}</div>
              </div>
            </div>
          </td>
          <td class="py-4">
            @if($category->status == 'active')
              <span class="bg-green-500/20 text-green-400 px-3 py-1 rounded-lg text-sm font-semibold border border-green-500/30">
                <i class="fas fa-circle text-xs mr-1"></i>
                Aktif
              </span>
            @else
              <span class="bg-red-500/20 text-red-400 px-3 py-1 rounded-lg text-sm font-semibold border border-red-500/30">
                <i class="fas fa-circle text-xs mr-1"></i>
                Nonaktif
              </span>
            @endif
          </td>
          <td class="py-4">
            <div class="flex items-center gap-2">
              <span class="bg-blue-500/20 text-blue-400 px-2 py-1 rounded text-sm font-semibold">
                {{ $category->products_count ?? 0 }}
              </span>
              <span class="text-slate-500 text-sm">produk</span>
            </div>
          </td>
          <td class="py-4">
            <div class="text-slate-400 text-sm">
              <div>{{ $category->created_at->format('d M Y') }}</div>
              <div class="text-xs opacity-75">{{ $category->created_at->format('H:i') }}</div>
            </div>
          </td>
          <td class="py-4 text-right">
            <div class="flex justify-end gap-2">
              <a href="{{ route('category.show', $category->id) }}" 
                 class="p-2 text-slate-400 hover:text-blue-400 transition-colors" title="Lihat Detail">
                <i class="fas fa-eye"></i>
              </a>
              <a href="{{ route('category.edit', $category->id) }}" 
                 class="p-2 text-slate-400 hover:text-yellow-400 transition-colors" title="Edit">
                <i class="fas fa-edit"></i>
              </a>
              <button onclick="toggleCategoryStatus({{ $category->id }})" 
                      class="p-2 text-slate-400 hover:text-green-400 transition-colors" title="Toggle Status">
                <i class="fas fa-toggle-{{ $category->status === 'active' ? 'on' : 'off' }}"></i>
              </button>
              <button onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}')" 
                      class="p-2 text-slate-400 hover:text-red-400 transition-colors" title="Hapus">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="py-8 text-center text-slate-400">
            <div class="flex flex-col items-center justify-center space-y-4">
              <div class="w-16 h-16 bg-slate-700/50 rounded-xl flex items-center justify-center">
                <i class="fas fa-folder-open text-3xl text-slate-500"></i>
              </div>
              <div>
                <p class="text-lg font-semibold mb-1">Belum Ada Kategori</p>
                <p class="text-sm text-slate-500 mb-4">Mulai dengan menambahkan kategori pertama Anda</p>
                <a href="{{ route('category.create') }}"
                   class="inline-flex items-center bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Kategori Pertama
                </a>
              </div>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  
  <!-- Pagination -->
  @if($categories->hasPages())
  <div class="flex items-center justify-between mt-6">
    <div class="text-slate-400 text-sm">
      Menampilkan {{ $categories->firstItem() }}-{{ $categories->lastItem() }} dari {{ $categories->total() }} kategori
    </div>
    <div class="flex gap-2">
      <!-- Previous Page -->
      @if($categories->onFirstPage())
        <button disabled class="glass-effect w-10 h-10 rounded-xl flex items-center justify-center opacity-50 cursor-not-allowed">
          <i class="fas fa-chevron-left"></i>
        </button>
      @else
        <a href="{{ $categories->previousPageUrl() }}" class="glass-effect hover:bg-slate-700/50 w-10 h-10 rounded-xl flex items-center justify-center transition-colors">
          <i class="fas fa-chevron-left"></i>
        </a>
      @endif

      <!-- Page Numbers -->
      @foreach($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
        @if($page == $categories->currentPage())
          <button class="glass-effect bg-purple-500/20 text-purple-400 w-10 h-10 rounded-xl font-semibold">{{ $page }}</button>
        @else
          <a href="{{ $url }}" class="glass-effect hover:bg-slate-700/50 w-10 h-10 rounded-xl flex items-center justify-center transition-colors">{{ $page }}</a>
        @endif
      @endforeach

      <!-- Next Page -->
      @if($categories->hasMorePages())
        <a href="{{ $categories->nextPageUrl() }}" class="glass-effect hover:bg-slate-700/50 w-10 h-10 rounded-xl flex items-center justify-center transition-colors">
          <i class="fas fa-chevron-right"></i>
        </a>
      @else
        <button disabled class="glass-effect w-10 h-10 rounded-xl flex items-center justify-center opacity-50 cursor-not-allowed">
          <i class="fas fa-chevron-right"></i>
        </button>
      @endif
    </div>
  </div>
  @endif
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Auto hide alerts
setTimeout(() => {
  const alerts = document.querySelectorAll('#success-alert, #error-alert');
  alerts.forEach(alert => {
    alert.style.opacity = '0';
    alert.style.transform = 'translateY(-10px)';
    setTimeout(() => alert.remove(), 300);
  });
}, 5000);

// Delete category
function deleteCategory(id, name) {
  Swal.fire({
    title: 'Hapus Kategori?',
    text: `Apakah Anda yakin ingin menghapus kategori "${name}"?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal',
    background: '#1e293b',
    color: '#f1f5f9'
  }).then((result) => {
    if (result.isConfirmed) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = `/admin/category/${id}`;
      form.innerHTML = `
        @csrf
        @method('DELETE')
      `;
      document.body.appendChild(form);
      form.submit();
    }
  });
}

// Toggle category status
function toggleCategoryStatus(id) {
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = `/admin/category/${id}/toggle-status`;
  form.innerHTML = `
    @csrf
    @method('PATCH')
  `;
  document.body.appendChild(form);
  form.submit();
}

// Add smooth transitions to table rows
document.addEventListener('DOMContentLoaded', function() {
  const rows = document.querySelectorAll('tbody tr');
  rows.forEach((row, index) => {
    row.style.opacity = '0';
    row.style.transform = 'translateY(10px)';
    setTimeout(() => {
      row.style.transition = 'all 0.3s ease';
      row.style.opacity = '1';
      row.style.transform = 'translateY(0)';
    }, index * 50);
  });
});

// Add loading state to buttons
document.querySelectorAll('a[href*="create"], a[href*="edit"]').forEach(link => {
  link.addEventListener('click', function(e) {
    const originalContent = this.innerHTML;
    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
    this.style.pointerEvents = 'none';
    
    // Restore if navigation is cancelled
    setTimeout(() => {
      if (this.innerHTML.includes('spinner')) {
        this.innerHTML = originalContent;
        this.style.pointerEvents = 'auto';
      }
    }, 3000);
  });
});
</script>
@endsection