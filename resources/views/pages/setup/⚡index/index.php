<?php

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public $name = '';
    public $username = '';
    public $password = '';

    protected $rules = [
        'name' => 'required|min:3',
        'username' => 'required|min:6|unique:users,username',
        'password' => 'required|min:8',
    ];

    public function createAdmin()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'username' => $this->username,
            'password' => Hash::make($this->password),
            'role' => 'admin'
        ]);

        Auth::login($user);

        return redirect()->route('routers.index');
    }

    public function render()
    {
        return $this->view()->layout('layouts::guest')->title('Setup');
    }
};
