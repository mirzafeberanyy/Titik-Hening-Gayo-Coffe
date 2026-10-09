<?php

namespace App\Policies;

use App\Models\BrandFeature;
use App\Models\User;

class BrandFeaturePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->isKonten();
    }

    public function view(User $user, BrandFeature $brandFeature): bool
    {
        return $user->role === 'admin' || $user->isKonten();
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->isKonten();
    }

    public function update(User $user, BrandFeature $brandFeature): bool
    {
        return $user->role === 'admin' || $user->isKonten();
    }

    public function delete(User $user, BrandFeature $brandFeature): bool
    {
        return $user->role === 'admin';
    }
}
