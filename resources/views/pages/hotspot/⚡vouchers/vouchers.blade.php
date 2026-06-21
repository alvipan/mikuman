 @placeholder
     <x-ui.skeleton.table rows="3" :cols="['Router', 'Code', 'Package', 'Reseller', 'Used at', 'Expired at', 'Status', '']" />
 @endplaceholder

 <div class="space-y-4">

     <flux:card class="space-y-4">

         <flux:heading size="md" class="me-auto">
             Hotspot Vouchers
         </flux:heading>

         <div class="flex items-center justify-between gap-2">

             <div class="flex flex-col gap-2 md:flex-row md:items-center">

                 <div class="flex-1">
                     <flux:input size="sm" wire:model.live.debounce.300ms="search" icon="magnifying-glass"
                         placeholder="Search voucher..." />
                 </div>

                 <flux:dropdown>

                     <flux:button icon="adjustments-horizontal" size="sm" />

                     <flux:menu>

                         <div class="w-72 space-y-3 p-3">

                             <flux:select size="sm" label="Router" wire:model.live="filterRouter">
                                 <option value="">All Routers</option>

                                 @foreach ($routers as $router)
                                     <option value="{{ $router->id }}">
                                         {{ $router->name }}
                                     </option>
                                 @endforeach
                             </flux:select>

                             <flux:select size="sm" label="Package" wire:model.live="filterProfile">
                                 <option value="">All Packages</option>

                                 @foreach ($profiles as $profile)
                                     <option value="{{ $profile->id }}">
                                         {{ $profile->name }}
                                     </option>
                                 @endforeach
                             </flux:select>

                             <flux:select size="sm" label="Reseller" wire:model.live="filterReseller">
                                 <option value="">All Resellers</option>

                                 @foreach ($resellers as $reseller)
                                     <option value="{{ $reseller->id }}">
                                         {{ $reseller->name }}
                                     </option>
                                 @endforeach
                             </flux:select>

                             <flux:select size="sm" label="Status" wire:model.live="filterStatus">
                                 <option value="">All Status</option>
                                 <option value="unused">Unused</option>
                                 <option value="used">Used</option>
                                 <option value="expired">Expired</option>
                             </flux:select>

                             <flux:button size="sm" class="w-full" wire:click="resetFilters">
                                 Reset Filters
                             </flux:button>

                         </div>

                     </flux:menu>

                 </flux:dropdown>

             </div>

             <div class="flex flex-col gap-2 md:flex-row md:items-center">

                 <flux:button icon="plus" size="sm" wire:click="openGenerateModal">
                     Generate
                 </flux:button>

                 <flux:button icon="printer" size="sm" wire:click="openPrintModal">
                     Print
                 </flux:button>

             </div>

         </div>

         <flux:table>

             <flux:table.columns>
                 <flux:table.column>Router</flux:table.column>
                 <flux:table.column>Code</flux:table.column>
                 <flux:table.column>Package</flux:table.column>
                 <flux:table.column>Reseller</flux:table.column>
                 <flux:table.column>Used at</flux:table.column>
                 <flux:table.column>Expired at</flux:table.column>
                 <flux:table.column>Status</flux:table.column>
                 <flux:table.column></flux:table.column>
             </flux:table.columns>

             <flux:table.rows wire:loading.class="opacity-50">

                 @forelse ($vouchers as $voucher)
                     <flux:table.row wire:key="vc-{{ $voucher->id }}">

                         <flux:table.cell>
                             {{ $voucher->router->name }}
                         </flux:table.cell>

                         <flux:table.cell class="font-mono font-bold">
                             {{ $voucher->username }}
                         </flux:table.cell>

                         <flux:table.cell>
                             {{ $voucher->profile->name }}
                         </flux:table.cell>

                         <flux:table.cell>
                             {{ $voucher->reseller?->name ?? '-' }}
                         </flux:table.cell>

                         <flux:table.cell>
                             {{ $voucher->used_at ?? '-' }}
                         </flux:table.cell>

                         <flux:table.cell>
                             {{ $voucher->expired_at ?? '-' }}
                         </flux:table.cell>

                         <flux:table.cell>

                             @if ($voucher->status === 'unused')
                                 <flux:badge rounded color="gray" size="sm">Unused</flux:badge>
                             @elseif ($voucher->status === 'used')
                                 <flux:badge rounded color="green" size="sm">Used</flux:badge>
                             @elseif ($voucher->status === 'expired')
                                 <flux:badge rounded color="red" size="sm">Expired</flux:badge>
                             @endif

                         </flux:table.cell>

                         <flux:table.cell class="flex justify-end gap-2">

                             <flux:button size="sm" wire:click="edit({{ $voucher->id }})"
                                 wire:target="edit({{ $voucher->id }})">
                                 Edit
                             </flux:button>

                             <flux:button size="sm" variant="danger" wire:click="delete({{ $voucher->id }})"
                                 wire:target="delete({{ $voucher->id }})">
                                 Delete
                             </flux:button>

                         </flux:table.cell>

                     </flux:table.row>
                 @empty
                     <flux:table.row>
                         <flux:table.cell colspan="8" class="text-center">
                             <div class="space-y-4 p-4">
                                 <p>No hotspot voucher data found.</p>
                                 <flux:button wire:click='openGenerateModal' icon="plus">
                                     Generate Voucher
                                 </flux:button>
                             </div>
                         </flux:table.cell>
                     </flux:table.row>
                 @endforelse

             </flux:table.rows>

         </flux:table>

         @if ($vouchers->hasPages())
             {{ $vouchers->links() }}
         @endif
     </flux:card>

     <flux:modal name="generate-voucher" class="w-sm" wire:model="showGenerateModal">

         <flux:heading size="lg">
             Generate Hotspot Vouchers
         </flux:heading>

         <div class="mt-4 space-y-4">

             <flux:select label="Router" wire:model.live="router_id">
                 <option value="">Select Router</option>
                 @foreach ($routers as $router)
                     <option value="{{ $router->id }}">
                         {{ $router->name }}
                     </option>
                 @endforeach
             </flux:select>

             <flux:select label="Profile" wire:model="profile_id">
                 <option value="">Select Package</option>
                 @foreach ($profiles as $profile)
                     <option value="{{ $profile->id }}">
                         {{ $profile->name }}
                     </option>
                 @endforeach
             </flux:select>

             <flux:select label="Reseller" wire:model="reseller_id">
                 <option value="">- Optional -</option>
                 @foreach ($resellers as $reseller)
                     <option value="{{ $reseller->id }}">
                         {{ $reseller->name }}
                     </option>
                 @endforeach
             </flux:select>

             <flux:input type="number" label="Quantity" wire:model="qty" min="1" />

         </div>

         <div class="mt-6 flex justify-end gap-2">

             <flux:button variant="ghost" x-on:click="$flux.modal('generate-voucher').close()">
                 Cancel
             </flux:button>

             <flux:button wire:click="generate">
                 Generate
             </flux:button>

         </div>

     </flux:modal>

     <flux:modal name="edit-voucher" wire:model="showEditModal" class="w-sm">

         <flux:heading size="lg">
             Edit Voucher
         </flux:heading>

         <div class="mt-4 space-y-4">
             <flux:input label="Username" wire:model="name" />
             <flux:input label="Password" wire:model="password" />
             <flux:input type="datetime-local" label="Expired At" wire:model="expired_at" />
         </div>

         <div class="mt-6 flex justify-end gap-2">
             <flux:button variant="ghost" x-on:click="$flux.modal('edit-voucher').close()">
                 Cancel
             </flux:button>

             <flux:button variant="primary" wire:click="update">
                 Save
             </flux:button>
         </div>

     </flux:modal>

     <flux:modal name="print-voucher" wire:model="showPrintModal" class="w-sm">
         <flux:heading size="lg">
             Print Vouchers
         </flux:heading>

         <div class="mt-4 space-y-4">
             <flux:select label="Batch" wire:model="selectedBatch">
                 <option value="">Select Batch</option>
                 @foreach ($batches as $b)
                     <option value="{{ $b }}">{{ $b }}</option>
                 @endforeach
             </flux:select>

             <flux:select label="Color" wire:model="selectedColor">
                 <option value="blue">Blue</option>
                 <option value="red">Red</option>
                 <option value="green">Green</option>
                 <option value="yellow">Yellow</option>
             </flux:select>

             <flux:field variant="inline">
                 <flux:checkbox wire:model="showQr" />

                 <flux:label>Show QR Code</flux:label>
             </flux:field>

             <flux:button wire:click="print" class="w-full">Print</flux:button>
         </div>
     </flux:modal>

 </div>

 @script
     <script>
         Livewire.on('print-voucher', (data) => {
             window.open(data.url, '_blank');
         });
     </script>
 @endscript
