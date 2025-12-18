<div class="flex flex-col h-[calc(100vh-4rem)] bg-base-200 md:rounded-tl-2xl 2xl:rounded-t-2xl">
    <div class="flex flex-col h-[calc(100vh-4rem)]" id="datatable" wire:ignore>
        <div class="flex max-sm:grid max-sm:grid-cols-1 items-center gap-3 px-4 md:px-6 py-4">
            <h2 class="font-bold me-auto flex items-center">
                <span class="icon-[tabler--coins] size-5 me-3"></span>
                Report
            </h2>
            <div class="grid grid-cols-2 gap-3">
                <div class="grid grid-cols-2 gap-3">
                    <button class="btn btn-sm btn-primary btn-soft justify-start">
                        <span class="icon-[tabler--ticket] size-4 me-1"></span>
                        <span class="shrink-0" id="voucher">0</span>
                    </button>
                    <button class="btn btn-sm btn-warning btn-soft justify-start">
                        <span class="currency">{{$router->currency}}</span>
                        <span class="shrink-0" id="income">0</span>
                    </button>
                </div>
                <div class="input input-sm md:w-auto">
                    <span class="icon-[tabler--search] text-base-content/80 my-auto me-3 size-4 shrink-0"></span>
                    <input type="search" class="grow" placeholder="Search..." id="table-input-search" data-datatable-search="" />
                </div>
            </div>
        </div>
        <div class="flex flex-col flex-1 px-4 md:px-6 pb-6">
            <div class="bg-base-100 flex flex-col flex-1 rounded-md shadow-base-300/20 shadow-sm">
                <div class="flex px-5 py-3 gap-2">
                    <div class="input input-sm w-auto px-0 me-auto">
                        <select class="select select-sm" id="page-entities" data-datatable-page-entities>
                            <option value="10">10 / page</option>
                            <option value="25">25 / page</option>
                            <option value="50">50 / page</option>
                        </select>
                    </div>
                    <div class="input input-sm w-auto md:w-60" wire:ignore>
                        <span class="icon-[tabler--calendar] text-base-content/80 my-auto me-3 size-4 shrink-0"></span>
                        <input type="search" class="grow" id="date-range" />
                    </div>
                </div>
                <div class="flex-grow h-90 overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="relative">
                            <table class="table min-w-full">
                                <thead class="bg-base-100 sticky top-0 z-5 shadow">
                                    <tr class="text-xs">
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>User</th>
                                        <th>Profile</th>
                                        <th>IP Address</th>
                                        <th>MAC Address</th>
                                        <th>Comment</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                    <tr wire:key="{{$report['time']}}">
                                        <td>{{$report['date']}}</td>
                                        <td>{{$report['time']}}</td>
                                        <td>{{$report['user']}}</td>
                                        <td>{{$report['profile']}}</td>
                                        <td>{{$report['ip-address']}}</td>
                                        <td>{{$report['mac-address']}}</td>
                                        <td>{{$report['comment']}}</td>
                                        <td>{{$report['price']}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="border-base-content/25 flex items-center justify-between gap-3 border-t p-3 max-md:flex-wrap max-md:justify-center">
                    <div class="text-sm text-base-content/80" data-datatable-info="">
                        Showing
                        <span data-datatable-info-from=""></span>
                        to
                        <span data-datatable-info-to=""></span>
                        of
                        <span data-datatable-info-length=""></span>
                        items
                    </div>
                    <div class="flex hidden items-center space-x-1" data-datatable-paging="">
                        <button type="button" class="btn btn-text btn-square btn-sm" data-datatable-paging-prev="">
                            <span aria-hidden="true">«</span>
                            <span class="sr-only">Previous</span>
                        </button>
                        <div class="flex items-center space-x-1 [&>.active]:text-bg-soft-primary" data-datatable-paging-pages=""></div>
                        <button type="button" class="btn btn-text btn-square btn-sm" data-datatable-paging-next="">
                            <span class="sr-only">Next</span>
                            <span aria-hidden="true">»</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>

document.addEventListener('livewire:navigated', () => {
    const reportTable = new HSDataTable("#datatable", {
        pageLength: 10,
        pagingOptions: {
            pageBtnClasses: "btn btn-text btn-square btn-sm",
        },
        columns: [
            { data: "date" },
            { data: "time" },
            { data: "user" },
            { data: "profile" },
            { data: "ip-address" },
            { data: "mac-address" },
            { data: "comment" },
            {
                data: "price",
                render: function (data) {
                    let currency = $("#setting-currency").val();
                    let price = new Intl.NumberFormat().format(data);
                    return (
                        '<span class="currency">' +
                        currency +
                        "</span> " +
                        price
                    );
                },
            },
        ],
    });

    let summary = {
        voucher: 0,
        income: 0,
    };

    $("#page-entities").on("change", function () {
        summary.voucher = 0;
        summary.income = 0;
        reportTable.dataTable.draw();
    });

    let start = new Date(new Date().getFullYear(), new Date().getMonth(), 1);
    let end = new Date();

    reportTable.dataTable.search.fixed("date", function (row, data, index) {
        var min = start;
        var max = end;
        var date = new Date(data["date"]);

        if (
            (min === null && max === null) ||
            (min === null && date <= max) ||
            (min <= date && max === null) ||
            (min <= date && date <= max)
        ) {
            summary.voucher++;
            summary.income += parseInt(data["price"].replaceAll(",", ""));
            $("#voucher").html(new Intl.NumberFormat().format(summary.voucher));
            $("#income").html(new Intl.NumberFormat().format(summary.income));
            return true;
        }

        return false;
    });

    reportTable.dataTable.draw();

    flatpickr("#date-range", {
        mode: "range",
        dateFormat: "M/d/Y",
        defaultDate:
            new Date(new Date().getFullYear(), new Date().getMonth(), 1) +
            " to " +
            new Date(),
        onChange: function (dates) {
            start = dates[0];
            end = dates.length > 1 ? dates[1] : dates[0];
            summary.voucher = 0;
            summary.income = 0;
            reportTable.dataTable.draw();
        },
    });
}, { once: true });
</script>
@endscript
