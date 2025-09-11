@extends('user.layouts.app')

@section('title', 'Berikan Ulasan - SoleStyle')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white mb-2">Berikan Ulasan</h1>
        <p class="text-slate-400">Bagikan pengalaman Anda tentang produk ini</p>
    </div>
    
    <!-- Product Info -->
    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6 mb-6">
        <div class="flex gap-4">
            <div class="w-24 h-24 bg-slate-700 rounded-lg overflow-hidden flex-shrink-0">
                <img src="{{ $orderItem->product->image_url ?? asset('images/default-product.jpg') }}" 
                     alt="{{ $orderItem->product->name }}" 
                     class="w-full h-full object-cover">
            </div>
            <div>
                <h2 class="text-xl font-semibold text-white mb-1">{{ $orderItem->product->name }}</h2>
                <p class="text-slate-400 text-sm mb-2">Order #{{ $orderItem->order->order_number }}</p>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-slate-400">Size: {{ $orderItem->size_display }}</span>
                    <span class="text-sm text-slate-400">•</span>
                    <span class="text-sm text-slate-400">Qty: {{ $orderItem->quantity }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Review Form -->
    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6">
        <form action="{{ route('review.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="order_item_id" value="{{ $orderItem->id }}">
            
            <!-- Rating -->
            <div class="mb-6">
                <label class="block text-white font-medium mb-3">Rating</label>
                <div class="flex items-center gap-1 mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" class="star-btn text-2xl text-slate-400 hover:text-yellow-400 transition-colors" data-rating="{{ $i }}">
                            <i class="far fa-star"></i>
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating" value="0" required>
                <p class="text-sm text-slate-400">Klik bintang untuk memberikan rating</p>
            </div>
            
            <!-- Comment -->
            <div class="mb-6">
                <label for="comment" class="block text-white font-medium mb-2">Ulasan Anda</label>
                <textarea id="comment" name="comment" rows="4" 
                          class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2 text-white placeholder-slate-400"
                          placeholder="Tulis ulasan Anda tentang produk ini..." required></textarea>
                <p class="text-sm text-slate-400 mt-1">Maksimal 1000 karakter</p>
            </div>
            
            <!-- Images -->
            <div class="mb-6">
                <label class="block text-white font-medium mb-2">Gambar (Opsional)</label>
                <div class="grid grid-cols-3 gap-3 mb-3" id="image-preview-container">
                    <!-- Image previews will be added here dynamically -->
                </div>
                <div class="flex items-center justify-center w-full">
                    <label for="images" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-600 rounded-lg cursor-pointer bg-slate-700/50 hover:bg-slate-700 transition-colors">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i class="fas fa-cloud-upload-alt text-2xl text-slate-400 mb-2"></i>
                            <p class="text-sm text-slate-400">Klik untuk upload gambar</p>
                        </div>
                        <input id="images" type="file" name="images[]" class="hidden" accept="image/*" multiple>
                    </label>
                </div>
                <p class="text-sm text-slate-400 mt-2">Maksimal 5 gambar, masing-masing maksimal 2MB</p>
            </div>
            
            <!-- Submit Button -->
            <div class="flex gap-3">
                <a href="{{ route('orders.index') }}" 
                   class="flex-1 bg-slate-600 hover:bg-slate-700 text-white py-3 rounded-lg text-center font-medium">
                    Batal
                </a>
                <button type="submit" 
                        class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-lg text-center font-medium">
                    Kirim Ulasan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Star rating functionality
    const stars = document.querySelectorAll('.star-btn');
    const ratingInput = document.getElementById('rating');
    
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            ratingInput.value = rating;
            
            // Update star display
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.innerHTML = '<i class="fas fa-star text-yellow-400"></i>';
                } else {
                    s.innerHTML = '<i class="far fa-star text-slate-400"></i>';
                }
            });
        });
        
        star.addEventListener('mouseenter', function() {
            const rating = this.dataset.rating;
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.add('text-yellow-400');
                    s.classList.remove('text-slate-400');
                } else {
                    s.classList.add('text-slate-400');
                    s.classList.remove('text-yellow-400');
                }
            });
        });
    });
    
    // Reset stars on mouse leave
    document.querySelector('.star-btn').parentElement.addEventListener('mouseleave', function() {
        const currentRating = ratingInput.value;
        stars.forEach((s, index) => {
            if (index < currentRating) {
                s.innerHTML = '<i class="fas fa-star text-yellow-400"></i>';
            } else {
                s.innerHTML = '<i class="far fa-star text-slate-400"></i>';
            }
        });
    });
    
    // Image upload preview
    const imageInput = document.getElementById('images');
    const previewContainer = document.getElementById('image-preview-container');
    let imageCount = 0;
    const maxImages = 5;
    
    imageInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        
        files.forEach(file => {
            if (imageCount >= maxImages) return;
            
            // Check file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('File terlalu besar. Maksimal 2MB');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const imageDiv = document.createElement('div');
                imageDiv.className = 'relative group';
                imageDiv.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover rounded-lg">
                    <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity" onclick="removeImage(this)">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                previewContainer.appendChild(imageDiv);
                imageCount++;
            };
            reader.readAsDataURL(file);
        });
    });
    
    // Remove image function
    window.removeImage = function(button) {
        button.parentElement.remove();
        imageCount--;
    };
});
</script>
@endsection