<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class DashboardController extends Controller
{
    public function userDashboard()
    {
        return view('user.dashboard');
    }
    
    public function adminDashboard()
    {
        // Get recent orders (last 5 orders)
        $recentOrders = Order::with(['user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer_name,
                    'formatted_total' => 'Rp ' . number_format($order->total, 0, ',', '.'),
                    'status' => $order->status,
                    'status_color' => $this->getStatusColor($order->status),
                    'status_label' => $this->getStatusLabel($order->status),
                    'created_at' => $order->created_at->diffForHumans()
                ];
            });
        
        // Get top products (most sold)
        $topProducts = Product::with(['sizes'])
            ->withCount(['orderItems as sold_count' => function($query) {
                $query->selectRaw('SUM(quantity) as total_quantity');
            }])
            ->orderBy('sold_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image_url' => $product->image_url,
                    'formatted_price' => $product->formatted_price,
                    'stock' => $product->total_stock,
                    'stock_color' => $product->stock_badge_class,
                    'sold_count' => $product->sold_count,
                    'rating' => $product->average_rating
                ];
            });
        
        // Get recent activities
        $activities = [
            [
                'type' => 'check',
                'color' => 'green',
                'title' => 'Pesanan baru dibuat',
                'description' => 'Pesanan #ORD-' . date('Ymd') . '-' . rand(1000, 9999) . ' telah dibuat',
                'time' => 'Baru saja'
            ],
            [
                'type' => 'sync-alt',
                'color' => 'blue',
                'title' => 'Stok produk diperbarui',
                'description' => 'Stok produk telah diperbarui',
                'time' => '5 menit yang lalu'
            ],
            [
                'type' => 'plus',
                'color' => 'purple',
                'title' => 'Produk baru ditambahkan',
                'description' => 'Produk baru telah ditambahkan ke katalog',
                'time' => '1 jam yang lalu'
            ],
            [
                'type' => 'exclamation-triangle',
                'color' => 'yellow',
                'title' => 'Peringatan stok rendah',
                'description' => 'Beberapa produk hampir habis stok',
                'time' => '3 jam yang lalu'
            ]
        ];
        
        // Get dashboard statistics
        $stats = [
            'total_orders' => Order::count(),
            'total_customers' => User::count(),
            'monthly_revenue' => Order::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total'),
            'cancellation_rate' => Order::where('status', 'cancelled')->count() / max(Order::count(), 1) * 100
        ];
        
        return view('admin.dashboard', compact(
            'recentOrders',
            'topProducts',
            'activities',
            'stats'
        ));
    }
    
    private function getStatusColor($status)
    {
        $colors = [
            'pending_payment' => 'yellow',
            'payment_verification' => 'amber',
            'paid' => 'blue',
            'processing' => 'purple',
            'shipped' => 'indigo',
            'delivered' => 'green',
            'cancelled' => 'red'
        ];
        
        return $colors[$status] ?? 'gray';
    }
    
    private function getStatusLabel($status)
    {
        $labels = [
            'pending_payment' => 'Belum Bayar',
            'payment_verification' => 'Verifikasi Pembayaran',
            'paid' => 'Sudah Bayar',
            'processing' => 'Dikemas',
            'shipped' => 'Dikirim',
            'delivered' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];
        
        return $labels[$status] ?? ucfirst($status);
    }
}