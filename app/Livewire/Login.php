<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Login extends Component
{
    #[Validate('required|email')]
    public $email = '';
    
    #[Validate('required')]
    public $password = '';
 
    public function login()
    {
        // Validate the form inputs
        $this->validate();
        
        // Prepare credentials for authentication
        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];
        
        // Attempt to authenticate user
        if (Auth::attempt($credentials)) {
            // Regenerate the session to prevent fixation attacks
            request()->session()->regenerate();
            
            // Redirect to the intended route or a default route
            // session()->flash('success', 'You are now logged in!');
            return $this->redirect('/sensor-data', navigate: true);
        }
        
        // If authentication fails, throw a validation exception
        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    
    public function render()
    {
        return view('livewire.login');
    }
}
