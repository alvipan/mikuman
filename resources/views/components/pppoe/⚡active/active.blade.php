 @placeholder
     <x-ui.skeleton.table rows="3" :cols="['Router', 'Name', 'Address', 'Caller ID', 'Uptime']" />
 @endplaceholder

 <div class="space-y-6">

     <flux:card class="space-y-4">

         {{-- HEADER --}}
         <div class="flex items-center justify-between">
             <flux:heading size="lg">
                 PPPoE Active
             </flux:heading>

             <flux:button size="sm" wire:click="refresh">
                 Refresh
             </flux:button>
         </div>

         <flux:table>

             <flux:table.columns>
                 <flux:table.column>Router</flux:table.column>
                 <flux:table.column>Name</flux:table.column>
                 <flux:table.column>Address</flux:table.column>
                 <flux:table.column>Caller ID</flux:table.column>
                 <flux:table.column>Uptime</flux:table.column>
             </flux:table.columns>

             <flux:table.rows>

                 @forelse ($results as $row)
                     <flux:table.row>
                         <flux:table.cell>
                             <flux:badge size="sm">
                                 {{ $row['router_name'] ?? '-' }}
                             </flux:badge>
                         </flux:table.cell>

                         <flux:table.cell>
                             {{ $row['name'] ?? '-' }}
                         </flux:table.cell>

                         <flux:table.cell>
                             {{ $row['address'] ?? '-' }}
                         </flux:table.cell>

                         <flux:table.cell>
                             {{ $row['caller'] ?? '-' }}
                         </flux:table.cell>

                         <flux:table.cell>
                             {{ format_uptime($row['uptime']) }}
                         </flux:table.cell>
                     </flux:table.row>
                 @empty
                     <flux:table.row>
                         <flux:table.cell colspan="3">
                             Tidak ada user aktif
                         </flux:table.cell>
                     </flux:table.row>
                 @endforelse

             </flux:table.rows>

         </flux:table>

     </flux:card>

 </div>
