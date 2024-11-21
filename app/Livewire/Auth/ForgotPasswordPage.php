<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;
use Livewire\Attributes\Title;


#[Title('Forgot Password')]
class ForgotPasswordPage extends Component
{
    public $email;
    public function save(){
        $this->validate([
            'email' => 'required|email|exists:users,email|max:255',  // Corrected validation rule
        ]);
    
        $status = Password::sendResetLink(['email' => $this->email]);
    
        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('success', 'Password reset link has been sent to your email address!');
            $this->email = '';  // Clear the email field after sending the reset link
        } else {
            session()->flash('error', 'There was an error sending the reset link. Please try again.');
        }
    }
    public function render()
    {
        return view('livewire.auth.forgot-password-page');
    }
}
