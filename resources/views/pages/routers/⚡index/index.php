<?php

use Livewire\Component;
use App\Models\Router;
use App\Services\Router\RouterService;

new class extends Component
{
    protected RouterService $routerService;

    public $routers;

    public $routerId;
    public $name;
    public $host;
    public $port = 8728;
    public $username;
    public $password;

    public $search = '';

    public bool $showModal = false;

    public function boot(RouterService $routerService): void
    {
        $this->routerService = $routerService;
    }

    public function mount(): void
    {
        $this->loadRouters();
    }

    public function loadRouters(): void
    {
        $this->routers = Router::query()
            ->latest()
            ->get();
    }

    public function routerStatus(Router $router): string
    {
        return $this->routerService
            ->status($router);
    }

    public function setupRouter(int $id): void
    {
        $router = Router::findOrFail($id);

        try {

            $configured = $this->routerService
                ->setup($router);

            if (! $configured) {

                $this->dispatch(
                    'notify',
                    type: 'info',
                    message: 'Already configured'
                );

                return;
            }

            $this->dispatch(
                'notify',
                type: 'success',
                message: 'Router fixed successfully'
            );

        } catch (\Throwable $e) {

            report($e);

            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Router setup failed'
            );
        }
    }

    public function create(): void
    {
        $this->reset([
            'routerId',
            'name',
            'host',
            'port',
            'username',
            'password',
        ]);

        $this->port = 8728;
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $router = Router::findOrFail($id);

        $this->routerId = $router->id;
        $this->name = $router->name;
        $this->host = $router->host;
        $this->port = $router->port;
        $this->username = $router->username;
        $this->password = $router->password;

        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required'],
            'host' => ['required'],
            'port' => ['required', 'numeric'],
            'username' => ['required'],
            'password' => ['required'],
        ]);

        Router::updateOrCreate(
            ['id' => $this->routerId],
            [
                'name' => $this->name,
                'host' => $this->host,
                'port' => $this->port,
                'username' => $this->username,
                'password' => $this->password,
            ]
        );

        $this->showModal = false;

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Router saved'
        );

        $this->loadRouters();
    }

    public function delete(int $id): void
    {
        Router::findOrFail($id)
            ->delete();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Router deleted'
        );

        $this->loadRouters();
    }

    public function render()
    {
        return $this->view()
            ->title('Routers');
    }
};