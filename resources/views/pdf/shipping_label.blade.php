<!DOCTYPE html>
<html>
<head>
    <title>Resi {{ $order->code }}</title>
    <style>
        @page { margin: 0px; }
        body { 
            font-family: sans-serif; 
            margin: 5px;
            /* Border kotak full page minus margin */
            border: 2px solid #000;
            height: 98%; 
        }
        .header {
            border-bottom: 2px solid #000;
            display: table;
            width: 100%;
        }
        .courier-box {
            display: table-cell;
            vertical-align: middle;
            width: 35%;
            border-right: 2px solid #000;
            text-align: center;
            padding: 5px;
        }
        .courier-name {
            font-size: 20px;
            font-weight: 900;
            text-transform: uppercase;
        }
        .courier-service {
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
        }
        .order-code {
            display: table-cell;
            vertical-align: top;
            padding: 10px;
            text-align: center;
        }
        .barcode-text {
            font-size: 10px;
            font-weight: bold;
            margin-top: 2px;
        }
        
        .address-section {
            padding: 10px;
            border-bottom: 1px dashed #000;
        }
        .label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #555;
            margin-bottom: 2px;
        }
        .receiver-name {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .receiver-address {
            font-size: 11px;
            line-height: 1.3;
        }
        .receiver-phone {
            font-size: 11px;
            font-weight: bold;
            margin-top: 4px;
        }

        .sender-section {
            padding: 8px 10px;
            border-bottom: 2px solid #000;
            font-size: 10px;
        }

        .items-section {
            padding: 8px 10px;
        }
        .items-table {
            width: 100%;
            font-size: 10px;
            border-collapse: collapse;
        }
        .items-table td { padding: 2px 0; vertical-align: top; }
        
        .footer {
            position: absolute;
            bottom: 5px;
            left: 0;
            right: 0;
            font-size: 8px;
            text-align: center;
            color: #777;
        }
        
        /* Helpers */
        .badge {
            border: 1px solid #000;
            display: inline-block;
            padding: 2px 4px;
            font-size: 9px;
            font-weight: bold;
            margin-top: 5px;
            background: #eee;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="courier-box">
            <div class="courier-name">{{ $order->shipping_courier ?? 'KURIR' }}</div>
            <div class="courier-service">{{ $order->shipping_service ?? 'REG' }}</div>
            
            @if($order->payment_status == 'paid')
                <div class="badge">NON-COD (LUNAS)</div>
            @else
                <div class="badge" style="background: #000; color: #fff;">COD: Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
            @endif
        </div>

        <div class="order-code">
            <img src="data:image/png;base64,{{ $barcode }}" height="35" width="160">
            
            <div style="font-size: 14px; font-weight: bold; margin-top: 2px;">{{ $order->code }}</div>
            
            <div class="barcode-text">
                Berat: {{ number_format($order->total_weight/1000, 1) }} Kg
            </div>

            @if($order->tracking_number)
                <div style="margin-top: 5px; border-top: 1px dotted #000; padding-top: 3px;">
                    <div style="font-size: 8px;">No. Resi:</div>
                    <div style="font-size: 12px; font-weight: 900; letter-spacing: 1px;">
                        {{ $order->tracking_number }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="address-section">
        <div class="label">Penerima:</div>
        <div class="receiver-name">{{ $order->customer_name }}</div>
        <div class="receiver-address">{{ $order->shipping_address }}</div>
        <div class="receiver-phone">Telp: {{ $order->customer_phone }}</div>
    </div>

    <div class="sender-section">
        <table width="100%">
            <tr>
                <td width="15%" class="label" style="vertical-align: top;">Pengirim:</td>
                <td>
                    <strong style="text-transform: uppercase;">Titik Hening Store</strong><br>
                    0812-3456-7890<br>
                </td>
            </tr>
        </table>
    </div>

    <div class="items-section">
        <div class="label" style="margin-bottom: 3px; border-bottom: 1px solid #ddd;">Isi Paket:</div>
        <table class="items-table">
            @foreach($order->items as $item)
            <tr>
                <td width="10%" style="font-weight: bold;">{{ $item->quantity }}x</td>
                <td>
                    {{ $item->product->name }}
                    @if($item->product_variant_id)
                         <br><small class="text-gray-500">({{ $item->product->variants->where('id', $item->product_variant_id)->first()->name ?? 'Varian' }})</small>
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
    </div>

    <div class="footer">
        Dicetak: {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>