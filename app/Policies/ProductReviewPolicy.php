<?php

namespace App\Policies;

use App\Models\ProductReview;
use App\Models\User;

class ProductReviewPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->isKonten();
    }

    public function view(User $user, ProductReview $review): bool
    {
        return $user->role === 'admin' || $user->isKonten();
    }

    public function create(User $user): bool
    {
        // Biasanya review dibuat customer, 
        // tapi untuk admin panel kita batasi aja
        return $user->role === 'admin' || $user->isKonten();
    }

    public function update(User $user, ProductReview $review): bool
    {
        return $user->role === 'admin' || $user->isKonten();
    }

    public function delete(User $user, ProductReview $review): bool
    {
        return $user->role === 'admin';
    }
}
