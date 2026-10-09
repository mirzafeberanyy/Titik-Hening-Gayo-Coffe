<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; margin-top: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background-color: #1a1a1a; padding: 30px 20px; text-align: center; color: #d4a574; }
        .content { padding: 30px; color: #333333; line-height: 1.6; text-align: center; }
        
        /* Tombol Review */
        .btn { display: inline-block; background-color: #d4a574; color: #1a1a1a; padding: 12px 24px; text-decoration: none; font-weight: bold; border-radius: 4px; margin-top: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .btn:hover { background-color: #c49665; }

        .footer { background-color: #f4f4f4; padding: 20px; text-align: center; font-size: 12px; color: #888888; }
        
        .quote { font-style: italic; color: #666; margin: 20px 0; border-left: 3px solid #d4a574; padding-left: 15px; display: inline-block; text-align: left; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0; font-family: serif; font-size: 28px;">Terima Kasih.</h1>
            <p style="margin-top: 5px; font-size: 14px; opacity: 0.8;">Momen hening Anda dimulai di sini.</p>
        </div>
        
        <div class="content">
            <h2 style="color: #1a1a1a; margin-top: 0;">Paket Telah Diterima!</h2>
            <p>Halo <strong>{{ $order->customer_name }}</strong>,</p>
            
            <p>Senang sekali mendengar bahwa paket pesanan <strong>#{{ $order->code }}</strong> sudah sampai di tangan Anda dengan selamat.</p>

            <div class="quote">
                "Kopi yang baik akan selalu menemukan penikmatnya. Terima kasih telah memilih Kopi Gayo kami untuk menemani hari Anda."
            </div>

            <p>Kami sangat menghargai dukungan Anda terhadap petani lokal Gayo. Jika Anda puas dengan kopi kami, luangkan waktu sejenak untuk memberikan ulasan bintang 5 ya!</p>

            <a href="{{ route('my-orders') }}" class="btn">Beri Ulasan ★★★★★</a>

            <br><br>
            <p style="font-size: 13px; color: #999;">
                Ada masalah dengan paketnya? Jangan ragu balas email ini, kami siap membantu.
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Titik Hening Store.<br>Takengon, Aceh Tengah.</p>
        </div>
    </div>
</body>
</html>