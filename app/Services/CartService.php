<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    // Kunci untuk menyimpan data di Session
    const SESSION_KEY = 'shopping_cart';

    // Ambil semua item di keranjang
    public function getCart()
    {
        return Session::get(self::SESSION_KEY, []);
    }

    // Tambah produk ke keranjang
    public function add($productId, $qty = 1, $variantId = null)
    {
        $cart = $this->getCart();
        $product = Product::find($productId);
        
        // Ambil data varian
        if ($variantId) {
            $variant = \App\Models\ProductVariant::find($variantId);
        } else {
            $variant = $product->variants->first();
        }

        if (!$product || !$variant) return false;

        // --- VALIDASI STOK BARU ---
        // Cek stok yang tersedia di database
        if ($variant->stock < $qty) {
            // Kita bisa return false atau throw exception agar UI bisa menangkap error
            // Untuk simple-nya kita return 'out_of_stock' string
            return 'out_of_stock';
        }
        
        // Cek juga: Stok di keranjang + qty baru apakah melebihi stok database?
        $rowId = $productId . '_' . $variant->id;
        $currentQtyInCart = isset($cart[$rowId]) ? $cart[$rowId]['quantity'] : 0;
        
        if (($currentQtyInCart + $qty) > $variant->stock) {
            return 'out_of_stock';
        }
        // --------------------------

        // Logic Add biasa...
        if (isset($cart[$rowId])) {
            $cart[$rowId]['quantity'] += $qty;
        } else {
            $cart[$rowId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $variant->price,
                'variant_id' => $variant->id,
                'variant_name' => $variant->name,
                'weight' => $variant->weight_grams,
                'image' => $product->main_image,
                'quantity' => $qty,
                'category' => $product->category->name ?? 'General'
            ];
        }

        Session::put(self::SESSION_KEY, $cart);
        return true;
    }

    // Update jumlah qty (tambah/kurang di sidebar)
    public function updateQty($productId, $qty)
    {
        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $qty;
            
            // Jika 0 atau minus, hapus item
            if ($cart[$productId]['quantity'] <= 0) {
                unset($cart[$productId]);
            }
            
            Session::put(self::SESSION_KEY, $cart);
        }
    }

    // Hapus item dari keranjang
    public function remove($productId)
    {
        $cart = $this->getCart();
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put(self::SESSION_KEY, $cart);
        }
    }

    // Hitung Total Harga
    public function getTotal()
    {
        $cart = $this->getCart();
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }

    // Hitung Total Item (untuk Badge Merah di Navbar)
    public function count()
    {
        return count($this->getCart());
    }
    
    // Kosongkan keranjang (setelah Checkout)
    public function clear()
    {
        Session::forget(self::SESSION_KEY);
    }

    public function getTotalWeight()
    {
        $cart = $this->getCart();
        $totalWeight = 0;

        foreach ($cart as $item) {
            $weight = $item['weight'] ?? 250; 
            $totalWeight += $weight * $item['quantity'];
        }

        return $totalWeight;
    }
}