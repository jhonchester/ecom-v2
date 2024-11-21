<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Category;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\WithPagination;


#[Title(content: 'Products Page | Spartan Commerce')]
class ProductsPage extends Component
{
    use LivewireAlert;
    use WithPagination;

    #[Url]
    public $selected_categories = [];

    
    #[Url]
    public $featured;

    #[Url]
    public $on_sale;

    #[Url]
    public $price_range = 800;

    #[Url]
    public $sort = 'lastest';


    public function addToCart($product_id){
        $total_count = CartManagement::addItemToCart($product_id);

        // Dispatch event with the total cart count
        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);
        $this->alert('success', 'Product Added to the Cart Successfully!', [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }
    public function render()
    {
        $productQuery = Product::query()->where('is_active', 1); 

        if(!empty($this->selected_categories)){
            $productQuery->whereIn('category_id', $this->selected_categories);
        }

        if ($this->featured){
            $productQuery->where('is_featured', 1);
        }
        if ($this->on_sale){
            $productQuery->where('on_sale', 1);
        }

        if ($this->price_range){
            $productQuery->whereBetween('price', [0, $this->price_range]);
        }

        if ($this->sort == 'latest'){
            $productQuery->latest();
        }
        if ($this->sort == 'price'){
            $productQuery->orderBy('price');
        }

        return view('livewire.products-page', [
            'products' => $productQuery->paginate(6),
            'categories' => Category::where('is_active', 1)->get(['id', 'name', 'slug']),
        ]);
    }
}
