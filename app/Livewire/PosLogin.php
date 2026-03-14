<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.pos-layout')]
class PosLogin extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;
    public string $loginMode = 'kasir'; // 'kasir' or 'admin'

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function setMode(string $mode): void
    {
        $this->loginMode = $mode;
        $this->reset(['email', 'password']);
        $this->resetValidation();
    }

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            $user = Auth::user();

            // Redirect berdasarkan role user
            if ($user->role === 'kasir') {
                return redirect()->route('pos.index');
            }

            // Admin / Owner → dashboard manajemen
            return redirect()->route('dashboard');
        }

        session()->flash('error', 'Email atau password yang Anda masukkan salah.');
    }

    public function render()
    {
        return view('livewire.pos-login');
    }
}
