

@extends('user.layouts.app')
@section('title', 'Detail Ulasan - SoleStyle')
@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white mb-2">Detail Ulasan</h1>
        <p class="text-slate-400">Lihat ulasan Anda tentang produk ini</p>
    </div>
    
    <!-- Product Info -->
    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6 mb-6">
        <div class="flex gap-4">
            <div class="w-24 h-24 bg-slate-700 rounded-lg overflow-hidden flex-shrink-0">
                <img src="{{ $review->orderItem->product->image_url ?? asset('images/default-product.jpg') }}" 
                     alt="{{ $review->orderItem->product->name }}" 
                     class="w-full h-full object-cover">
            </div>
            <div>
                <h2 class="text-xl font-semibold text-white mb-1">{{ $review->orderItem->product->name }}</h2>
                <p class="text-slate-400 text-sm mb-2">Order #{{ $review->orderItem->order->order_number }}</p>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-slate-400">Size: {{ $review->orderItem->size_display }}</span>
                    <span class="text-sm text-slate-400">•</span>
                    <span class="text-sm text-slate-400">Qty: {{ $review->orderItem->quantity }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Review Details -->
    <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl p-6">
        <!-- Rating -->
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-2">
                <h3 class="text-lg font-semibold text-white">Rating</h3>
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $review->rating)
                            <i class="fas fa-star text-yellow-400"></i>
                        @else
                            <i class="far fa-star text-slate-400"></i>
                        @endif
                    @endfor
                </div>
            </div>
        </div>
        
        <!-- Comment -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-white mb-3">Ulasan Anda</h3>
            <div class="bg-slate-700/30 rounded-lg p-4">
                <p class="text-white">{{ nl2br(e($review->comment)) }}</p>
            </div>
        </div>
        
        <!-- Images -->
        @if($review->images->count() > 0)
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-white mb-3">Gambar Ulasan</h3>
            <div class="grid grid-cols-3 gap-3">
                @foreach($review->images as $image)
                <div class="relative group">
                    <img src="{{ $image->image_url }}" alt="Ulasan gambar" class="w-full h-32 object-cover rounded-lg">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-lg">
                        <a href="{{ $image->image_url }}" target="_blank" class="text-white hover:text-purple-300">
                            <i class="fas fa-expand"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Review Info -->
        <div class="border-t border-slate-700/50 pt-4 flex justify-between items-center">
            <div class="text-sm text-slate-400">
                <p><i class="far fa-calendar mr-1"></i> {{ $review->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div>
                <a href="{{ route('review.index', $review->orderItem->order->id) }}" 
                   class="text-purple-400 hover:text-purple-300 text-sm font-medium flex items-center gap-2 px-3 py-2 hover:bg-slate-700/50 rounded-lg transition-all">
                    Lihat Penilaian <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection>