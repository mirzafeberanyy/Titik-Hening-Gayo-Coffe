<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        // Hanya Admin & Gudang yang boleh lihat produk
        return $user->role === 'admin' || $user->isGudang();
    }

    public function view(User $user, Product $product): bool
    {
        return $user->role === 'admin' || $user->isGudang();
    }

    public function create(User $user): bool
    {
        // Membuat produk baru cuma boleh Admin (atau Gudang, terserah Anda)
        return $user->role === 'admin' || $user->isGudang();
    }

    public function update(User $user, Product $product): bool
    {
        return $user->role === 'admin' || $user->isGudang();
    }

    public function delete(User $user, Product $product): bool
    {
        // Hapus cuma boleh Admin
        return $user->role === 'admin';
    }
}