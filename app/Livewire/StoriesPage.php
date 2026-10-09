<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination; // Agar bisa halaman 1, 2, 3

class StoriesPage extends Component
{
    use WithPagination;

    public function render()
    {
        // Ambil artikel yang sudah terbit (published_at tidak kosong)
        // Urutkan dari yang paling baru
        $articles = Article::whereNotNull('published_at')
            ->latest('published_at')
            ->paginate(9); // Tampilkan 9 per halaman

        return view('livewire.stories-page', [
            'articles' => $articles
        ]);
    }
}