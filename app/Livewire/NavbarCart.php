<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class NavbarCart extends Component
{
    public $count = 0;

    // Saat komponen dimuat pertama kali
    public function mount(CartService $cart)
    {
        $this->count = $cart->count();
    }

    // Listener: Setiap kali ada event 'cart-updated', jalankan fungsi ini
    #[On('cart-updated')] 
    public function updateCartCount(CartService $cart)
    {
        $this->count = $cart->count();
    }

    public function render()
    {
        return view('livewire.navbar-cart');
    }
}