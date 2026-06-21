<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

new class extends Component
{
    public $user;
    public $name;
    public $username;
    public $password;
    public $role;

    public function mount()
    {
        $this->user = auth()->user();
        $this->name = $this->user->name;
        $this->username = $this->user->username;
        $this->role = $this->user->role;
    }

    public function save()
    {
        $this->user->update([
            'name' => $this->name,
        ]);

        if ($this->password) {
            $this->user->update([
                'password' => Hash::make($this->password)
            ]);
        }

        $this->password = null;

        $this->dispatch('notify', type: 'success', message: 'Saved');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }

    public function render()
    {
        return $this->view();
    }
};