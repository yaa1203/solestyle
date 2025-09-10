<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->order_number }} - SoleStyle</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 20px;
            background: #fff;
            color: #333;
        }
        .invoice-box {
            max-width: 900px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0,0,0,.15);
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 0;
            color: #6d28d9;
        }
        .header img {
            max-height: 50px;
        }
        .details, .summary {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table td, table th {
            border: 1px solid #ddd;
            padding: 8px;
        }
        table th {
            background: #6d28d9;
            color: white;
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .total {
            font-weight: bold;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 30px;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 12px;
            color: white;
        }
        .badge-green { background: #16a34a; }
        .badge-red { background: #dc2626; }
        .badge-yellow { background: #ca8a04; }
        .badge-blue { background: #2563eb; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <!-- Header -->
        <div class="header">
            <div>
                <h2>SoleStyle</h2>
                <p>Invoice #{{ $order->order_number }}</p>
                <p>Tanggal: {{ $order->order_date->format('d M Y, H:i') }}</p>
            </div>
            <div>
                <img src="{{ asset('images/logo.png') }}" alt="SoleStyle">
            </div>
        </div>

        <!-- Customer & Shipping Info -->
        <div class="details">
            <h3>Detail Pelanggan</h3>
            <p><strong>{{ $order->customer_name }}</strong><br>
               {{ $order->customer_email }}<br>
               {{ $order->customer_phone }}</p>

            <h3>Alamat Pengiriman</h3>
            <p>{{ $order->shipping_address }}<br>
               {{ $order->city }}, {{ $order->province }} {{ $order->postal_code }}</p>
        </div>

        <!-- Order Items -->
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Size</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->size_display }}</td>
                    <td>{{ $item->formatted_price }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-right">{{ $item->formatted_subtotal }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary">
            <table>
                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">{{ $order->formatted_subtotal }}</td>
                </tr>
                @if($order->shipping_cost > 0)
                <tr>
                    <td>Ongkos Kirim</td>
                    <td class="text-right">{{ $order->formatted_shipping_cost }}</td>
                </tr>
                @endif
                @if($order->promo_discount > 0)
                <tr>
                    <td>Diskon Promo</td>
                    <td class="text-right">-{{ $order->formatted_promo_discount }}</td>
                </tr>
                @endif
                <tr class="total">
                    <td>Total</td>
                    <td class="text-right">{{ $order->formatted_total }}</td>
                </tr>
            </table>
        </div>

        <!-- Payment & Status -->
        <p><strong>Metode Pembayaran:</strong> {{ $order->payment_method_label }}</p>
        <p><strong>Status:</strong> 
            <span class="badge badge-{{ $order->status_color }}">
                {{ $order->status_label }}
            </span>
        </p>

        <div class="footer">
            <p>Terima kasih telah berbelanja di SoleStyle ❤️</p>
            <p>Invoice ini sah dan dibuat secara otomatis.</p>
        </div>
    </div>
</body>
</html>
