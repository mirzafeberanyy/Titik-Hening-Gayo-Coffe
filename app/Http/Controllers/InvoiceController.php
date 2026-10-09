<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG; // Pastikan baris ini ada

class InvoiceController extends Controller
{
    public function download($orderId)
    {
        // 1. Ambil data order
        $order = Order::with('items.product')->findOrFail($orderId);

        // Authorization check: pastikan hanya pemilik atau admin yang bisa download
        if (!auth()->check() || (auth()->id() !== $order->user_id && auth()->user()->role !== 'admin' && auth()->user()->role !== 'staff')) {
            abort(403, 'Unauthorized access.');
        }

        // 2. Generate Barcode (Ini yang sebelumnya mungkin hilang)
        $generator = new BarcodeGeneratorPNG();
        $barcode = base64_encode($generator->getBarcode($order->code, $generator::TYPE_CODE_128));

        // 3. Load View & KIRIM VARIABLE BARCODE
        // Perhatikan bagian compact() harus ada 'barcode'
        $pdf = Pdf::loadView('pdf.invoice', compact('order', 'barcode'));

        // 4. Set Ukuran Kertas (Resi Thermal)
        $customPaper = [0, 0, 283.465, 425.197]; 
        $pdf->setPaper($customPaper, 'portrait');

        // 5. Download
        return $pdf->stream('Resi-' . $order->code . '.pdf');
    }
}