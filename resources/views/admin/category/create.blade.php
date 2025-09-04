{{-- resources/views/admin/category/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Tambah Kategori')
@section('categories-active', 'active')

@section('styles')
<style>
  .glass-effect {
    background: rgba(30, 41, 59, 0.4);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(148, 163, 184, 0.1);
  }
  
  .form-group {
    position: relative;
  }
  
  .form-input {
    transition: all 0.3s ease;
  }
  
  .form-input:focus {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(147, 51, 234, 0.15);
  }
  
  .btn-save {
    position: relative;
    overflow: hidden;
  }
  
  .btn-save::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.6s;
  }
  
  .btn-save:hover::before {
    left: 100%;
  }
  
  @keyframes slideInUp {
    0% {
      opacity: 0;
      transform: translateY(20px);
    }
    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .animate-slide-up {
    animation: slideInUp 0.4s ease-out;
  }
  
  .input-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    transition: color 0.3s ease;
  }
  
  .form-input:focus + .input-icon {
    color: #a855f7;
  }
</style>
@endsection

@section('content')
<div class="space-y-8">
  <!-- Breadcrumb -->
  <div class="flex items-center space-x-2 text-slate-400 text-sm">
    <a href="{{ route('category.index') }}" class="hover:text-purple-400 transition-colors">
      <i class="fas fa-tags mr-1"></i>Kategori
    </a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-slate-300">Tambah Kategori</span>
  </div>

  <!-- Header -->
  <div class="flex items-center justify-between">
    <div class="flex items-center space-x-4">
      <div class="bg-gradient-to-br from-purple-500 to-pink-500 p-3 rounded-xl shadow-lg">
        <i class="fas fa-plus text-white text-xl"></i>
      </div>
      <div>
        <h1 class="text-2xl font-bold">Tambah Kategori Baru</h1>
        <p class="text-slate-400">Buat kategori baru untuk mengorganisir produk Anda</p>
      </div>
    </div>
    <a href="{{ route('category.index') }}"
       class="glass-effect hover:bg-slate-700/50 px-4 py-2 rounded-xl transition-all flex items-center gap-2">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali</span>
    </a>
  </div>

  <!-- Main Form -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Form Section -->
    <div class="lg:col-span-2">
      <div class="glass-effect rounded-2xl p-8 animate-slide-up">
        <form action="{{ route('category.store') }}" method="POST" id="category-form" class="space-y-6">
          @csrf

          <!-- Form Header -->
          <div class="border-b border-slate-700 pb-6 mb-6">
            <h3 class="text-lg font-semibold flex items-center gap-2">
              <i class="fas fa-info-circle text-purple-400"></i>
              Informasi Kategori
            </h3>
            <p class="text-slate-400 text-sm mt-1">Masukkan detail kategori yang akan dibuat</p>
          </div>

          <!-- Nama Kategori -->
          <div class="form-group">
            <label for="name" class="block text-sm font-semibold text-slate-300 mb-3">
              <i class="fas fa-tag mr-2 text-purple-400"></i>Nama Kategori
            </label>
            <div class="relative">
              <input type="text" 
                     name="name" 
                     id="name"
                     value="{{ old('name') }}"
                     placeholder="Masukkan nama kategori..."
                     class="form-input w-full bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-4 pr-12 text-white placeholder-slate-500 focus:border-purple-500 focus:outline-none">
              <i class="input-icon fas fa-folder"></i>
            </div>
            @error('name')
              <div class="flex items-center mt-2 text-red-400 text-sm">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                {{ $message }}
              </div>
            @enderror
            <p class="text-slate-500 text-xs mt-2">Nama kategori harus unik dan mudah diingat</p>
          </div>

          <!-- Status -->
          <div class="form-group">
            <label for="status" class="block text-sm font-semibold text-slate-300 mb-3">
              <i class="fas fa-toggle-on mr-2 text-purple-400"></i>Status Kategori
            </label>
            <div class="grid grid-cols-2 gap-4">
              <label class="relative cursor-pointer">
                <input type="radio" name="status" value="active" 
                       {{ old('status', 'active') == 'active' ? 'checked' : '' }}
                       class="sr-only peer">
                <div class="glass-effect peer-checked:bg-green-500/20 peer-checked:border-green-500/50 border border-slate-600 rounded-xl p-4 transition-all hover:bg-slate-700/30">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center">
                      <i class="fas fa-check text-green-400 text-sm"></i>
                    </div>
                    <div>
                      <div class="font-semibold text-green-400">Aktif</div>
                      <div class="text-xs text-slate-500">Kategori dapat digunakan</div>
                    </div>
                  </div>
                </div>
              </label>
              
              <label class="relative cursor-pointer">
                <input type="radio" name="status" value="inactive" 
                       {{ old('status') == 'inactive' ? 'checked' : '' }}
                       class="sr-only peer">
                <div class="glass-effect peer-checked:bg-red-500/20 peer-checked:border-red-500/50 border border-slate-600 rounded-xl p-4 transition-all hover:bg-slate-700/30">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-red-500/20 rounded-lg flex items-center justify-center">
                      <i class="fas fa-times text-red-400 text-sm"></i>
                    </div>
                    <div>
                      <div class="font-semibold text-red-400">Nonaktif</div>
                      <div class="text-xs text-slate-500">Kategori tidak dapat digunakan</div>
                    </div>
                  </div>
                </div>
              </label>
            </div>
            @error('status')
              <div class="flex items-center mt-2 text-red-400 text-sm">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                {{ $message }}
              </div>
            @enderror
          </div>

          <!-- Form Actions -->
          <div class="border-t border-slate-700 pt-6 mt-8">
            <div class="flex justify-end space-x-4">
              <a href="{{ route('category.index') }}"
                 class="glass-effect hover:bg-slate-700/50 px-6 py-3 rounded-xl font-semibold transition-all flex items-center gap-2">
                <i class="fas fa-times"></i>
                Batal
              </a>
              <button type="submit"
                      class="btn-save bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 px-8 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 flex items-center gap-2">
                <i class="fas fa-save"></i>
                Simpan Kategori
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Info Panel -->
    <div class="lg:col-span-1 space-y-6">
      <!-- Tips Card -->
      <div class="glass-effect rounded-2xl p-6 animate-slide-up" style="animation-delay: 0.1s;">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
            <i class="fas fa-lightbulb text-blue-400"></i>
          </div>
          <h3 class="font-semibold">Tips Kategori</h3>
        </div>
        <ul class="space-y-3 text-sm text-slate-400">
          <li class="flex items-start gap-2">
            <i class="fas fa-check-circle text-green-400 mt-0.5 text-xs"></i>
            <span>Gunakan nama yang jelas dan mudah dipahami</span>
          </li>
          <li class="flex items-start gap-2">
            <i class="fas fa-check-circle text-green-400 mt-0.5 text-xs"></i>
            <span>Hindari nama kategori yang terlalu panjang</span>
          </li>
          <li class="flex items-start gap-2">
            <i class="fas fa-check-circle text-green-400 mt-0.5 text-xs"></i>
            <span>Pastikan kategori tidak duplikat</span>
          </li>
          <li class="flex items-start gap-2">
            <i class="fas fa-check-circle text-green-400 mt-0.5 text-xs"></i>
            <span>Gunakan status aktif untuk kategori yang siap digunakan</span>
          </li>
        </ul>
      </div>

      <!-- Stats Card -->
      <div class="glass-effect rounded-2xl p-6 animate-slide-up" style="animation-delay: 0.2s;">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
            <i class="fas fa-chart-pie text-purple-400"></i>
          </div>
          <h3 class="font-semibold">Statistik Kategori</h3>
        </div>
        <div class="space-y-4">
          <div class="flex justify-between items-center">
            <span class="text-slate-400 text-sm">Total Kategori</span>
            <span class="bg-blue-500/20 text-blue-400 px-2 py-1 rounded text-sm font-semibold">
              {{ $totalCategories ?? 0 }}
            </span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-slate-400 text-sm">Kategori Aktif</span>
            <span class="bg-green-500/20 text-green-400 px-2 py-1 rounded text-sm font-semibold">
              {{ $activeCategories ?? 0 }}
            </span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-slate-400 text-sm">Kategori Nonaktif</span>
            <span class="bg-red-500/20 text-red-400 px-2 py-1 rounded text-sm font-semibold">
              {{ $inactiveCategories ?? 0 }}
            </span>
          </div>
        </div>
      </div>

      <!-- Recent Categories -->
      @if(isset($recentCategories) && $recentCategories->count() > 0)
      <div class="glass-effect rounded-2xl p-6 animate-slide-up" style="animation-delay: 0.3s;">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 bg-orange-500/20 rounded-lg flex items-center justify-center">
            <i class="fas fa-clock text-orange-400"></i>
          </div>
          <h3 class="font-semibold">Kategori Terbaru</h3>
        </div>
        <div class="space-y-3">
          @foreach($recentCategories as $recent)
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="w-6 h-6 bg-purple-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-folder text-purple-400 text-xs"></i>
              </div>
              <span class="text-sm">{{ Str::limit($recent->name, 15) }}</span>
            </div>
            <span class="text-xs text-slate-500">{{ $recent->created_at->diffForHumans() }}</span>
          </div>
          @endforeach
        </div>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('category-form');
  const nameInput = document.getElementById('name');
  const submitBtn = form.querySelector('button[type="submit"]');
  
  // Real-time validation
  nameInput.addEventListener('input', function() {
    const value = this.value.trim();
    const errorElement = this.parentNode.parentNode.querySelector('.text-red-400');
    
    if (value.length > 0) {
      this.classList.remove('border-red-500');
      this.classList.add('border-green-500');
      if (errorElement) {
        errorElement.remove();
      }
    } else {
      this.classList.remove('border-green-500');
      this.classList.add('border-slate-600');
    }
  });
  
  // Form submission with loading state
  form.addEventListener('submit', function(e) {
    const originalContent = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    submitBtn.disabled = true;
    
    // Validate form
    if (!nameInput.value.trim()) {
      e.preventDefault();
      submitBtn.innerHTML = originalContent;
      submitBtn.disabled = false;
      
      nameInput.focus();
      nameInput.classList.add('border-red-500');
      
      // Show error if not exists
      if (!nameInput.parentNode.parentNode.querySelector('.text-red-400')) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'flex items-center mt-2 text-red-400 text-sm';
        errorDiv.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i>Nama kategori wajib diisi';
        nameInput.parentNode.parentNode.appendChild(errorDiv);
      }
      return;
    }
  });
  
  // Character counter for name input
  nameInput.addEventListener('input', function() {
    const maxLength = 50;
    const currentLength = this.value.length;
    
    let counter = this.parentNode.parentNode.querySelector('.char-counter');
    if (!counter) {
      counter = document.createElement('div');
      counter.className = 'char-counter text-xs text-slate-500 mt-1 text-right';
      this.parentNode.parentNode.appendChild(counter);
    }
    
    counter.textContent = `${currentLength}/${maxLength} karakter`;
    
    if (currentLength > maxLength * 0.9) {
      counter.classList.add('text-yellow-400');
      counter.classList.remove('text-slate-500');
    } else {
      counter.classList.remove('text-yellow-400');
      counter.classList.add('text-slate-500');
    }
  });
  
  // Auto-focus on name input
  setTimeout(() => {
    nameInput.focus();
  }, 500);
  
  // Smooth scroll to form errors
  const firstError = document.querySelector('.text-red-400');
  if (firstError) {
    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
  // Ctrl/Cmd + S to save
  if ((e.ctrlKey || e.metaKey) && e.key === 's') {
    e.preventDefault();
    document.getElementById('category-form').submit();
  }
  
  // Escape to cancel
  if (e.key === 'Escape') {
    window.location.href = '{{ route("category.index") }}';
  }
});

// Add visual feedback for radio buttons
document.querySelectorAll('input[name="status"]').forEach(radio => {
  radio.addEventListener('change', function() {
    document.querySelectorAll('input[name="status"]').forEach(r => {
      const container = r.closest('label').querySelector('div');
      if (r.checked) {
        container.style.transform = 'scale(1.02)';
        setTimeout(() => {
          container.style.transform = 'scale(1)';
        }, 150);
      }
    });
  });
});

// Form validation feedback
function showValidationFeedback(input, isValid, message = '') {
  const container = input.parentNode.parentNode;
  let feedback = container.querySelector('.validation-feedback');
  
  if (!feedback) {
    feedback = document.createElement('div');
    feedback.className = 'validation-feedback flex items-center mt-2 text-sm';
    container.appendChild(feedback);
  }
  
  if (isValid) {
    feedback.className = 'validation-feedback flex items-center mt-2 text-sm text-green-400';
    feedback.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Valid';
    input.classList.remove('border-red-500');
    input.classList.add('border-green-500');
  } else {
    feedback.className = 'validation-feedback flex items-center mt-2 text-sm text-red-400';
    feedback.innerHTML = `<i class="fas fa-exclamation-triangle mr-1"></i>${message}`;
    input.classList.add('border-red-500');
    input.classList.remove('border-green-500');
  }
}
</script>
@endsection