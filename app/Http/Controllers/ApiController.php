<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Check payment status
     */
    public function checkPaymentStatus($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        return response()->json([
            'status' => $order->status,
        ]);
    }
}