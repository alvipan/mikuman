<div class="flex flex-col h-[calc(100vh-4rem)] bg-base-200 md:rounded-tl-2xl 2xl:rounded-t-2xl" wire:poll.keep-alive="poll">
    <div class="flex items-center px-4 md:px-6 py-4">
        <h2 class="font-bold me-auto flex items-center">
            <span class="icon-[tabler--layout-dashboard] size-5 me-3"></span>
            Dashboard
        </h2>
    </div>
    <div class="flex flex-1 overflow-y-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm px-4 md:px-6 pb-4 md:pb-6">

        <div class="md:col-span-2 grid grid-cols-subgrid gap-4">
            <div class="card">
                <div class="card-header flex items-center gap-2 pb-3">
                    <span class="icon-[tabler--wifi] size-5 text-success"></span>
                    <h3 class="font-bold me-auto">Hotspot</h3>
                    <a class="btn btn-xs btn-outline btn-primary" href="/hotspot/users">More</a>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-base-content/60 text-sm font-semibold">Users</h4>
                            <span class="min-md:text-2xl font-semibold">{{ $hotspot['users'] }}</span>
                        </div>
                        <div>
                            <h4 class="text-base-content/60 text-sm font-semibold">Active</h4>
                            <span class="min-md:text-2xl font-semibold">{{ $hotspot['active'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header flex items-center gap-2 pb-3">
                    <span class="icon-[tabler--devices-pc] size-5 text-success"></span>
                    <h3 class="font-bold me-auto">PPPoE</h3>
                    <a class="btn btn-xs btn-outline btn-primary" href="/pppoe/users">More</a>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-base-content/60 text-sm font-semibold">Users</h4>
                            <span class="min-md:text-2xl font-semibold">{{ $pppoe['users'] }}</span>
                        </div>
                        <div>
                            <h4 class="text-base-content/60 text-sm font-semibold">Active</h4>
                            <span class="min-md:text-2xl font-semibold">{{ $pppoe['active'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card md:col-span-2">
                <div class="card-header flex items-center gap-2 pb-3">
                    <span class="icon-[tabler--coins] size-5 text-warning"></span>
                    <h3 class="font-bold me-auto">Income</h3>
                    <a class="btn btn-xs btn-outline btn-primary" href="/report">More</a>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <h4 class="text-base-content/60 text-sm font-semibold">Today</h4>
                            <span class="min-md:text-2xl font-semibold currency">{{$router->currency}}</span>
                            <span class="min-md:text-2xl font-semibold">{{ $income['today'] }}</span>
                        </div>
                        <div>
                            <h4 class="text-base-content/60 text-sm font-semibold">Yesterday</h4>
                            <span class="min-md:text-2xl font-semibold currency">{{$router->currency}}</span>
                            <span class="min-md:text-2xl font-semibold">{{ $income['yesterday'] }}</span>
                        </div>
                        <div>
                            <h4 class="text-base-content/60 text-sm font-semibold">This Month</h4>
                            <span class="min-md:text-2xl font-semibold currency">{{$router->currency}}</span>
                            <span class="min-md:text-2xl font-semibold">{{ $income['this-month'] }}</span>
                        </div>
                        <div>
                            <h4 class="text-base-content/60 text-sm font-semibold">Last Month</h4>
                            <span class="min-md:text-2xl font-semibold currency">{{$router->currency}}</span>
                            <span class="min-md:text-2xl font-semibold">{{ $income['last-month'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card md:col-span-2 lg:col-span-1" wire:ignore>
            <div class="card-header flex items-center gap-2 pb-2">
                <span class="icon-[tabler--chart-histogram] size-5 text-primary"></span>
                <h3 class="font-bold me-auto">Traffic Monitor</h3>
            </div>
            <div id="traffic-chart" class="w-full px-2"></div>
        </div>

        <div class="card flex md:col-span-2">
            <div class="card-header flex items-center gap-2 pb-1">
                <span class="icon-[tabler--logs] size-5 text-info"></span>
                <h3 class="font-bold me-auto">System Logs</h3>
                <a class="btn btn-xs btn-outline btn-primary" href="/logs">More</a>
            </div>
            <div class="w-full overflow-scroll h-[200px] grow">
                <table class="table text-sm">
                    <thead class="bg-base-100 sticky top-0 z-5 shadow">
                        <tr>
                            <th>DATE/TIME</th>
                            <th>TYPE</th>
                            <th>USER</th>
                            <th>IP ADDRESS</th>
                            <th>MESSAGE</th>
                        </tr>
                    </thead>
                    <tbody id="logs">
                        @foreach($logs as $log)
                        <tr>
                            <td>{{ $log['time'] }}</td>
                            <td>{{ $log['type'] }}</td>
                            <td>{{ $log['user'] }}</td>
                            <td>{{ $log['ip'] }}</td>
                            <td>{{ $log['message'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="md:col-span-2 lg:col-span-1 grid grid-cols-subgrid gap-4">
            <div class="card">
                <div class="card-header flex items-center gap-2 pb-1">
                    <span class="icon-[tabler--cpu] size-6 text-error"></span>
                    <h3 class="font-bold">System Resources</h3>
                </div>
                <div class="w-full overflow-x-auto">
                    <table class="table">
                        <tr>
                            <td class="font-semibold">CPU</td>
                            <td>
                                <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar bg-primary" style="width: {{ $cpu_load.'%' }};"></div>
                                </div>
                            </td>
                            <td>
                                <div class="w-16">
                                    <span>{{ $resource['cpu-count'] }}</span> *
                                    <span>{{ $cpu_frequency }}</span> MHz
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-semibold">Memory</td>
                            <td>
                                <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar bg-primary" style="width: {{ $memory_usage.'%' }}"></div>
                                </div>
                            </td>
                            <td>
                                <span>{{ $total_memory }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-semibold">HDD</td>
                            <td>
                                <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar bg-primary" style="width: {{ $hdd_usage.'%' }}"></div>
                                </div>
                            </td>
                            <td>
                                <span>{{ $total_hdd_space }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-semibold">Health</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="icon-[tabler--progress-bolt] size-5 text-warning"></span>
                                    <span>{{ $health[0]['value'] ?? 0 }}V</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="icon-[tabler--temperature] size-5 text-error"></span>
                                    <span>{{ $health[1]['value'] ?? 0 }}&deg;C</span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header flex items-center gap-2 pb-1">
                    <span class="icon-[tabler--router] size-6 text-info"></span>
                    <h3 class="font-bold">System Info</h3>
                </div>
                <div class="w-full overflow-x-auto">
                    <table class="table">
                        <tr>
                            <td class="font-semibold">Board name</td>
                            <td class="text-end">
                                <span>{{ $resource['board-name'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-semibold">Arcitecture name</td>
                            <td class="text-end">
                                <span>{{ $resource['architecture-name'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-semibold">Version</td>
                            <td class="text-end">
                                <span>{{ $resource['version'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-semibold">Build time</td>
                            <td class="text-end">
                                <span>{{ $resource['build-time'] }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script type="module">

const chartEl = document.querySelector("#traffic-chart");
const chartConfig = {
    chart: {
        height: 220,    
        type: "area",
        toolbar: {
            show: false,
        },
        zoom: {
            enabled: false,
        },
        animations: {
            enabled: false,
        },
    },
    series: [
        {
            name: "Tx",
            data: [0,0,0,0,0],
        },
        {
            name: "Rx",
            data: [0,0,0,0,0],
        },
    ],
    legend: {
        show: true,
        position: "top",
        horizontalAlign: "right",
        labels: {
            useSeriesColors: true,
        },
    },
    dataLabels: {
        enabled: false,
    },
    stroke: {
        curve: "smooth",
        width: 2,
    },
    grid: {
        strokeDashArray: 2,
        borderColor:
            "color-mix(in oklab, var(--color-base-content) 40%, transparent)",
    },
    colors: ["var(--color-blue-500)", "var(--color-error)"],
    fill: {
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            gradientToColors: ["var(--color-base-100)"],
            opacityTo: 0.3,
            stops: [0, 90, 100],
        },
    },
    xaxis: {
        type: "category",
        tickPlacement: "on",
        categories: [
            "00:00:00",
            "00:00:00",
            "00:00:00",
            "00:00:00",
            "00:00:00",
        ],
        axisBorder: {
            show: false,
        },
        axisTicks: {
            show: false,
        },
        crosshairs: {
            stroke: {
                dashArray: 0,
            },
            dropShadow: {
                show: false,
            },
        },
        tooltip: {
            enabled: false,
        },
        labels: {
            style: {
                colors: "var(--color-base-content)",
                fontSize: "12px",
                fontWeight: 400,
            },
            formatter: (title) => {
                let t = title;
                return t;
            },
        },
    },
    yaxis: {
        labels: {
            align: "left",
            minWidth: 0,
            maxWidth: 140,
            style: {
                colors: "var(--color-base-content)",
                fontSize: "12px",
                fontWeight: 400,
            },
            formatter: (value) => formatBytes(value),
        },
    },
    tooltip: {
        enabled: false,
    },
};
const trafficChart = new ApexCharts(chartEl, chartConfig);
trafficChart.render();

function formatBytes(bytes, decimals = 0) {
    if (bytes <= 0) return "0 Bytes";

    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ["Bytes", "KiB", "MiB", "GiB"];

    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
};

$wire.on('traffic-updated', () => {
    chartConfig.series[0].data.shift();
    chartConfig.series[0].data.push(
        $wire.txBits
    );
    chartConfig.series[1].data.shift();
    chartConfig.series[1].data.push(
        $wire.rxBits
    );

    let d = new Date();
    let t = d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();

    chartConfig.xaxis.categories.shift();
    chartConfig.xaxis.categories.push(t);

    trafficChart.updateSeries(chartConfig.series);
    trafficChart.updateOptions({
        xaxis: {
            categories: chartConfig.xaxis.categories,
        },
    });
});
</script>
@endscript