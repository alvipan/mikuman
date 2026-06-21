@placeholder
    <x-ui.skeleton.table rows="3" :cols="['Router', 'User', 'IP', 'MAC', 'Uptime']" />
@endplaceholder

<div class="space-y-4">
    <flux:card class="space-y-4">

        <flux:heading class="flex items-center gap-3">
            <span>Active Users</span>
            <flux:badge rounded size="sm" color="green">
                {{ count($this->activeUsers) }}
            </flux:badge>
        </flux:heading>

        <flux:table>

            <flux:table.columns>
                <flux:table.column>Router</flux:table.column>
                <flux:table.column>User</flux:table.column>
                <flux:table.column>IP</flux:table.column>
                <flux:table.column>MAC</flux:table.column>
                <flux:table.column>Uptime</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($this->activeUsers as $user)
                    <flux:table.row>

                        <flux:table.cell>
                            {{ $user['router_name'] }}
                        </flux:table.cell>

                        <flux:table.cell class="font-mono">
                            {{ $user['user'] }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $user['ip'] }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $user['mac'] }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ format_uptime($user['uptime']) }}
                        </flux:table.cell>

                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="text-center text-gray-500">
                            Tidak ada user aktif
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>

        </flux:table>
    </flux:card>

</div>
