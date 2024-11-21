<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use Livewire\Component;
use Livewire\Attributes\Title;
#[Title('Categories Page | Spartan Commerce')]
class CheckoutPage extends Component
{
    //address table edit this to user checkout details table
    public $first_name;
    public $last_name;
    public $suffix;
    public $sr_code;
    public $department;
    public $course;
    public $section;
    public $phone_number;
    public $email;
    public $payment_method;
    
    public function mount()
    {
        $this->email = auth()->user()->email ?? '';
    }
    public function placeOrder(){
        //dd($this->first_name, $this->last_name, $this->sr_code, $this->department, $this->course, $this->section, 
        //$this->phone_number, $this->email, $this->payment_method);
        
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'suffix' => 'max:4',
            'sr_code' => 'required',
            'department' => 'required',
            'course' => 'required',
            'section' => 'required|numeric|digits:4',
            'phone_number' => 'required',
            'email' => 'required',
            'payment_method' => 'required',

        ]);
    
        
    }
    
    public function render()
    {
        $cart_items = CartManagement::getCartItemsFromCookie();
        $grand_total = CartManagement::calculateGrandTotal($cart_items);

        return view('livewire.checkout-page',[
            'cart_items' => $cart_items,
            'grand_total' => $grand_total
        ]);
    }
}
