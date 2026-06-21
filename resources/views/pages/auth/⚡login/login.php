<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public $username;
    public $password;
    public $remember = false;

    public function login()
    {
        $this->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ], $this->remember)) {

            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Username atau password salah'
            ]);

            return;
        }

        session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function render()
    {
        return $this->view()
            ->layout('layouts.guest')->title('Login');
    }
};