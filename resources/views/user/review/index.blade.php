@extends('user.layouts.app')
@section('title', 'Penilaian Saya - SoleStyle')
@section('content')
<div class="container mx-auto px-4 py-6">
    
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white mb-2">Penilaian Saya</h1>
        <p class="text-slate-400">Lihat dan kelola ulasan Anda untuk produk yang sudah diterima</p>
    </div>
    
    <!-- Rating Summary -->
    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="text-4xl font-bold text-white mb-2">{{ $ratingStats['average_rating'] > 0 ? number_format($ratingStats['average_rating'], 1) : '0.0' }}</div>
                <div class="flex justify-center mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        @if($ratingStats['average_rating'] >= $i)
                            <i class="fas fa-star text-yellow-400"></i>
                        @else
                            <i class="far fa-star text-slate-600"></i>
                        @endif
                    @endfor
                </div>
                <p class="text-sm text-slate-400">Rating Rata-rata</p>
            </div>
            
            <div class="text-center">
                <div class="text-4xl font-bold text-white mb-2">{{ $ratingStats['total_reviews'] }}</div>
                <p class="text-sm text-slate-400">Total Ulasan</p>
            </div>
            
            <div class="text-center">
                <div class="text-4xl font-bold text-white mb-2">{{ $ratingStats['pending_reviews'] }}</div>
                <p class="text-sm text-slate-400">Belum Dinilai</p>
            </div>
        </div>
    </div>
    
    <!-- Filter Rating -->
    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-1 mb-6">
        <div class="flex overflow-x-auto">
            <a href="?rating=all" class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap {{ request('rating', 'all') === 'all' ? 'bg-purple-600 text-white' : 'text-slate-400 hover:text-white' }}">
                Semua ({{ $ratingStats['total_reviews'] }})
            </a>
            <a href="?rating=5" class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap {{ request('rating') === '5' ? 'bg-purple-600 text-white' : 'text-slate-400 hover:text-white' }}">
                5 Bintang ({{ $ratingStats['rating_5'] }})
            </a>
            <a href="?rating=4" class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap {{ request('rating') === '4' ? 'bg-purple-600 text-white' : 'text-slate-400 hover:text-white' }}">
                4 Bintang ({{ $ratingStats['rating_4'] }})
            </a>
            <a href="?rating=3" class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap {{ request('rating') === '3' ? 'bg-purple-600 text-white' : 'text-slate-400 hover:text-white' }}">
                3 Bintang ({{ $ratingStats['rating_3'] }})
            </a>
            <a href="?rating=2" class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap {{ request('rating') === '2' ? 'bg-purple-600 text-white' : 'text-slate-400 hover:text-white' }}">
                2 Bintang ({{ $ratingStats['rating_2'] }})
            </a>
            <a href="?rating=1" class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap {{ request('rating') === '1' ? 'bg-purple-600 text-white' : 'text-slate-400 hover:text-white' }}">
                1 Bintang ({{ $ratingStats['rating_1'] }})
            </a>
        </div>
    </div>
    
    <!-- Search -->
    <div class="mb-6">
        <form method="GET" class="max-w-md">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari ulasan produk..."
                       class="w-full bg-slate-700/50 border border-slate-600 rounded-lg pl-10 pr-4 py-2 text-white placeholder-slate-400">
                <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                <button type="submit" class="absolute right-2 top-1 bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-sm">
                    Cari
                </button>
            </div>
        </form>
    </div>
    
    <!-- Reviews List -->
    @if($reviews->count() > 0)
        <div class="space-y-4">
            @foreach($reviews as $review)
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl overflow-hidden">
                <!-- Review Header with Product Info -->
                <div class="p-4 border-b border-slate-700/50">
                    <div class="flex gap-4">
                        <!-- Product Image -->
                        <div class="w-20 h-20 bg-slate-700 rounded-lg overflow-hidden flex-shrink-0">
                            <img src="{{ $review->orderItem->product->image_url ?? asset('images/default-product.jpg') }}" 
                                 alt="{{ $review->orderItem->product->name }}" 
                                 class="w-full h-full object-cover">
                        </div>
                        
                        <!-- Product Info and Rating -->
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="text-white font-semibold">{{ $review->orderItem->product->name }}</h3>
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                                        @else
                                            <i class="far fa-star text-slate-600 text-sm"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <p class="text-slate-400 text-sm mb-2">Order #{{ $review->orderItem->order->order_number }}</p>
                            <div class="flex items-center gap-2 text-xs text-slate-400">
                                <span>Size: {{ $review->orderItem->size_display }}</span>
                                <span>•</span>
                                <span>Qty: {{ $review->orderItem->quantity }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Review Content -->
                <div class="p-4">
                    <div class="mb-3">
                        <p class="text-white text-sm">{{ nl2br($review->comment) }}</p>
                    </div>
                    
                    <!-- Review Images (if any) -->
                    @if($review->images && count($review->images) > 0)
                    <div class="grid grid-cols-3 gap-2 mb-3">
                        @foreach($review->images as $image)
                        <div class="aspect-square bg-slate-700 rounded-lg overflow-hidden">
                            <img src="{{ $image->image_url }}" alt="Review image" class="w-full h-full object-cover">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                
                <!-- Review Footer -->
                <div class="px-4 py-3 bg-slate-700/20 border-t border-slate-700/50 flex justify-between items-center">
                    <div class="text-xs text-slate-400">
                        {{ $review->created_at->diffForHumans() }}
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex gap-2">
                        @if($review->is_helpful_count > 0)
                            <span class="text-xs text-green-400">
                                <i class="fas fa-thumbs-up mr-1"></i>{{ $review->is_helpful_count }}
                            </span>
                        @endif
                        
                        <button onclick="toggleHelpful({{ $review->id }})" 
                                class="text-xs text-slate-400 hover:text-purple-400">
                            <i class="fas fa-thumbs-up"></i> Bermanfaat
                        </button>
                        
                        <a href="{{ route('review.show', $review->id) }}" 
                           class="text-xs text-purple-400 hover:text-purple-300">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $reviews->withQueryString()->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-8 text-center">
            <div class="w-16 h-16 bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-star text-2xl text-slate-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Belum Ada Penilaian</h3>
            <p class="text-slate-400 mb-4">Berikan penilaian Anda untuk produk yang sudah diterima</p>
            
            <!-- List of products that can be reviewed -->
            @if($pendingReviews->count() > 0)
            <div class="max-w-2xl mx-auto mb-6">
                <p class="text-sm text-slate-400 mb-4">Produk yang dapat dinilai:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($pendingReviews as $orderId => $items)
                        @foreach($items as $item)
                        <div class="bg-slate-700/30 rounded-lg p-4 flex gap-4">
                            <div class="w-16 h-16 bg-slate-700 rounded-lg overflow-hidden flex-shrink-0">
                                <img src="{{ $item->product->image_url ?? asset('images/default-product.jpg') }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 text-left">
                                <h4 class="text-white font-medium mb-1">{{ $item->product->name }}</h4>
                                <p class="text-slate-400 text-xs mb-2">Order #{{ $item->order->order_number }}</p>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('review.create', $item->id) }}" 
                                       class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-xs font-medium">
                                        <i class="fas fa-star mr-1"></i> Nilai
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endforeach
                </div>
                <a href="{{ route('orders.index', ['status' => 'delivered']) }}" 
                   class="text-sm text-purple-400 hover:text-purple-300 mt-4 block text-center">
                    Lihat semua produk <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            @endif
        </div>
    @endif
</div>

<!-- Review Helpful Modal -->
<div id="helpfulModal" class="fixed inset-0 bg-black/50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-slate-800 border border-slate-700 rounded-xl max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Apakah ulasan ini bermanfaat?</h3>
            
            <div class="mb-4">
                <p class="text-slate-300 text-sm mb-4">Klik "Ya" jika ulasan ini membantu Anda dalam memutuskan pembelian</p>
                
                <div class="flex gap-3">
                    <button onclick="markHelpful({{ $review->id ?? 0 }}, true)" 
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg text-sm">
                        Ya, bermanfaat
                    </button>
                    <button onclick="markHelpful({{ $review->id ?? 0 }}, false)" 
                            class="flex-1 bg-slate-600 hover:bg-slate-700 text-white py-2 rounded-lg text-sm">
                        Tidak
                    </button>
                </div>
            </div>
            
            <div class="text-center">
                <button onclick="closeHelpfulModal()" 
                        class="text-purple-400 hover:text-purple-300 text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function toggleHelpful(reviewId) {
    document.getElementById('helpfulModal').classList.remove('hidden');
}
function closeHelpfulModal() {
    document.getElementById('helpfulModal').classList.add('hidden');
}
function markHelpful(reviewId, isHelpful) {
    fetch(`/reviews/${reviewId}/mark-helpful`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            is_helpful: isHelpful
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeHelpfulModal();
            // Update the helpful count in the UI
            const helpfulCountElement = document.querySelector(`#review-${reviewId} .helpful-count`);
            if (helpfulCountElement) {
                helpfulCountElement.textContent = data.helpful_count;
            }
        } else {
            showNotification(data.message, 'error');
        }
    });
}
function showNotification(message, type = 'info') {
    const colors = {
        success: 'from-green-500 to-emerald-500',
        error: 'from-red-500 to-pink-500'
    };
    
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 bg-gradient-to-r ${colors[type]} text-white px-4 py-2 rounded-lg z-50`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    setTimeout(() => document.body.removeChild(toast), 3000);
}
</script>
@endsection