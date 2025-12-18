<div class="flex flex-col h-[calc(100vh-4rem)] bg-base-200 md:rounded-tl-2xl 2xl:rounded-t-2xl">
    <div id="datatable" class="flex flex-col h-[calc(100vh-4rem)]" wire:ignore>
        <div class="flex items-center gap-2 px-4 md:px-6 py-4">

            <h2 class="font-bold me-auto flex items-center">
                <span class="icon-[tabler--devices-pc] size-5 me-3"></span>
                PPPoE Profiles
            </h2>

            <div class="tooltip [--placement:bottom]">
                <button type="button" class="tooltip-toggle btn btn-square btn-soft btn-sm btn-add">
                    <span class="icon-[tabler--plus] size-5"></span>
                </button>
                <span class="tooltip-content tooltip-shown:opacity-100 tooltip-shown:visible" role="tooltip">
                    <span class="tooltip-body">Add Profile</span>
                </span>
            </div>
            <div class="input input-sm max-w-50">
                <span class="icon-[tabler--search] text-base-content/80 my-auto me-3 size-4 shrink-0"></span>
                <label class="sr-only" for="table-input-search"></label>
                <input type="search" class="grow" placeholder="Search..." id="table-input-search" data-datatable-search="" />
            </div>
        </div>
        <div class="flex flex-col flex-1 px-4 md:px-6 pb-6">
            <div class="bg-base-100 flex flex-col flex-1 rounded-md shadow-base-300/20 shadow-sm">
                <div class="flex px-5 py-3 gap-2">
                    <div class="input input-sm w-auto px-0">
                        <select class="select select-sm" data-datatable-page-entities>
                            <option value="10">10 / page</option>
                            <option value="25">25 / page</option>
                            <option value="50">50 / page</option>
                        </select>
                    </div>
                    <button id="btn-remove" class="btn btn-soft btn-error btn-sm" disabled>Remove</button>
                </div>
                <div class="flex-grow h-90 overflow-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="relative">
                            <table class="table min-w-full">
                                <thead class="bg-base-100 sticky top-0 z-5 shadow">
                                    <tr class="text-xs">
                                        @foreach($config['tableHeader'] as $item)
                                        @if($item === 'checkbox')
                                        <th scope="col" class="--exclude-from-ordering w-3.5 pe-0">
                                            <div class="flex h-5">
                                                <input id="table-checkbox-all" type="checkbox" class="checkbox checkbox-xs" />
                                                <label for="table-checkbox-all" class="sr-only">Checkbox</label>
                                            </div>
                                        </th>
                                        @else
                                        <th>{{ $item }}</th>
                                        @endif
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
                        <button type="button" class="btn btn-text btn-circle btn-sm" data-datatable-paging-prev="">
                            <span aria-hidden="true">«</span>
                            <span class="sr-only">Previous</span>
                        </button>
                        <div class="flex items-center space-x-1 [&>.active]:text-bg-soft-primary" data-datatable-paging-pages=""></div>
                        <button type="button" class="btn btn-text btn-circle btn-sm" data-datatable-paging-next="">
                            <span class="sr-only">Next</span>
                            <span aria-hidden="true">»</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-modal.pppoe-profile-form />
    <x-modal.confirm />
</div>

@script
<script>
window.addEventListener("livewire:navigated", function () {
    let selected = [];
    table = new HSDataTable("#datatable", {
        pageLength: 10,
        pagingOptions: {
            pageBtnClasses: "btn btn-text btn-square btn-sm",
        },
        rowSelectingOptions: {
            selectAllSelector: "#table-checkbox-all",
        },
        data: $wire.data,
        columns: [
            {
                data: "",
                render: function () {
                    return '<input type="checkbox" class="checkbox checkbox-xs" data-datatable-row-selecting-individual="" />';
                },
            },
            { data: "name" },
            { data: "rate-limit", defaultContent: "" },
            { data: "only-one" },
            {
                data: "dns-server",
                defaultContent: "",
                render: function (data) {
                    let html = "";
                    if (data !== undefined) {
                        let dns = data.split(",");
                        $.each(dns, function (i, v) {
                            html +=
                                '<span class="badge badge-sm badge-soft">' +
                                v +
                                "</span> ";
                        });
                    }

                    return html;
                },
            },
            { data: "default", defaultContent: "" },
            {
                data: "",
                render: function (data) {
                    return (
                        '<button class="btn btn-circle btn-text btn-edit btn-sm">' +
                        '<span class="icon-[solar--settings-line-duotone] size-4.5"></span>' +
                        '</button>' +
                        '<button class="btn btn-circle btn-text btn-remove btn-error btn-sm">' +
                        '<span class="icon-[solar--trash-bin-trash-line-duotone] size-4.5"></span>' +
                        '</button>'
                    );
                },
            },
        ],
    });

    $("#content").on("click", ".btn-add", function () {
        HSOverlay.open("#pppoe-profile-form-modal");
        const form = $("#pppoe-profile-form");
        form[0].reset();
        form.find('input[name="item.id"]').val('');
    });

    $("#content").on("click", ".btn-edit", function () {
        HSOverlay.open("#pppoe-profile-form-modal");
        const row = $(this).closest("tr");
        const data = table.dataTable.row(row).data();
        const form = $("#pppoe-profile-form");

        form[0].reset();
        form.find('input[name="item.id"]').val(data.id);
        form.find('input[name="item.name"]').val(data.name);
        form.find('input[name="item.rate_limit"]').val(data["rate-limit"]);
        form.find('select[name="item.parent_queue"]').val(data["parent-queue"]);
        form.find('select[name="item.only_one"]').val(data["only-one"]);
        form.find('input[name="item.dns_server"]').val(data["dns-server"]);
    });
});
</script>
@endscript
