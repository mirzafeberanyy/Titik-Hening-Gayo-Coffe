<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\ProductReview;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCompleted;

class MyOrdersPage extends Component
{
    // Fitur Cancel Order
    public function cancelOrder($orderId)
    {
        $order = Order::where('id', $orderId)
                      ->where('user_id', Auth::id()) // Pastikan punya sendiri
                      ->where('order_status', 'new') // Cuma bisa cancel kalau masih New
                      ->first();

        if ($order) {
            $order->update([
                'order_status' => 'cancelled',
                'payment_status' => 'failed'
            ]);
            
            // Optional: Kembalikan stok produk disini jika mau canggih
            
            session()->flash('success', 'Pesanan berhasil dibatalkan.');
        }
    }

    // PROPERTI UNTUK MODAL REVIEW
    public $reviewModalOpen = false;
    public $selectedProductId = null;
    public $selectedOrderId = null;
    public $rating = 5;
    public $comment = '';

    // Buka Modal
    public function openReviewModal($orderId, $productId)
    {
        $this->selectedOrderId = $orderId;
        $this->selectedProductId = $productId;
        $this->reviewModalOpen = true;
        $this->rating = 5;
        $this->comment = '';
    }

    // Simpan Review
    public function submitReview()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|min:5',
        ]);

        ProductReview::create([
            'user_id' => Auth::id(),
            'order_id' => $this->selectedOrderId,
            'product_id' => $this->selectedProductId,
            'rating' => $this->rating,
            'comment' => $this->comment,
        ]);

        $this->reviewModalOpen = false;
        session()->flash('success', 'Ulasan berhasil dikirim! Terima kasih.');
    }

    public function render()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items.product') // Eager load
            ->latest()
            ->get();

        return view('livewire.my-orders-page', [
            'orders' => $orders
        ]);
    }

    // Fitur Konfirmasi Terima Barang
    public function completeOrder($orderId)
    {
        $order = Order::where('id', $orderId)
                      ->where('user_id', Auth::id())
                      ->where('order_status', 'shipping') // Hanya bisa klik kalau statusnya 'shipping'
                      ->first();

        if ($order) {
            $order->update([
                'order_status' => 'completed',
                'payment_status' => 'paid',
            ]);
            
            // --- KIRIM EMAIL TERIMA KASIH (BARU) ---
            if ($order->customer_email) {
                try {
                    Mail::to($order->customer_email)->send(new OrderCompleted($order));
                } catch (\Exception $e) {
                    // Jika gagal kirim email, biarkan saja (jangan error)
                    // Log error kalau perlu: Log::error($e->getMessage());
                }
            }

            session()->flash('success', 'Terima kasih! Pesanan telah selesai.');
        }
    }

    public function mount()
    {
        // Jika user yang login BUKAN customer, lempar ke dashboard admin
        if (auth()->check() && auth()->user()->role !== 'customer') {
            return redirect('/admin');
        }
    }
}