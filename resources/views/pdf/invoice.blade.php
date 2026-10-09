<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->code }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            font-size: 14px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #D4A574;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #8B4513;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .invoice-info {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .info-left, .info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .info-right {
            text-align: right;
        }
        .info-label {
            font-weight: bold;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background-color: #f8f8f8;
            border-bottom: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            color: #8B4513;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .total-section {
            width: 100%;
            text-align: right;
        }
        .total-row {
            margin-bottom: 5px;
        }
        .grand-total {
            font-size: 18px;
            font-weight: bold;
            color: #8B4513;
            margin-top: 10px;
            border-top: 2px solid #D4A574;
            display: inline-block;
            padding-top: 10px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo">Titik Hening</div>
        <div style="font-size: 12px; color: #777; margin-top: 5px;">Ketenangan dalam Setiap Tegukan</div>
    </div>

    <div class="invoice-info">
        <div class="info-left">
            <div class="info-label">Tagihan Kepada:</div>
            <strong>{{ $order->customer_name }}</strong><br>
            {{ $order->customer_phone }}<br>
            {{ $order->customer_email }}<br>
            <div style="margin-top: 5px; width: 80%;">
                {{ $order->shipping_address }}
            </div>
        </div>
        <div class="info-right">
            <div class="info-label">Nomor Invoice:</div>
            <strong>#{{ $order->code }}</strong><br>
            
            <div class="info-label" style="margin-top: 10px;">Tanggal Pesanan:</div>
            {{ $order->created_at->format('d M Y, H:i') }}<br>
            
            <div class="info-label" style="margin-top: 10px;">Status Pembayaran:</div>

            @if($order->payment_method == 'cod')
                <span style="color: orange; font-weight: bold;">MENUNGGU PEMBAYARAN (COD)</span>
            @else
                <span style="color: {{ $order->payment_status == 'paid' ? 'green' : 'red' }}">
                    {{ strtoupper($order->payment_status) }}
                </span>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Varian</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Harga</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>
                    @if($item->product_variant_id)
                        {{ $item->product->variants->find($item->product_variant_id)->name ?? '-' }}
                    @else
                        -
                    @endif
                </td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">Subtotal: Rp {{ number_format($order->items->sum('total_price'), 0, ',', '.') }}</div>
        <div class="total-row">Ongkos Kirim ({{ $order->shipping_courier }} - {{ $order->shipping_service }}): Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</div>
        <div class="grand-total">
            Total Bayar: Rp {{ number_format($order->total_price, 0, ',', '.') }}
        </div>

        @if($order->payment_method == 'cod')
            <div style="margin-top: 10px; font-size: 12px; color: #8B4513; font-style: italic;">
                * Mohon siapkan uang tunai sejumlah di atas saat kurir tiba.
            </div>
        @endif
    </div>

    <div class="footer">
        <p>Terima kasih telah berbelanja di Titik Hening.</p>
        <p>Silakan hubungi kami via WhatsApp jika ada pertanyaan mengenai pesanan ini.</p>
        <p>www.titikhening.com</p>
    </div>

</body>
</html>