@extends('app')

@section('content')
<div class="h-14 bg-base-200 flex items-center sticky px-6 -mx-6 -mt-6 top-16 z-30 mb-3">
    <h2 class="font-bold">Dashboard</h2>
</div>
<div class="flex grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">

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
                        <span class="min-md:text-2xl font-bold" id="hotspot-users">0</span>
                    </div>
                    <div>
                        <h4 class="text-base-content/60 text-sm font-semibold">Active</h4>
                        <span class="min-md:text-2xl font-bold" id="hotspot-active">0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header flex items-center gap-2 pb-3">
                <span class="icon-[tabler--devices-pc] size-5 text-success"></span>
                <h3 class="font-bold me-auto">PPPoE</h3>
                <a class="btn btn-xs btn-outline btn-primary" href="/ppp/users">More</a>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-base-content/60 text-sm font-semibold">Users</h4>
                        <span class="min-md:text-2xl font-bold" id="ppp-users">0</span>
                    </div>
                    <div>
                        <h4 class="text-base-content/60 text-sm font-semibold">Active</h4>
                        <span class="min-md:text-2xl font-bold" id="ppp-active">0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card col-span-2">
            <div class="card-header flex items-center gap-2 pb-3">
                <span class="icon-[tabler--coins] size-5 text-warning"></span>
                <h3 class="font-bold me-auto">Income</h3>
                <a class="btn btn-xs btn-outline btn-primary" href="/income">More</a>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <h4 class="text-base-content/60 text-sm font-semibold">Today</h4>
                        <span class="min-md:text-2xl font-bold" id="income-today">Rp 0</span>
                    </div>
                    <div>
                        <h4 class="text-base-content/60 text-sm font-semibold">Yesterday</h4>
                        <span class="min-md:text-2xl font-bold" id="income-yesterday">Rp 0</span>
                    </div>
                    <div>
                        <h4 class="text-base-content/60 text-sm font-semibold">This Month</h4>
                        <span class="min-md:text-2xl font-bold" id="income-this-month">Rp 0</span>
                    </div>
                    <div>
                        <h4 class="text-base-content/60 text-sm font-semibold">Last Month</h4>
                        <span class="min-md:text-2xl font-bold" id="income-last-month">Rp 0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header flex items-center gap-2 pb-2">
            <span class="icon-[tabler--chart-histogram] size-5 text-primary"></span>
            <h3 class="font-bold me-auto">Traffic Monitor</h3>
            <select
                id="select-interface"
                data-select='{
                    "apiUrl": "/interface",
                    "apiFieldsMap": {
                        "title":"name",
                        "val":"name"
                    },
                    "placeholder": "Interface",
                    "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                    "toggleClasses": "advance-select-toggle advance-select-sm w-29 select-disabled:pointer-events-none select-disabled:opacity-40",
                    "dropdownClasses": "advance-select-menu",
                    "optionClasses": "advance-select-option selected:select-active",
                    "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"icon-[tabler--check] shrink-0 size-4 text-primary hidden selected:block \"></span></div>",
                    "extraMarkup": "<span class=\"icon-[tabler--caret-up-down] shrink-0 size-4 text-base-content absolute top-1/2 end-3 -translate-y-1/2 \"></span>"
                }'
                class="hidden">
                <option>Interface</option>
            </select>
        </div>
        <div id="traffic-chart" class="w-full"></div>
    </div>

    <div class="card">
        <div class="card-header flex items-center gap-2 pb-1">
            <span class="icon-[tabler--router] size-6 text-info"></span>
            <h3 class="font-bold">System Info</h3>
        </div>
        <table class="table">
            <tr>
                <td class="font-semibold">Board name</td>
                <td class="text-end">
                    <span id="board-name">-</span>
                </td>
            </tr>
            <tr>
                <td class="font-semibold">Arcitecture name</td>
                <td class="text-end">
                    <span id="architecture-name">-</span>
                </td>
            </tr>
            <tr>
                <td class="font-semibold">Version</td>
                <td class="text-end">
                    <span id="version">-</span>
                </td>
            </tr>
            <tr>
                <td class="font-semibold">Build time</td>
                <td class="text-end">
                    <span id="build-time">-</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="card">
        <div class="card-header flex items-center gap-2 pb-1">
            <span class="icon-[tabler--cpu] size-6 text-error"></span>
            <h3 class="font-bold">System Resources</h3>
        </div>
        <table class="table">
            <tr>
                <td class="font-semibold">CPU</td>
                <td>
                    <div class="progress" role="progressbar" aria-label="25% Progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-primary" id="cpu-progress"></div>
                    </div>
                </td>
                <td>
                    <div class="w-16">
                        <span id="cpu-count">0</span> *
                        <span id="cpu-frequency">0</span> MHz
                    </div>
                </td>
            </tr>
            <tr>
                <td class="font-semibold">Memory</td>
                <td>
                    <div class="progress" role="progressbar" aria-label="25% Progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-primary" id="memory-progress"></div>
                    </div>
                </td>
                <td>
                    <span id="total-memory">0 Bytes</span>
                </td>
            </tr>
            <tr>
                <td class="font-semibold">HDD</td>
                <td>
                    <div class="progress" role="progressbar" aria-label="25% Progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-primary" id="hdd-progress"></div>
                    </div>
                </td>
                <td>
                    <span id="total-hdd-space">0 Bytes</span>
                </td>
            </tr>
            <tr>
                <td class="font-semibold">Health</td>
                <td>
                    <div class="flex items-center gap-2">
                        <span class="icon-[tabler--progress-bolt] size-5 text-warning"></span>
                        <span id="voltage">0V</span>
                    </div>
                </td>
                <td>
                    <div class="flex items-center gap-2">
                        <span class="icon-[tabler--temperature] size-5 text-error"></span>
                        <span id="temperature">0&deg;C</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="card">
        <div class="card-header flex items-center gap-2 pb-1">
            <span class="icon-[tabler--logs] size-5 text-info"></span>
            <h3 class="font-bold me-auto">System Logs</h3>
            <a class="btn btn-xs btn-outline btn-primary" href="/logs">More</a>
        </div>
        <div class="w-full h-[180px] overflow-auto">
            <table class="table text-sm">
                <tbody id="logs"></tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('js')
@vite(['resources/js/pages/dashboard.js'])
@endsection
