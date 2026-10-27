<div>
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-lg font-semibold">Daftar Router (NAS)</h2>
        <div class="flex gap-2">
            <flux:button variant="filled" wire:click="refreshStatus" icon="arrow-path" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="refreshStatus">Refresh Status</span>
                <span wire:loading wire:target="refreshStatus">Mengecek...</span>
            </flux:button>
            <flux:button variant="primary" wire:click="resetForm" icon="plus">Tambah Router</flux:button>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Tabel Router -->
        <div
            class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm lg:col-span-2 dark:border-zinc-700 dark:bg-zinc-800">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
                        <th class="p-4 text-sm font-semibold">Nama</th>
                        <th class="p-4 text-sm font-semibold">Address</th>
                        <th class="p-4 text-sm font-semibold">Status</th>
                        <th class="p-4 text-right text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($routers as $router)
                        <tr
                            class="border-b border-zinc-100 hover:bg-zinc-50 dark:border-zinc-700 dark:hover:bg-zinc-700/50">
                            <td class="p-4">
                                <div class="font-medium">{{ $router->name }}</div>
                                <div class="text-xs text-zinc-500">{{ $router->lan_ip }}</div>
                            </td>
                            <td class="p-4">{{ $router->address }}:{{ $router->port }}</td>
                            <td class="p-4">
                                @if (!$router->is_active)
                                    <flux:badge color="zinc" size="sm">Nonaktif</flux:badge>
                                @else
                                    <flux:badge
                                        :color="($this->statuses[$router->id] ?? 'offline') === 'online' ? 'green' : 'red'"
                                        size="sm">
                                        {{ ucfirst($this->statuses[$router->id] ?? 'offline') }}
                                    </flux:badge>
                                @endif
                            </td>
                            <td class="flex flex-wrap justify-end gap-2 p-4">
                                @if (($this->statuses[$router->id] ?? 'offline') === 'online')
                                    <flux:button size="sm" variant="primary" icon="wrench-screwdriver"
                                        wire:click="confirmSetup({{ $router->id }})">
                                        Setup
                                    </flux:button>
                                @endif
                                <flux:button size="sm" wire:click="edit({{ $router->id }})">Edit</flux:button>
                                <flux:button size="sm" variant="danger" wire:click="delete({{ $router->id }})"
                                    wire:confirm="Hapus router ini?">Hapus</flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-4 text-center">Belum ada router.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Form Router -->
        <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
            <h3 class="text-md mb-4 font-semibold">{{ $routerId ? 'Edit Router' : 'Tambah Router Baru' }}</h3>
            <form wire:submit="save" class="space-y-4">
                <flux:input wire:model="name" label="Nama Router" required />
                <flux:input wire:model="address" label="IP Address (Tunnel)" required />
                <div class="grid grid-cols-2 gap-4">
                    <flux:select wire:model="protocol" label="Protocol">
                        <option value="https">HTTPS</option>
                        <option value="http">HTTP</option>
                    </flux:select>
                    <flux:input wire:model="port" label="Port" type="number" required />
                </div>
                <flux:input wire:model="username" label="Username REST API" required />
                <flux:input wire:model="password" label="Password REST API" type="password" required />
                <flux:separator text="Konfigurasi Provisioning" class="my-4" />
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="lan_ip" label="IP LAN" required />
                    <flux:input wire:model="lan_interface" label="Bridge LAN" required />
                </div>
                <flux:input wire:model="wlan_ssid" label="SSID WiFi (Opsional)" />
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="hotspot_subnet" label="Subnet Hotspot" required />
                    <flux:input wire:model="pppoe_subnet" label="Subnet PPPoE" required />
                </div>
                <flux:checkbox wire:model="is_active" label="Aktifkan Router" />
                <div class="pt-4">
                    <flux:button type="submit" variant="primary" class="w-full">Simpan</flux:button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL SETUP PROVISIONING -->
    <flux:modal wire:model="showSetupModal" class="md:w-[500px]"
        x-on:modal-close.window="
        if ($wire.isProvisioning && !$wire.setupFinished) {
            $event.preventDefault();
            if (confirm('Proses provisioning sedang berjalan. Yakin ingin membatalkan?')) {
                $wire.cancelSetup();
                $flux.modal('setup-modal').close();
            }
        }
    ">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Setup Otomatis: {{ $setupRouterName }}</flux:heading>
                <flux:text class="mt-2">Mikuman akan mengonfigurasi router.</flux:text>
            </div>

            <!-- State 1: Konfirmasi -->
            @if (!$isProvisioning && !$setupFinished && empty($provisionSteps))
                <div class="rounded-lg bg-orange-50 p-4 text-sm text-orange-700 dark:bg-orange-900/30">
                    <strong>PERINGATAN:</strong> Proses ini akan memperbarui/membuat konfigurasi bernama
                    <code>mikuman-*</code>.
                </div>
                <div class="flex justify-end gap-2">
                    <flux:button wire:click="closeSetupModal" variant="ghost">Batal</flux:button>
                    <flux:button wire:click="startSetup" variant="primary" icon="play">Mulai Provisioning
                    </flux:button>
                </div>
            @endif

            <!-- State 2 & 3: Proses & Hasil (Console Style) -->
            @if ($isProvisioning || $setupFinished)
                <div @if ($isProvisioning) wire:poll.1s="executeStep" @endif x-data="{ scrollToBottom() { $el.scrollTop = $el.scrollHeight; } }"
                    x-init="scrollToBottom()" @step-appended.window="setTimeout(() => scrollToBottom(), 50)"
                    class="max-h-64 space-y-2 overflow-y-auto rounded-lg bg-zinc-900 p-4 font-mono text-sm text-zinc-300 shadow-inner">
                    @foreach ($provisionSteps as $step)
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 shrink-0">
                                @if ($step['status'] === 'running')
                                    <flux:icon.arrow-path class="h-4 w-4 animate-spin text-indigo-400" />
                                @elseif ($step['status'] === 'success')
                                    <flux:icon.check-circle variant="solid" class="h-4 w-4 text-green-500" />
                                @else
                                    <flux:icon.x-circle variant="solid" class="h-4 w-4 text-red-500" />
                                @endif
                            </div>

                            <div class="flex-1">
                                <span
                                    class="{{ $step['status'] === 'running' ? 'text-zinc-500' : ($step['status'] === 'success' ? 'text-zinc-100' : 'text-red-400') }}">
                                    {{ $step['step'] }}
                                </span>

                                @if (isset($step['error']))
                                    <div class="mt-1 border-l-2 border-red-500/50 pl-2 text-xs text-red-500">
                                        {{ $step['error'] }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pesan Error Utama -->
                @if ($provisionError)
                    <div class="rounded-lg border border-red-800 bg-red-900/50 text-sm text-red-300">
                        <strong>Setup Gagal:</strong> {{ $provisionError }}
                    </div>
                @endif

                <!-- Tombol Aksi (Batal Paksa / Tutup) -->
                <div class="flex justify-end gap-2">

                    @if ($setupFinished)
                        <flux:button wire:click="closeSetupModal" variant="primary">Tutup</flux:button>
                    @endif
                </div>
            @endif

        </div>
    </flux:modal>
</div>
