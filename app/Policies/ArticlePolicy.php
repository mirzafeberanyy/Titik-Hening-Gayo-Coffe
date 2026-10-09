<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function viewAny(User $user): bool
    {
        // Gudang DILARANG masuk sini
        return $user->role === 'admin' || $user->isKonten();
    }

    public function view(User $user, Article $article): bool
    {
        return $user->role === 'admin' || $user->isKonten();
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->isKonten();
    }

    public function update(User $user, Article $article): bool
    {
        return $user->role === 'admin' || $user->isKonten();
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->role === 'admin'; // Hapus cuma boleh Admin
    }
}