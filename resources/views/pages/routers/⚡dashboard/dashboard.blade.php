<div class="space-y-6">

    {{-- Top Stats Cards --}}
    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
        <flux:card>
            <div class="text-sm">Hotspot Users</div>
            <div class="text-2xl font-bold">{{ $stats["hotspot"]["total"] ?? 0 }}</div>
        </flux:card>
        <flux:card>
            <div class="text-sm">Active Hotspot</div>
            <div class="text-2xl font-bold">{{ $stats["hotspot"]["active"] ?? 0 }}</div>
        </flux:card>
        <flux:card>
            <div class="text-sm">PPPoE Secrets</div>
            <div class="text-2xl font-bold">{{ $stats["pppoe"]["total"] ?? 0 }}</div>
        </flux:card>
        <flux:card>
            <div class="text-sm">Active PPPoE</div>
            <div class="text-2xl font-bold">{{ $stats["pppoe"]["active"] ?? 0 }}</div>
        </flux:card>
    </div>

    {{-- Traffic Chart --}}
    <flux:card wire:poll.2s="loadTraffic">
        <div class="mb-2 font-semibold">Traffic (Live)</div>
        <canvas id="trafficChart" class="h-48 w-full" wire:ignore></canvas>
    </flux:card>

    {{-- Router Detail & Resources --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <flux:card>
            <div class="mb-2 font-semibold">Router Detail</div>
            <ul class="space-y-1 text-sm">
                <li><strong>Name:</strong> {{ $stats["identity"]["name"] ?? "-" }}</li>
                <li><strong>Board:</strong> {{ $stats["resources"]["board"] ?? "-" }}</li>
                <li><strong>Version:</strong> {{ $stats["resources"]["version"] ?? "-" }}</li>
                <li><strong>Uptime:</strong> {{ format_uptime($stats["resources"]["uptime"]) ?? "-" }}</li>
            </ul>
        </flux:card>
        <flux:card>
            <div class="mb-2 font-semibold">Resources</div>
            <ul class="space-y-1 text-sm">
                <li><strong>CPU:</strong> {{ $stats["resources"]["cpu_load"] ?? 0 }}%</li>
                <li><strong>Memory:</strong>
                    {{ format_bytes(($stats["resources"]["total_memory"] ?? 0) - ($stats["resources"]["free_memory"] ?? 0)) }}
                    / {{ format_bytes($stats["resources"]["total_memory"] ?? 0) }}
                </li>
            </ul>
        </flux:card>
    </div>

</div>

{{-- Include Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('livewire:init', () => {

        const ctx = document.getElementById('trafficChart').getContext('2d')

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                        label: 'Download',
                        data: [],
                        borderColor: 'rgb(48, 155, 255)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Upload',
                        data: [],
                        borderColor: 'rgb(240, 60, 36)',
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                animation: false,
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) => formatBps(value)
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                return context.dataset.label + ': ' + formatBps(context.raw)
                            }
                        }
                    }
                }
            }

        })

        Livewire.on('traffic-updated', (event) => {

            const data = event[0];
            const time = new Date().toLocaleTimeString()

            chart.data.labels.push(time)
            chart.data.datasets[0].data.push(data.download)
            chart.data.datasets[1].data.push(data.upload)

            // limit 20 point
            if (chart.data.labels.length > 10) {
                chart.data.labels.shift()
                chart.data.datasets[0].data.shift()
                chart.data.datasets[1].data.shift()
            }

            chart.update()
        })

    })
</script>
