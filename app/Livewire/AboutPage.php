<?php

namespace App\Livewire;

use App\Models\AboutPage as AboutModel;
use Livewire\Component;

class AboutPage extends Component
{
    public function render()
    {
        $about = AboutModel::first();
        return view('livewire.about-page', compact('about'));
    }
}