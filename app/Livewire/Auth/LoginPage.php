<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LoginPage extends Component
{
    public $email;
    public $password;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba Login
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            
            // --- LOGIC BARU: CEK ROLE ---
            // Jika role BUKAN customer, tendang keluar!
            if (auth()->user()->role !== 'customer') {
                Auth::logout(); // Logout paksa
                session()->invalidate();
                session()->regenerateToken();
                
                // Kasih pesan error
                $this->addError('email', 'Akun Staff/Admin dilarang login disini. Silakan ke /admin');
                return;
            }
            // -----------------------------

            session()->regenerate();
            return redirect()->intended('/'); 
        }

        $this->addError('email', 'Email atau password salah.');
    }

    public function render()
    {
        return view('livewire.auth.login-page');
    }
}