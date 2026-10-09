<?php
namespace App\Livewire;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ShopPage extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.shop-page', [
            'products' => Product::where('is_active', true)
                ->with(['category', 'variants'])
                ->withCount('variants') 
                ->latest()
                ->paginate(12)
        ]);
    }
}