<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Show e-wallet payment page
     */
    public function eWallet($orderId, $paymentMethod)
    {
        try {
            $order = Order::with('orderItems')->findOrFail($orderId);
            
            // Validate payment method
            if (!in_array($paymentMethod, ['dana', 'ovo', 'gopay'])) {
                return redirect()->back()->with('error', 'Metode pembayaran tidak valid');
            }
            
            // Check if order is still pending payment
            if ($order->status !== 'pending_payment') {
                return redirect()->route('order.show', $order->id)
                    ->with('info', 'Pesanan ini sudah diproses atau telah dibayar');
            }
            
            // Generate transaction ID
            $transactionId = 'EWAL-' . date('YmdHis') . '-' . strtoupper(Str::random(8));
            
            // Create or update payment record
            $payment = Payment::updateOrCreate(
                ['order_id' => $orderId],
                [
                    'payment_method' => $paymentMethod,
                    'amount' => $order->total,
                    'status' => 'pending',
                    'transaction_id' => $transactionId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
            
            return view('user.payment.ewallet', compact('order', 'paymentMethod', 'transactionId'));
            
        } catch (\Exception $e) {
            Log::error('E-wallet payment page error', [
                'order_id' => $orderId,
                'payment_method' => $paymentMethod,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pembayaran');
        }
    }
    
    /**
     * Process e-wallet payment callback
     */
    public function eWalletCallback(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|string',
            'transaction_id' => 'required|string',
            'status' => 'required|string'
        ]);
        
        try {
            DB::beginTransaction();
            
            $orderId = $request->input('order_id');
            $paymentMethod = $request->input('payment_method');
            $transactionId = $request->input('transaction_id');
            $status = $request->input('status');
            
            $order = Order::findOrFail($orderId);
            $payment = Payment::where('order_id', $orderId)
                             ->where('transaction_id', $transactionId)
                             ->first();
            
            if (!$payment) {
                throw new \Exception('Payment record tidak ditemukan');
            }
            
            if ($status === 'success') {
                // Update order status
                $order->update([
                    'status' => 'paid',
                    'payment_date' => now()
                ]);
                
                // Update payment record
                $payment->update([
                    'status' => 'success',
                    'payment_date' => now(),
                    'payment_response' => json_encode($request->all())
                ]);
                
                DB::commit();
                
                Log::info('E-wallet payment success', [
                    'order_id' => $orderId,
                    'transaction_id' => $transactionId,
                    'payment_method' => $paymentMethod
                ]);
                
                return redirect()->route('order.success', $orderId);
            } else {
                // Payment failed
                $payment->update([
                    'status' => 'failed',
                    'payment_response' => json_encode($request->all())
                ]);
                
                DB::commit();
                
                Log::warning('E-wallet payment failed', [
                    'order_id' => $orderId,
                    'transaction_id' => $transactionId,
                    'payment_method' => $paymentMethod
                ]);
                
                return redirect()->route('order.failed', ['order_id' => $orderId]);
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('E-wallet callback error', [
                'order_id' => $request->input('order_id'),
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);
            
            return redirect()->route('order.failed', ['order_id' => $request->input('order_id')])
                ->with('error', 'Terjadi kesalahan dalam pemrosesan pembayaran');
        }
    }
    
    /**
     * Simulate e-wallet payment (untuk testing)
     */
    public function simulateEWalletPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|string',
            'transaction_id' => 'required|string'
        ]);
        
        try {
            DB::beginTransaction();
            
            $orderId = $request->input('order_id');
            $paymentMethod = $request->input('payment_method');
            $transactionId = $request->input('transaction_id');
            
            $order = Order::findOrFail($orderId);
            $payment = Payment::where('order_id', $orderId)
                             ->where('transaction_id', $transactionId)
                             ->first();
            
            if (!$payment) {
                throw new \Exception('Payment record tidak ditemukan');
            }
            
            // Simulate payment processing delay
            sleep(2);
            
            // Simulate 90% success rate untuk demo
            $success = rand(0, 10) > 1;
            
            if ($success) {
                // Update order status
                $order->update([
                    'status' => 'paid',
                    'payment_date' => now()
                ]);
                
                // Update payment record
                $payment->update([
                    'status' => 'success',
                    'payment_date' => now(),
                    'payment_response' => json_encode([
                        'status' => 'success',
                        'simulation' => true,
                        'processed_at' => now()
                    ])
                ]);
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil diproses',
                    'redirect_url' => route('order.success', $orderId)
                ]);
            } else {
                // Update payment record untuk failed payment
                $payment->update([
                    'status' => 'failed',
                    'payment_response' => json_encode([
                        'status' => 'failed',
                        'simulation' => true,
                        'processed_at' => now()
                    ])
                ]);
                
                DB::commit();
                
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran gagal, silakan coba lagi',
                    'redirect_url' => route('order.failed', ['order_id' => $orderId])
                ]);
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Simulate e-wallet payment error', [
                'order_id' => $request->input('order_id'),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan dalam simulasi pembayaran'
            ], 500);
        }
    }

    /**
     * Upload payment receipt (IMPROVED)
     */
    public function uploadReceipt(Request $request)
    {
        $request->validate([
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|string|in:dana,ovo,gopay,bank_transfer',
            'transaction_id' => 'nullable|string|max:100',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Get order
            $order = Order::findOrFail($request->order_id);
            
            // Validate order status
            if (!in_array($order->status, ['pending_payment', 'payment_verification'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan ini tidak dapat menerima bukti pembayaran lagi'
                ], 400);
            }
            
            // Store receipt file
            if ($request->hasFile('receipt')) {
                $file = $request->file('receipt');
                
                // Generate unique filename
                $fileName = 'payment_' . $order->order_number . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Store in payment_proofs directory
                $filePath = $file->storeAs('payment_proofs', $fileName, 'public');
                
                if (!$filePath) {
                    throw new \Exception('Gagal menyimpan file bukti pembayaran');
                }
                
                // Update order with payment proof
                $order->update([
                    'payment_proof' => $filePath,
                    'status' => 'payment_verification',
                ]);
                
                // Create or update payment record
                $payment = Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'payment_method' => $request->payment_method,
                        'amount' => $order->total,
                        'status' => 'pending_verification',
                        'transaction_id' => $request->transaction_id ?? 'MANUAL-' . time(),
                        'receipt_path' => $filePath,
                        'payment_response' => json_encode([
                            'status' => 'pending_verification',
                            'receipt_uploaded' => true,
                            'upload_time' => now(),
                            'file_name' => $fileName,
                            'file_size' => $file->getSize()
                        ])
                    ]
                );
                
                Log::info('Payment receipt uploaded', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'payment_method' => $request->payment_method,
                    'file_path' => $filePath,
                    'file_size' => $file->getSize()
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil diupload dan sedang diverifikasi',
                'data' => [
                    'order_id' => $order->id,
                    'status' => 'payment_verification',
                    'upload_time' => now()->format('Y-m-d H:i:s')
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Upload receipt error', [
                'order_id' => $request->order_id,
                'payment_method' => $request->payment_method,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload bukti pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Check payment status
     */
    public function checkPaymentStatus($orderId)
    {
        try {
            $order = Order::with('payment')->findOrFail($orderId);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'payment_method' => $order->payment_method,
                    'payment_status' => $order->payment ? $order->payment->status : null,
                    'payment_date' => $order->payment_date,
                    'total' => $order->total,
                    'formatted_total' => $order->formatted_total
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Check payment status error', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengecek status pembayaran'
            ], 500);
        }
    }
    
    /**
     * Cancel payment
     */
    public function cancelPayment(Request $request, $orderId)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);
        
        try {
            DB::beginTransaction();
            
            $order = Order::findOrFail($orderId);
            
            // Only allow cancellation if order is pending payment
            if (!in_array($order->status, ['pending_payment', 'payment_verification'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan ini tidak dapat dibatalkan'
                ], 400);
            }
            
            // Update order status
            $order->update([
                'status' => 'cancelled',
                'admin_notes' => 'Dibatalkan oleh customer. Alasan: ' . ($request->reason ?? 'Tidak disebutkan')
            ]);
            
            // Update payment if exists
            if ($order->payment) {
                $order->payment->update([
                    'status' => 'cancelled'
                ]);
            }
            
            // Restore stock
            foreach ($order->orderItems as $item) {
                if ($item->size_id && $item->productSize) {
                    $item->productSize->increment('stock', $item->quantity);
                } elseif ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
            
            DB::commit();
            
            Log::info('Payment cancelled by user', [
                'order_id' => $orderId,
                'reason' => $request->reason,
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibatalkan'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Cancel payment error', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan pesanan'
            ], 500);
        }
    }
}