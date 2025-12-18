<div class="flex flex-col h-[calc(100vh-4rem)] bg-base-200 md:rounded-tl-2xl 2xl:rounded-t-2xl">
    <div id="datatable" class="flex flex-col h-[calc(100vh-4rem)]" wire:ignore.self>
        <div class="flex items-center max-md:grid max-md:grid-cols-2 gap-3 px-4 md:px-6 py-4">
            <h2 class="font-bold me-auto flex items-center">
                <span class="icon-[tabler--wifi] size-5 me-3"></span>
                Hotspot Users
            </h2>

            <div class="flex gap-2 justify-end">

                <div class="tooltip [--placement:bottom]">
                    <button type="button" class="tooltip-toggle btn btn-square btn-soft btn-sm btn-add">
                        <span class="icon-[tabler--user-plus] size-5"></span>
                    </button>
                    <span class="tooltip-content tooltip-shown:opacity-100 tooltip-shown:visible" role="tooltip">
                        <span class="tooltip-body">Add User</span>
                    </span>
                </div>

                <div class="tooltip [--placement:bottom]">
                    <button type="button" class="tooltip-toggle btn btn-square btn-soft btn-sm" aria-haspopup="dialog" aria-expanded="false" aria-controls="middle-center-modal" data-overlay="#hotspot-user-form-modal">
                        <span class="icon-[tabler--users-plus] size-5"></span>
                    </button>
                    <span class="tooltip-content tooltip-shown:opacity-100 tooltip-shown:visible" role="tooltip">
                        <span class="tooltip-body">Generate Users</span>
                    </span>
                </div>

                <div class="tooltip [--placement:bottom]">
                    <button type="button" class="tooltip-toggle btn btn-square btn-soft btn-sm" aria-haspopup="dialog" aria-expanded="false" aria-controls="middle-center-modal" data-overlay="#hotspot-user-print-form-modal">
                        <span class="icon-[tabler--printer] size-5"></span>
                    </button>
                    <span class="tooltip-content tooltip-shown:opacity-100 tooltip-shown:visible" role="tooltip">
                        <span class="tooltip-body">Print</span>
                    </span>
                </div>

                <div class="input input-sm max-w-50" wire:ignore>
                    <span class="icon-[tabler--search] text-base-content/80 my-auto me-3 size-4 shrink-0"></span>
                    <label class="sr-only" for="table-input-search"></label>
                    <input type="search" class="grow" placeholder="Search..." id="table-input-search" data-datatable-search="" />
                </div>

            </div>
        </div>
        <div class="flex flex-col flex-1 px-4 md:px-6 pb-6">
            <div class="bg-base-100 flex flex-col flex-1 rounded-md shadow-base-300/20 shadow-sm">
                <div class="flex px-5 py-3 gap-2">
                    <div class="input input-sm w-auto px-0" wire:ignore>
                        <select class="select select-sm" data-datatable-page-entities>
                            <option value="10">10 / page</option>
                            <option value="25">25 / page</option>
                            <option value="50">50 / page</option>
                        </select>
                    </div>
                    <button id="btn-remove" class="btn btn-soft btn-error btn-sm" disabled>Remove</button>
                    <div class="flex gap-2 ms-auto" wire:ignore.self>
                        <select class="select select-sm select-server">
                            <option value="">Server</option>
                            @foreach($servers as $server)
                            <option value="{{$server['name']}}">{{$server['name']}}</option>
                            @endforeach
                        </select>
                        <select class="select select-sm select-profile">
                            <option value="">Profile</option>
                            @foreach($profiles as $profile)
                            <option value="{{$profile['name']}}">{{$profile['name']}}</option>
                            @endforeach
                        </select>
                        <select class="select select-sm select-comment">
                            <option value="">Comment</option>
                            @foreach($comments as $comment)
                            <option value="{{$comment}}">{{$comment}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex-grow h-90 overflow-x-auto" wire:ignore>
                    <div class="inline-block min-w-full align-middle">
                        <div class="relative">
                            <table class="table min-w-full">
                                <thead class="bg-base-100 sticky top-0 z-5 shadow">
                                    <tr class="text-xs">
                                        <th scope="col" class="--exclude-from-ordering w-3.5 pe-0">
                                            <div class="flex h-5">
                                                <input id="table-checkbox-all" type="checkbox" class="checkbox checkbox-xs" />
                                                <label for="table-checkbox-all" class="sr-only">Checkbox</label>
                                            </div>
                                        </th>
                                        <th>Server</th>
                                        <th>Name</th>
                                        <th>Profile</th>
                                        <th>Uptime</th>
                                        <th>Bytes In</th>
                                        <th>Bytes Out</th>
                                        <th>Comment</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="border-base-content/25 flex items-center justify-between gap-3 border-t p-3 max-md:flex-wrap max-md:justify-center" wire:ignore>
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

    <x-modal.confirm />
    <x-modal.hotspot-user-form />
    <x-modal.hotspot-user-edit-form />
    <x-modal.hotspot-user-print-form />
</div>

@script
<script>
document.addEventListener('livewire:navigated', () => 
{
    table = new HSDataTable('#datatable', {
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
                data: '',
                render: function () {
                    return '<input type="checkbox" class="checkbox checkbox-xs" data-datatable-row-selecting-individual="" />';
                },
            },
            { 
                data: 'server',
                render: (data) => {
                    return data ?? 'All';
                }
            },
            { data: 'name' },
            {
                data: 'profile',
                render: (data) => {
                    return data ?? 'default';
                }
            },
            {
                data: "uptime",
                render: function (data) {
                    return formatTimes(data);
                },
            },
            {
                data: "bytes-in",
                render: function (data) {
                    return formatBytes(data);
                },
            },
            {
                data: "bytes-out",
                render: function (data) {
                    return formatBytes(data);
                },
            },
            { 
                data: 'comment',
                render: (data) => {
                    return data ?? '';
                }
             },
            { 
                data: '',
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
            }
        ],
    });

    table.dataTable.search.fixed("server", function (row, data) {
        let server = $(".select-server").val();
        return server === data['server'] || server === '';
    });

    table.dataTable.search.fixed("profile", function (row, data) {
        let profile = $(".select-profile").val();
        return profile === data['profile'] || profile === '';
    });

    table.dataTable.search.fixed("comment", function (row, data) {
        let comment = $(".select-comment").val();
        return comment === data['comment'] || comment === '';
    });
}, { once: true });

$(".select-server").on("change", function () {
    table.dataTable.draw();
});

$(".select-profile").on("change", function () {
    table.dataTable.draw();
});

$(".select-comment").on("change", function () {
    table.dataTable.draw();
});

$("#content").on("click", ".btn-add", function () {
    const form = $("#hotspot-user-edit-form");
    form[0].reset();
    form.find('input[name="id"]').val('');

    HSOverlay.open("#hotspot-user-edit-form-modal");
});

$("#content").on("click", ".btn-edit", function () {
    const row = $(this).closest("tr");
    const data = table.dataTable.row(row).data();
    const form = $("#hotspot-user-edit-form");

    form[0].reset();
    form.find('input[name="item.id"]').val(data.id);
    form.find('select[name="item.server"]').val(data.server ?? 'all');
    form.find('input[name="item.name"]').val(data.name);
    form.find('input[name="item.password"]').val(data.password);
    form.find('select[name="item.profile"]').val(data.profile);
    form.find('input[name="item.comment"]').val(data.comment);

    HSOverlay.open("#hotspot-user-edit-form-modal");
});

$("#content").on("submit", "#hotspot-user-print-form", function (e) {
    e.preventDefault();
    const form = $(this);
    const btn = form.find(".btn-submit");
    const text = btn.html();
    const url = "/hotspot/users/print";
    const data = form.serializeArray();

    btn.attr("disabled", true).html(
        '<span class="icon-[svg-spinners--90-ring-with-bg] size-5"></span>'
    );

    $.get(url, data, function (res) {
        let newWin = window.open("");
        newWin.document.write(res);
        btn.attr("disabled", false).html(text);
    });
});
</script>
@endscript
