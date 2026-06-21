<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

new class extends Component
{
    public $name;
    public $username;
    public $password;
    public $search = '';

    public $editId = null;
    public $showModal = false;

    public function create()
    {
        $this->resetForm();

        $this->showModal = true;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $this->editId = $user->id;
        $this->name = $user->name;
        $this->username = $user->username;

        $this->showModal = true;
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => ['required'],
            'username' => [
                'required',
                'unique:users,username,' . $this->editId,
            ],
        ]);

        DB::transaction(function () use ($validated) {

            $data = $validated;

            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }

            $data['role'] = 'reseller';

            User::updateOrCreate(
                ['id' => $this->editId],
                $data
            );
        });

        $this->showModal = false;

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Reseller saved'
        );

        $this->resetForm();
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Reseller deleted'
        );
    }

    protected function resetForm()
    {
        $this->reset([
            'name',
            'username',
            'password',
            'editId',
        ]);
    }

    public function getResellersProperty()
    {
        return User::query()
            ->where('role', 'reseller')
            ->where(function ($query) {
                $query
                    ->where('name', 'like', "%{$this->search}%")
                    ->orWhere('username', 'like', "%{$this->search}%");
            })
            ->withCount([
                'vouchers as unused_vouchers_count' => fn ($q) => $q->where('status', 'unused'),
                'vouchers as used_vouchers_count' => fn ($q) => $q->where('status', 'used'),
                'vouchers as expired_vouchers_count' => fn ($q) => $q->where('status', 'expired'),
            ])
            ->withSum('commissions as commission_total', 'amount')
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return $this->view()->title('Resellers');
    }
};