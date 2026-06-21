<x-container>
    <div class="space-y-6">

        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div>
                <flux:heading size="lg">Reports</flux:heading>

                <flux:text size="sm">
                    Revenue and hotspot usage overview
                </flux:text>
            </div>

            <div class="flex gap-2">

                <flux:select wire:model.live="period" size="sm">

                    <flux:select.option value="today">
                        Today
                    </flux:select.option>

                    <flux:select.option value="week">
                        Week
                    </flux:select.option>

                    <flux:select.option value="month">
                        Month
                    </flux:select.option>

                    <flux:select.option value="last_month">
                        Last Month
                    </flux:select.option>

                    <flux:select.option value="year">
                        Year
                    </flux:select.option>

                    <flux:select.option value="custom">
                        Custom
                    </flux:select.option>

                </flux:select>

                @if ($period === 'custom')
                    <flux:input type="date" wire:model.live="dateFrom" />

                    <flux:input type="date" wire:model.live="dateTo" />
                @endif

            </div>

        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">

            <flux:card>
                <flux:text size="sm">
                    Hotspot Revenue
                </flux:text>

                <div class="text-2xl font-bold">
                    {{ money($summary['hotspot_revenue']) }}
                </div>
            </flux:card>

            <flux:card>
                <flux:text size="sm">
                    PPPoE Revenue
                </flux:text>

                <div class="text-2xl font-bold">
                    {{ money($summary['pppoe_revenue']) }}
                </div>
            </flux:card>

            <flux:card>
                <flux:text size="sm">
                    Total Revenue
                </flux:text>

                <div class="text-2xl font-bold">
                    {{ money($summary['total_revenue']) }}
                </div>
            </flux:card>

        </div>

        <div class="grid gap-4 lg:grid-cols-3">

            <flux:card>
                <flux:text size="sm">
                    Unused Voucher
                </flux:text>

                <div class="text-2xl font-bold">
                    {{ number_format($summary['unused_vouchers']) }}
                </div>
            </flux:card>

            <flux:card>
                <flux:text size="sm">
                    Used Voucher
                </flux:text>

                <div class="text-2xl font-bold">
                    {{ number_format($summary['used_vouchers']) }}
                </div>
            </flux:card>

            <flux:card>
                <flux:text size="sm">
                    Expired Voucher
                </flux:text>

                <div class="text-2xl font-bold">
                    {{ number_format($summary['expired_vouchers']) }}
                </div>
            </flux:card>

        </div>

        <flux:card>

            <div class="mb-4">
                <flux:heading>
                    Revenue Trend
                </flux:heading>
            </div>

            <div wire:ignore x-data="revenueChart(@js($trend))">
                <div x-ref="chart"></div>
            </div>

        </flux:card>

        <flux:card>

            <div class="mb-4">
                <flux:heading>
                    Recent Transactions
                </flux:heading>

                <flux:text size="sm">
                    Latest hotspot and PPPoE revenue activity
                </flux:text>
            </div>

            <flux:table>

                <flux:table.columns>
                    <flux:table.column>Type</flux:table.column>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Date</flux:table.column>
                    <flux:table.column align="right">Amount</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>

                    @forelse ($transactions as $transaction)
                        <flux:table.row>

                            <flux:table.cell>
                                <flux:badge size="sm" rounded
                                    color="{{ $transaction['type'] === 'hotspot' ? 'sky' : 'emerald' }}">
                                    {{ strtoupper($transaction['type']) }}
                                </flux:badge>
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $transaction['name'] }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ \Carbon\Carbon::parse($transaction['date'])->format('d M Y H:i') }}
                            </flux:table.cell>

                            <flux:table.cell class="text-right font-semibold">
                                {{ money($transaction['amount']) }}
                            </flux:table.cell>

                        </flux:table.row>

                    @empty

                        <flux:table.row>
                            <flux:table.cell colspan="4" class="text-center">
                                No transactions found.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse

                </flux:table.rows>

            </flux:table>

        </flux:card>

    </div>
</x-container>

@script
    <script>
        window.revenueChart = (trend) => ({

            chart: null,

            buildSeries(rows) {
                return [{
                        name: 'Hotspot',
                        data: rows.map(row => ({
                            x: row.date,
                            y: row.hotspot,
                        })),
                    },
                    {
                        name: 'PPPoE',
                        data: rows.map(row => ({
                            x: row.date,
                            y: row.pppoe,
                        })),
                    },
                    {
                        name: 'Total',
                        data: rows.map(row => ({
                            x: row.date,
                            y: row.total,
                        })),
                    },
                ];
            },

            init() {

                this.chart = new ApexCharts(this.$refs.chart, {

                    chart: {
                        type: 'line',
                        height: 350,
                        toolbar: {
                            show: false,
                        },
                        foreColor: '#cbd5e1',
                    },

                    series: this.buildSeries(trend),

                    xaxis: {
                        type: 'datetime',

                        labels: {
                            format: 'dd MMM',
                        },
                    },

                    stroke: {
                        curve: 'smooth',
                        width: 3,
                    },

                    legend: {
                        position: 'top',
                    },

                    tooltip: {
                        y: {
                            formatter: value =>
                                'Rp ' + Number(value).toLocaleString(),
                        },
                    },

                    yaxis: {
                        labels: {
                            formatter: value =>
                                'Rp ' + Number(value).toLocaleString(),
                        },
                    },

                });

                this.chart.render();

                Livewire.on('trend-updated', (event) => {

                    const rows =
                        event.trend ??
                        event[0]?.trend ?? [];

                    this.chart.updateSeries(
                        this.buildSeries(rows)
                    );

                });

            },

        });
    </script>
@endscript
