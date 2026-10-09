<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Layout;

class OrderSuccess extends Component
{
    public $order;

    // Menangkap parameter 'code' dari URL
    public function mount($code)
    {
        // Cari data order berdasarkan code
        // Pastikan snap_token juga terambil
        $this->order = Order::where('code', $code)
            ->where('user_id', auth()->id()) // Pastikan hanya pemilik order yang bisa lihat
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.order-success');
    }
}