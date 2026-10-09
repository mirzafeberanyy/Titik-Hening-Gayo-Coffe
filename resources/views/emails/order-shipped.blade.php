<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; margin-top: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background-color: #1a1a1a; padding: 20px; text-align: center; color: #d4a574; }
        .content { padding: 30px; color: #333333; line-height: 1.6; }
        .tracking-box { background-color: #f9fafb; border: 1px dashed #d4a574; padding: 15px; border-radius: 6px; margin: 20px 0; text-align: center; }
        .btn { display: inline-block; background-color: #d4a574; color: #1a1a1a; padding: 12px 24px; text-decoration: none; font-weight: bold; border-radius: 4px; margin-top: 10px; }
        .footer { background-color: #f4f4f4; padding: 20px; text-align: center; font-size: 12px; color: #888888; }
        .item-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .item-table th { text-align: left; border-bottom: 2px solid #eee; padding: 10px; font-size: 12px; text-transform: uppercase; color: #888; }
        .item-table td { border-bottom: 1px solid #eee; padding: 10px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0; font-family: serif; font-size: 24px;">Titik Hening.</h1>
        </div>
        
        <div class="content">
            <h2 style="color: #1a1a1a; margin-top: 0;">Paket Sedang Meluncur!</h2>
            <p>Halo <strong>{{ $order->customer_name }}</strong>,</p>
            <p>Terima kasih sudah bersabar. Kopi pesanan Anda sudah kami serahkan ke kurir dan sedang dalam perjalanan menuju alamat Anda.</p>
            
            <div class="tracking-box">
                <p style="margin: 0 0 5px 0; font-size: 12px; color: #666; text-transform: uppercase;">Nomor Resi ({{ strtoupper($order->shipping_courier) }})</p>
                <p style="margin: 0; font-size: 20px; font-weight: bold; letter-spacing: 2px; font-family: monospace;">
                    {{ $order->tracking_number ?? 'Resi sedang diupdate' }}
                </p>
            </div>

            <p style="text-align: center;">
                <a href="{{ route('my-orders') }}" class="btn">Lacak Pesanan Saya</a>
            </p>

            <h3>Rincian Barang:</h3>
            <table class="item-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Jml</th>
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Titik Hening Coffee.<br>Takengon, Aceh Tengah.</p>
        </div>
    </div>
</body>
</html>