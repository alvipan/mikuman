<div class="flex flex-col h-[calc(100vh-4rem)] bg-base-200 md:rounded-tl-2xl 2xl:rounded-t-2xl">
    <div class="flex flex-col h-[calc(100vh-4rem)]" id="datatable" wire:ignore>
        <div class="flex items-center gap-2 px-4 md:px-6 py-4">
            <h2 class="font-bold me-auto flex items-center">
                <span class="icon-[tabler--wifi] size-5 me-3"></span>
                Hotspot Active
            </h2>
            
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
                                    <th scope="col" class="--exclude-from-ordering w-3.5 pe-0">
                                        <div class="flex h-5">
                                            <input id="table-checkbox-all" type="checkbox" class="checkbox checkbox-xs" />
                                            <label for="table-checkbox-all" class="sr-only">Checkbox</label>
                                        </div>
                                    </th>
                                    <th>Server</th>
                                    <th>User</th>
                                    <th>MAC Address</th>
                                    <th>IP Address</th>
                                    <th>Uptime</th>
                                    <th>Actions</th>
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
    <div>
        <x-modal.confirm />
    </div>
</div>

@script
<script>
document.addEventListener('livewire:navigated', () => {
    
    table = new HSDataTable('#datatable', {
        pageLength: 10,
        pagingOptions: {
            pageBtnClasses: "btn btn-text btn-square btn-sm",
        },
        selecting: true,
        rowSelectingOptions: {
            selectAllSelector: "#table-checkbox-all",
        },
        data: $wire.data,
        columns: [
            {
                data: '',
                render: function () {
                    return '<input type="checkbox" class="checkbox checkbox-xs" data-datatable-row-selecting-individual="" />';
                },
            },
            { data: "server" },
            { data: "user", default: "" },
            { data: "mac-address", default: "" },
            { data: "address", default: 0 },
            {
                data: "uptime",
                render: function (data) {
                    return formatTimes(data);
                },
            },
            {
                data: "id",
                render: function (data) {
                    return (
                        '<button class="btn btn-circle btn-text btn-remove btn-error btn-sm">' +
                        '<span class="icon-[solar--trash-bin-trash-line-duotone] size-4.5"></span>' +
                        '</button>'
                    );
                },
            },
        ],
    });
}, { once: true });
</script>
@endscript
