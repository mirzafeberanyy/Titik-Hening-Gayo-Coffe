<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\OrderPlaced; // Pastikan ini ada
use Illuminate\Support\Facades\Mail; // Pastikan ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;

class PaymentCallbackController extends Controller
{
    public function receive(Request $request)
    {
        try {
            $serverKey = config('services.midtrans.server_key');
            Config::$serverKey = $serverKey; 
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $payload = $request->getContent();
            $notification = json_decode($payload);

            if (!$notification) {
                return response()->json(['message' => 'Invalid JSON'], 400);
            }

            $transaction = $notification->transaction_status;
            $type = $notification->payment_type;
            $orderId = $notification->order_id;
            $fraud = $notification->fraud_status;
            $grossAmount = $notification->gross_amount;
            $statusCode = $notification->status_code;

            // Validasi Signature Key dari Midtrans
            $calculatedSignature = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);
            if (!isset($notification->signature_key) || $calculatedSignature !== $notification->signature_key) {
                Log::warning("Midtrans Invalid Signature for Order: $orderId");
                return response()->json(['message' => 'Invalid Signature'], 403);
            }

            Log::info("Webhook Masuk: Order $orderId statusnya $transaction");

            $order = Order::with('items.variant', 'items.product')->where('code', $orderId)->first();

            if (!$order) {
                return response()->json(['message' => 'Order not found (Test OK)'], 200);
            }

            // Jika order sudah dibatalkan atau gagal sebelumnya, jangan proses lagi
            if (in_array($order->payment_status, ['failed']) || in_array($order->order_status, ['cancelled'])) {
                return response()->json(['message' => 'Order already failed/cancelled']);
            }

            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $order->update(['payment_status' => 'pending']);
                    } else {
                        $order->update(['payment_status' => 'paid', 'order_status' => 'processing']);
                        $this->sendInvoiceEmail($order);
                    }
                }
            } else if ($transaction == 'settlement') {
                $order->update(['payment_status' => 'paid', 'order_status' => 'processing']);
                $this->sendInvoiceEmail($order);

            } else if ($transaction == 'pending') {
                $order->update(['payment_status' => 'pending']);
                
            } else if (in_array($transaction, ['deny', 'expire', 'cancel'])) {
                $order->update(['payment_status' => 'failed', 'order_status' => 'cancelled']);
                
                // KEMBALIKAN STOK KARENA GAGAL BAYAR
                foreach ($order->items as $item) {
                    if ($item->variant) {
                        $item->variant->increment('stock', $item->quantity);
                    } elseif ($item->product && !$item->product_variant_id) {
                        // Jika tidak pakai varian dan langsung ke product (sebagai jaga-jaga)
                        // Karena di CheckoutPage.php sistem mengharuskan varian, ini mungkin tidak tereksekusi, 
                        // namun disiapkan untuk best practice.
                        $item->product->increment('stock', $item->quantity);
                    }
                }
                Log::info("Stok order $orderId berhasil dikembalikan karena $transaction.");
            }

            return response()->json(['message' => 'Payment status updated']);

        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Fungsi Helper untuk mengirim email invoice
     * Dibuat terpisah agar kodingan di atas rapi
     */
    protected function sendInvoiceEmail($order)
    {
        try {
            // Cek apakah email sudah dikirim sebelumnya (opsional, tapi bagus untuk mencegah spam)
            // Di sini kita kirim saja langsung.
            
            Mail::to($order->customer_email)->send(new OrderPlaced($order));
            
            Log::info("Email Invoice LUNAS berhasil dikirim ke: " . $order->customer_email);
        } catch (\Exception $e) {
            // Jika email gagal, JANGAN biarkan error ini menghentikan respon ke Midtrans
            // Cukup catat di log saja
            Log::error("Gagal kirim email invoice: " . $e->getMessage());
        }
    }
}