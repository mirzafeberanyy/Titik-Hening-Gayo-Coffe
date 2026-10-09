<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;

class StoryDetail extends Component
{
    public $article;

    // Tangkap parameter Slug dari URL
    public function mount($slug)
    {
        $this->article = Article::with('images') // Load galeri juga
            ->where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.story-detail');
    }
}