<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public $message = '';
    public $type = 'success'; // success, error, warning, info
    public $show = false;

    #[On('notify')]
    public function notify($message, $type = 'success')
    {
        $this->message = $message;
        $this->type = $type;
        $this->show = true;
    }

    #[On('closeToast')]
    public function close()
    {
        $this->show = false;
    }
};

?>

<div x-data="{
    show: @entangle('show'),
    init() {
        this.$watch('show', value => {
            if (value) {
                setTimeout(() => {
                    this.show = false;
                    $wire.close();
                }, 3000);
            }
        });
    }
}" x-show="show" x-transition class="fixed bottom-5 right-5 z-50">

    <div
        class="flex w-full max-w-xs items-center rounded-lg border border-zinc-200 bg-white p-4 text-gray-500 shadow-lg dark:border-zinc-700 dark:bg-zinc-800 dark:text-gray-400">

        <!-- Ikon -->
        <div
            class="{{ $type === 'success' ? 'text-green-500 bg-green-100 dark:bg-green-800 dark:text-green-200' : '' }} {{ $type === 'error' ? 'text-red-500 bg-red-100 dark:bg-red-800 dark:text-red-200' : '' }} {{ $type === 'warning' ? 'text-orange-500 bg-orange-100 dark:bg-orange-700 dark:text-orange-200' : '' }} {{ $type === 'info' ? 'text-blue-500 bg-blue-100 dark:bg-blue-700 dark:text-blue-200' : '' }} flex h-8 w-8 shrink-0 items-center justify-center rounded-lg">

            @if ($type === 'success')
                <flux:icon.check-circle variant="solid" class="h-5 w-5" />
            @elseif($type === 'error')
                <flux:icon.x-circle variant="solid" class="h-5 w-5" />
            @elseif($type === 'warning')
                <flux:icon.exclamation-triangle variant="solid" class="h-5 w-5" />
            @else
                <flux:icon.information-circle variant="solid" class="h-5 w-5" />
            @endif
        </div>

        <!-- Pesan -->
        <div class="ml-3 text-sm font-normal">{{ $message }}</div>

        <!-- Tombol Tutup Manual -->
        <button x-on:click="show = false; $wire.close()" type="button"
            class="-mx-1.5 -my-1.5 ml-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white text-gray-400 hover:bg-gray-100 hover:text-gray-900 dark:bg-zinc-800 dark:text-gray-500 dark:hover:bg-zinc-700 dark:hover:text-white">
            <span class="sr-only">Close</span>
            <flux:icon.x-mark class="h-4 w-4" />
        </button>
    </div>
</div>
