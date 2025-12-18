<?php

namespace App\Livewire;


use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Validate; 
use Livewire\Component;
use App\Models\Router;
use App\Helpers\Mikrotik;

class Login extends Component
{
    #[Validate('required')]
    public $host = '';
    #[Validate('required')]
    public $user = '';
    public $pass = '';
    public $routers = [];

    public function mount()
    {
        $this->routers = Router::all();
    }

    public function login($id = null)
    {
        if ($id != null) {
            $router = Router::find($id);
        } else {
            $this->validate();
            $router = Router::firstOrNew(['host' => $this->host]);
            $router->user = $this->user;
            $router->pass = Crypt::encryptString($this->pass);
        }

        $connected = Mikrotik::connect($router);

        if (!$connected) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Failed to connect to mikrotik'
            ]);
            return;
        }

        $router->save();
        session(['router' => $router->host]);
        return $this->redirect('/dashboard');
    }

    public function render()
    {
        return view('livewire.login');
    }
}
