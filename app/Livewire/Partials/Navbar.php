<?php

namespace App\Livewire\Partials;

use App\Helpers\CartManagement;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Cookie;


class Navbar extends Component
{
    public $total_count = 0;
    protected $listeners = ['update-cart-count' => 'updateCartCount'];

    public function mount()
    {
        // Initialize total_count from the cookie
        $cart_items = json_decode(Cookie::get('cart_items'), true) ?: [];
        $this->total_count = array_sum(array_column($cart_items, 'quantity'));
    }

    public function updateCartCount($total_count)
    {
        // Update the cart count dynamically
        $this->total_count = $total_count;
    }

    public function render()
    {
        return view('livewire.partials.navbar');
    }
}
