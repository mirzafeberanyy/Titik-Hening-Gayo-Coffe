<!DOCTYPE html>
<html>
<head>
    <style>
        /* CSS SAMA PERSIS DENGAN EMAIL SHIPPING AGAR KONSISTEN */
        body { font-family: sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; margin-top: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background-color: #1a1a1a; padding: 20px; text-align: center; color: #d4a574; }
        .content { padding: 30px; color: #333333; line-height: 1.6; }
        
        /* Box Status (Mirip Tracking Box tapi untuk Payment) */
        .info-box { background-color: #f9fafb; border: 1px dashed #d4a574; padding: 15px; border-radius: 6px; margin: 20px 0; text-align: center; }
        
        .btn { display: inline-block; background-color: #d4a574; color: #1a1a1a; padding: 12px 24px; text-decoration: none; font-weight: bold; border-radius: 4px; margin-top: 10px; }
        .footer { background-color: #f4f4f4; padding: 20px; text-align: center; font-size: 12px; color: #888888; }
        .item-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .item-table th { text-align: left; border-bottom: 2px solid #eee; padding: 10px; font-size: 12px; text-transform: uppercase; color: #888; }
        .item-table td { border-bottom: 1px solid #eee; padding: 10px; font-size: 14px; }
        
        /* Tambahan khusus status */
        .badge-success { color: #059669; font-weight: bold; text-transform: uppercase; }
        .badge-warning { color: #d97706; font-weight: bold; text-transform: uppercase; }
        .badge-danger { color: #dc2626; font-weight: bold; text-transform: uppercase; }
        .cod-note { font-size: 12px; color: #d97706; margin-top: 5px; font-style: italic; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0; font-family: serif; font-size: 24px;">Titik Hening.</h1>
        </div>
        
        <div class="content">
            <h2 style="color: #1a1a1a; margin-top: 0;">Terima Kasih!</h2>
            <p>Halo <strong>{{ $order->customer_name }}</strong>,</p>
            <p>Pesanan Anda dengan nomor <strong>#{{ $order->code }}</strong> telah berhasil masuk ke sistem kami.</p>
            
            <p style="font-size: 13px; color: #666;">
                <strong>Invoice PDF</strong> telah kami lampirkan di Attachment email ini sebagai bukti transaksi yang sah.
            </p>

            <div class="info-box">
                <p style="margin: 0 0 5px 0; font-size: 12px; color: #666; text-transform: uppercase;">Total Pembayaran</p>
                <p style="margin: 0 0 10px 0; font-size: 24px; font-weight: bold; color: #1a1a1a;">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </p>
                
                <div style="border-top: 1px solid #eee; padding-top: 10px; margin-top: 10px;">
                    <span style="font-size: 12px; color: #888;">STATUS PEMBAYARAN:</span><br>
                    
                    @if($order->payment_method == 'cod')
                        <span class="badge-warning">Menunggu Pembayaran (COD)</span>
                        <div class="cod-note">* Siapkan uang tunai pas saat kurir tiba.</div>
                    @elseif($order->payment_status == 'paid')
                        <span class="badge-success">LUNAS (PAID)</span>
                    @else
                        <span class="badge-danger">{{ strtoupper($order->payment_status) }}</span>
                    @endif
                </div>
            </div>

            <p style="text-align: center;">
                <a href="{{ route('my-orders') }}" class="btn">Lihat Pesanan Saya</a>
            </p>

            <h3>Rincian Pesanan:</h3>
            <table class="item-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Jml</th>
                        <th style="text-align: right;">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong style="display:block; color:#1a1a1a;">{{ $item->product->name }}</strong>
                            <span style="font-size:12px; color:#666;">
                                {{ $item->product_variant ? $item->product_variant->name : '' }}
                            </span>
                        </td>
                        <td>x{{ $item->quantity }}</td>
                        <td style="text-align: right;">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Titik Hening Store.<br>Takengon, Aceh Tengah.</p>
        </div>
    </div>
</body>
</html>