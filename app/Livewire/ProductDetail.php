<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\CartService; // Import CartService
use Livewire\Component;

class ProductDetail extends Component
{
    public $product;
    public $selectedVariantId;
    public $price;
    public $quantity = 1;
    public $stock = 0;

    public function mount($slug)
    {
        $this->product = Product::with(['variants', 'category', 'reviews.user'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Set default ke varian pertama
        $firstVariant = $this->product->variants->first();
        if ($firstVariant) {
            $this->selectedVariantId = $firstVariant->id;
            $this->price = $firstVariant->price;
            $this->stock = $firstVariant->stock;
        } else {
            $this->price = $this->product->base_price;
            $this->stock = 0;
        }
    }

    // Update harga saat varian diganti
    public function updatedSelectedVariantId($value)
    {
        $variant = $this->product->variants->find($value);
        if ($variant) {
            $this->price = $variant->price;
            $this->stock = $variant->stock;
        }
    }

    public function addToCart(CartService $cart)
    {
        // Panggil service
        $result = $cart->add($this->product->id, $this->quantity, $this->selectedVariantId);
        
        if ($result === 'out_of_stock') {
            // Kirim notifikasi error (Flash Message)
            session()->flash('error', 'Maaf, stok varian ini tidak mencukupi!');
            return;
        }

        // Jika sukses
        $this->dispatch('cart-updated'); 
        $this->dispatch('add-to-cart-success'); // Opsional buat trigger animasi
    }

    public function render()
    {
        return view('livewire.product-detail');
    }
}