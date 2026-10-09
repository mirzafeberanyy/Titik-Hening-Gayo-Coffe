<?php

namespace App\Policies;

use App\Models\HeroSetting;
use App\Models\User;

class HeroSettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, HeroSetting $hero): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, HeroSetting $hero): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, HeroSetting $hero): bool
    {
        return $user->isAdmin();
    }
}
