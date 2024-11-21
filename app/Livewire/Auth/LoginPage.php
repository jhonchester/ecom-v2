<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;
#[Title('Login')]

class LoginPage extends Component
{
    public $email;
    public $password;
    public function save(){
        // Validate input fields before attempting login
        $this->validate([
            'email' => 'required|email|exists:users,email|max:255',
            'password' => 'required|min:6|max:255',
        ]);
    
    
        // Attempt login
        if (!auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->flash('error', 'Invalid Credentials');
            return;
        }
    
        // Redirect if login is successful
        return redirect()->intended();
    }
    
    
    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
