@props([
    'cols' => [],
    'rows' => 5,
])

<flux:card class="space-y-4">
    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <flux:skeleton animate="shimmer" class="w-20" />

        <flux:skeleton animate="shimmer" class="h-8 w-20" />
    </div>
    <flux:skeleton.group animate="shimmer">
        <flux:table>
            @if (!empty($cols))
                <flux:table.columns>
                    @foreach ($cols as $col)
                        <flux:table.column>{{ $col }}</flux:table.column>
                    @endforeach
                </flux:table.columns>
            @endif

            <flux:table.rows>
                @foreach (range(1, $rows) as $order)
                    <flux:table.row>
                        @foreach ($cols as $col)
                            @if (!empty($col))
                                <flux:table.cell>
                                    <flux:skeleton />
                                </flux:table.cell>
                            @else
                                <flux:table.cell>
                                    <div class="flex w-auto justify-end gap-2">
                                        <flux:skeleton class="w-15 h-7" />
                                        <flux:skeleton class="w-15 h-7" />
                                    </div>
                                </flux:table.cell>
                            @endif
                        @endforeach
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:skeleton.group>
</flux:card>
