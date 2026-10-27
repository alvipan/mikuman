<div>
    <!-- Tab Navigasi (Karena ini bagian dari halaman Hotspot) -->
    <div class="mb-6 flex border-b border-zinc-200 dark:border-zinc-700">
        <a href="/hotspot/packages" class="px-4 py-2 text-zinc-500 hover:text-zinc-900 dark:hover:text-white">Packages</a>
        <a href="/hotspot/vouchers"
            class="border-b-2 border-indigo-500 px-4 py-2 font-semibold text-indigo-600 dark:text-indigo-400">Vouchers</a>
        <a href="/hotspot/active" class="px-4 py-2 text-zinc-500 hover:text-zinc-900 dark:hover:text-white">Active</a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Form Generate Voucher -->
        <div
            class="h-fit rounded-xl border border-zinc-200 bg-white p-6 shadow-sm lg:col-span-1 dark:border-zinc-700 dark:bg-zinc-800">
            <h3 class="text-md mb-4 font-semibold">Generate Voucher</h3>
            <form wire:submit="generateVouchers" class="space-y-4">
                <flux:select wire:model="package_id" label="Pilih Paket" required>
                    <option value="">-- Pilih Paket --</option>
                    @foreach ($packages as $pkg)
                        <option value="{{ $pkg->id }}">{{ $pkg->name }} (Rp
                            {{ number_format($pkg->sale_price) }})</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model="reseller_id" label="Reseller (Opsional)">
                    <option value="">-- Tanpa Reseller --</option>
                    @foreach ($resellers as $reseller)
                        <option value="{{ $reseller->id }}">{{ $reseller->name }}</option>
                    @endforeach
                </flux:select>

                <flux:input wire:model="quantity" label="Jumlah Voucher" type="number" min="1" max="100"
                    required />

                <div class="pt-4">
                    <flux:button type="submit" variant="primary" class="w-full" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="generateVouchers">Generate Voucher</span>
                        <span wire:loading wire:target="generateVouchers">Memproses...</span>
                    </flux:button>
                </div>
            </form>
        </div>

        <!-- Tabel Voucher -->
        <div
            class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm lg:col-span-2 dark:border-zinc-700 dark:bg-zinc-800">
            <div class="border-b border-zinc-200 p-4 dark:border-zinc-700">
                <h3 class="font-semibold">Daftar Voucher Terbaru</h3>
            </div>
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="bg-zinc-50 dark:bg-zinc-900">
                        <th class="p-4 text-sm font-semibold">Kode</th>
                        <th class="p-4 text-sm font-semibold">Paket</th>
                        <th class="p-4 text-sm font-semibold">Reseller</th>
                        <th class="p-4 text-sm font-semibold">Status</th>
                        <th class="p-4 text-right text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vouchers as $voucher)
                        <tr
                            class="border-b border-zinc-100 hover:bg-zinc-50 dark:border-zinc-700 dark:hover:bg-zinc-700/50">
                            <td class="p-4 font-mono font-medium">{{ $voucher->code }}</td>
                            <td class="p-4">{{ $voucher->package->name }}</td>
                            <td class="p-4">{{ $voucher->reseller ? $voucher->reseller->name : '-' }}</td>
                            <td class="p-4">
                                <flux:badge size="sm"
                                    :color="$voucher->status === 'unused' ? 'green' : ($voucher->status === 'used' ? 'blue' : 'red')">
                                    {{ ucfirst($voucher->status) }}
                                </flux:badge>
                            </td>
                            <td class="p-4 text-right">
                                <flux:button size="sm" variant="danger"
                                    wire:click="deleteVoucher({{ $voucher->id }})" wire:confirm="Hapus voucher ini?">
                                    Hapus
                                </flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-zinc-500">Belum ada voucher.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
