 @placeholder
     <x-ui.skeleton.table rows="3" :cols="['Router', 'Name', 'Speed', 'Validity', 'Users', 'Price', 'Status', '']" />
 @endplaceholder

 <div class="space-y-4">

     <flux:card class="space-y-4">

         <div class="flex items-center justify-between">

             <flux:heading size="md">
                 Hotspot Packages
             </flux:heading>

             <flux:button icon="plus" size="sm" wire:click="create" wire:target="create">
                 Add
             </flux:button>

         </div>

         <flux:table>
             <flux:table.columns>
                 <flux:table.column>Router</flux:table.column>
                 <flux:table.column>Name</flux:table.column>
                 <flux:table.column>Speed</flux:table.column>
                 <flux:table.column>Validity</flux:table.column>
                 <flux:table.column>Users</flux:table.column>
                 <flux:table.column>Price</flux:table.column>
                 <flux:table.column>Status</flux:table.column>
                 <flux:table.column></flux:table.column>
             </flux:table.columns>

             <flux:table.rows>
                 @forelse ($packages as $p)
                     <flux:table.row :key="$p->id" wire:key="hp-{{ $p->id }}">
                         <flux:table.cell>{{ $p->router->name }}</flux:table.cell>

                         <flux:table.cell variant="strong">{{ $p->name }}</flux:table.cell>

                         <flux:table.cell>{{ $p->rate_limit ?? '-' }}</flux:table.cell>

                         <flux:table.cell>{{ $p->validity ?? '-' }}</flux:table.cell>

                         <flux:table.cell>{{ $p->shared_users }}</flux:table.cell>

                         <flux:table.cell>
                             <span class="font-medium text-zinc-800 dark:text-zinc-200">
                                 {{ money($p->sale_price) }}
                             </span>
                         </flux:table.cell>

                         <flux:table.cell>
                             @if ($p->status)
                                 <flux:badge color="green" rounded size="sm" inset="top bottom">Active
                                 </flux:badge>
                             @else
                                 <flux:badge color="zinc" rounded size="sm" inset="top bottom">Disabled
                                 </flux:badge>
                             @endif
                         </flux:table.cell>

                         <flux:table.cell class="flex justify-end gap-2">
                             <flux:button size="sm" wire:click="edit({{ $p->id }})"
                                 wire:target="edit({{ $p->id }})">
                                 Edit
                             </flux:button>
                             <flux:button size="sm" variant="danger" wire:click="delete({{ $p->id }})"
                                 wire:target="delete({{ $p->id }})">
                                 Delete
                             </flux:button>
                         </flux:table.cell>
                     </flux:table.row>
                 @empty
                     <flux:table.row>
                         <flux:table.cell colspan="8" class="text-center">
                             <div class="space-y-4 p-4">
                                 <p>No hotspot package data found.</p>
                                 <flux:button icon="plus" wire:click="create" wire:target='create'>
                                     Add Package
                                 </flux:button>
                             </div>
                         </flux:table.cell>
                     </flux:table.row>
                 @endforelse
             </flux:table.rows>
         </flux:table>

     </flux:card>

     {{-- MODAL --}}

     <flux:modal wire:model="showModal" class="w-sm">

         <div class="space-y-4">

             <flux:heading size="md">
                 Package
             </flux:heading>

             <flux:select wire:model="router_id" label="Router">

                 <option value="">Select Router</option>

                 @foreach ($routers as $r)
                     <option value="{{ $r->id }}">
                         {{ $r->name }}
                     </option>
                 @endforeach

             </flux:select>

             <flux:input label="Package Name" wire:model="name" />

             <div class="grid grid-cols-2 gap-3">
                 <flux:field>
                     <flux:label>Sale Price</flux:label>
                     <flux:input.group>
                         <flux:input.group.prefix>Rp</flux:input.group.prefix>
                         <flux:input wire:model="sale_price" type="number" />
                     </flux:input.group>
                 </flux:field>

                 <flux:field>
                     <flux:label>Cost Price</flux:label>
                     <flux:input.group>
                         <flux:input.group.prefix>Rp</flux:input.group.prefix>
                         <flux:input wire:model="cost_price" type="number" />
                     </flux:input.group>
                 </flux:field>
             </div>

             <div class="grid grid-cols-2 gap-3">
                 <flux:input label="Validity" placeholder="1h / 1d / 30d" wire:model="validity" />

                 <flux:input label="Rate Limit" placeholder="2M/2M" wire:model="rate_limit" />
             </div>

             <flux:input label="Shared Users" type="number" wire:model="shared_users" />

             <div class="flex items-center justify-between">
                 <flux:checkbox label="Active" wire:model="status" />

                 <flux:button wire:click="save">
                     Save
                 </flux:button>
             </div>

         </div>

     </flux:modal>

 </div>
