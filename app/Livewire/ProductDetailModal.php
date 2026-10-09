<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On;

class ProductDetailModal extends Component
{
    public $isOpen = false;
    public $product = null;
    public $selectedVariantId;
    public $price;
    public $stock = 0;

    #[On('open-product-modal')] // Listener Event
    public function openModal($slug)
    {
        $this->product = Product::with(['variants', 'reviews.user'])->where('slug', $slug)->first();

        if ($this->product) {
            $this->isOpen = true;

            // Set default variant & price
            $firstVariant = $this->product->variants->first();
            if ($firstVariant) {
                $this->selectedVariantId = $firstVariant->id;
                $this->price = $firstVariant->price;
                $this->stock = $firstVariant->stock;
            } else {
                $this->selectedVariantId = null;
                $this->price = $this->product->base_price;
                $this->stock = 0;
            }
        }
    }

    public function updatedSelectedVariantId($value)
    {
        if ($this->product) {
            $variant = $this->product->variants->find($value);
            if ($variant) {
                $this->price = $variant->price;
                $this->stock = $variant->stock;
            }
        }
    }

    public function addToCart()
    {
        if ($this->product) {
            $this->dispatch('add-to-cart', productId: $this->product->id, variantId: $this->selectedVariantId);
            $this->isOpen = false; // Tutup modal setelah add

            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'Kopi telah ditambahkan ke keranjang.',
                'icon' => 'success',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.product-detail-modal');
    }
}