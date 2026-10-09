<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    // Siapa yang boleh melihat daftar order? (Admin & Gudang)
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->isGudang();
    }

    // Siapa yang boleh melihat detail satu order? (Admin & Gudang)
    public function view(User $user, Order $order): bool
    {
        // PERBAIKAN DI SINI: Ganti 'staff' jadi isGudang()
        return $user->role === 'admin' || $user->isGudang();
    }

    // Siapa yang boleh buat order manual di admin? (Hanya Admin)
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    // Siapa yang boleh edit status/resi? (Admin & Gudang)
    public function update(User $user, Order $order): bool
    {
        // PERBAIKAN DI SINI: Ganti 'staff' jadi isGudang()
        return $user->role === 'admin' || $user->isGudang();
    }

    // Siapa yang boleh MENGHAPUS order? (HANYA ADMIN)
    public function delete(User $user, Order $order): bool
    {
        return $user->role === 'admin';
    }
}