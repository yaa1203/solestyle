<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pendingReviews = OrderItem::with(['order', 'product'])
            ->whereHas('order', function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('status', 'delivered');
            })
            ->whereDoesntHave('review')
            ->get()
            ->groupBy('order_id');
            
        $reviews = Review::with(['orderItem.product'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);
            
        // Hitung statistik rating
        $ratingStats = [
            'average_rating' => $reviews->avg('rating') ?? 0,
            'total_reviews' => $reviews->count(),
            'pending_reviews' => $pendingReviews->flatten()->count(),
            'rating_5' => $reviews->where('rating', 5)->count(),
            'rating_4' => $reviews->where('rating', 4)->count(),
            'rating_3' => $reviews->where('rating', 3)->count(),
            'rating_2' => $reviews->where('rating', 2)->count(),
            'rating_1' => $reviews->where('rating', 1)->count(),
        ];
        
        return view('user.review.index', compact('reviews', 'pendingReviews', 'ratingStats'));
    }
    
    public function create($orderItemId)
    {
        $orderItem = OrderItem::with(['order', 'product'])
            ->where('id', $orderItemId)
            ->whereHas('order', function($query) {
                $query->where('user_id', Auth::id())
                      ->where('status', 'delivered');
            })
            ->whereDoesntHave('review')
            ->firstOrFail();
            
        return view('user.review.create', compact('orderItem'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'order_item_id' => 'required|exists:order_items,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $orderItem = OrderItem::findOrFail($request->order_item_id);
        
        // Pastikan user adalah pemilik order dan order sudah diterima
        if ($orderItem->order->user_id != Auth::id() || $orderItem->order->status != 'delivered') {
            return redirect()->back()->with('error', 'Anda tidak dapat mengulas produk ini.');
        }
        
        // Cek apakah sudah ada ulasan
        if ($orderItem->review) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }
        
        // Simpan ulasan
        $review = new Review();
        $review->order_item_id = $orderItem->id;
        $review->user_id = Auth::id();
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();
        
        // Upload gambar jika ada
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                $review->images()->create(['image_path' => $path]);
            }
        }
        
        return redirect()->route('review.show', $review->id)->with('success', 'Ulasan berhasil dikirim!');
    }
    
    public function show($id)
    {
        $review = Review::with(['orderItem.product', 'images'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        return view('user.review.show', compact('review'));
    }

    public function markHelpful(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        
        if ($request->is_helpful) {
            $review->increment('helpful_count');
            return response()->json([
                'success' => true,
                'message' => 'Terima kasih atas feedback Anda!',
                'helpful_count' => $review->helpful_count
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Terima kasih atas feedback Anda!'
        ]);
    }
}