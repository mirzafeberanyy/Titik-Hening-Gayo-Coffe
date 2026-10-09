<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class CartSidebar extends Component
{
    public $cartItems = [];
    public $total = 0;

    public function mount(CartService $cartService)
    {
        $this->loadCart($cartService);
    }

    #[On('cart-updated')] // Dengarkan event jika ada perubahan keranjang
    public function refreshCart(CartService $cartService)
    {
        $this->loadCart($cartService);
    }

    #[On('add-to-cart')] // Listener khusus saat tombol Add to Cart diklik
    public function addToCart(CartService $cartService, $productId, $variantId = null)
    {
        $cartService->add($productId, 1, $variantId);
        $this->dispatch('cart-updated'); // Kabari NavbarCart untuk update angka
        $this->loadCart($cartService);
        $this->dispatch('open-cart-sidebar'); // Paksa sidebar terbuka
    }

    public function increment(CartService $cartService, $productId)
    {
        $currentQty = $this->cartItems[$productId]['quantity'];
        $cartService->updateQty($productId, $currentQty + 1);
        $this->refreshCart($cartService);
    }

    public function decrement(CartService $cartService, $productId)
    {
        $currentQty = $this->cartItems[$productId]['quantity'];
        $cartService->updateQty($productId, $currentQty - 1);
        $this->refreshCart($cartService); // Jika 0 otomatis terhapus di service
        $this->dispatch('cart-updated');
    }

    public function remove(CartService $cartService, $productId)
    {
        $cartService->remove($productId);
        $this->refreshCart($cartService);
        $this->dispatch('cart-updated');
    }

    private function loadCart(CartService $cartService)
    {
        $this->cartItems = $cartService->getCart();
        $this->total = $cartService->getTotal();
    }

    public function render()
    {
        return view('livewire.cart-sidebar');
    }
}