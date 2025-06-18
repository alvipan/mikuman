window.addEventListener("load", function () 
{
    let selected = [];
    const activeTable = new HSDataTable("#active-table", {
        pageLength: 10,
        pagingOptions: {
            pageBtnClasses: "btn btn-text btn-square btn-sm",
        },
        rowSelectingOptions: {
            selectAllSelector: "#table-checkbox-all"
        },
        ajax: "/get/hotspot/active",
        columns: [
            {
                data: "", 
                render: function() {
                    return ('<input type="checkbox" class="checkbox checkbox-xs" data-datatable-row-selecting-individual="" />');
                } 
            },
            { data: "server", defaultContent: "All" },
            { 
                data: "user",
                render: function(data) {
                    return '<span class="font-mono">'+data+'</span>';
                }
            },
            { data: "address" },
            { data: "mac-address" },
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
            { data: "comment", defaultContent: "" },
            {
                data: "id",
                render: function (data) {
                    return (
                        '<button class="btn btn-circle btn-text btn-remove btn-error btn-xs">' +
                        '<span class="icon-[tabler--trash] size-4"></span>' +
                        "</button>"
                    );
                },
            },
        ],
    });

    $("#content").on("click", ".btn-reload", function () {
        activeTable.dataTable.ajax.reload();
    });

    $("#content").on("click", "#btn-remove", function() {
        const data = activeTable.dataTable.rows('.selected').data();
        removeActive(data);
    });

    $("#content").on("click", ".btn-remove", function () {
        const data = [];
        const row = $(this).closest("tr");
        data.push(activeTable.dataTable.row(row).data());
        removeActive(data);
    });

    activeTable.dataTable.on('change', 'thead input[type="checkbox"]', function() {
        $('tbody tr input[type="checkbox"]').trigger('change');
    });

    activeTable.dataTable.on('change', 'tbody input[type="checkbox"]', function() {
        const row = $(this).closest('tr');
        if ($(this).is(':checked')) {
            row.addClass('selected');
        } else {
            row.removeClass('selected');
        }
        $('#btn-remove').attr('disabled', (activeTable.dataTable.rows('.selected').data().length <= 0));
    });

    function removeActive(data) {
        HSOverlay.open("#confirm-modal");
        const modal = $("#confirm-modal");
        const title = modal.find(".title");
        const body = modal.find(".modal-body");

        title.html("Confirmation");
        body.html(
            "<p>The hotspot active user below will be deleted.</p>" +
            "<items></items>"
        );

        selected = [];
        $.each(data, function(i, v) {
            selected.push(v.id);
            body.find('items').append(
                '<span class="badge badge-soft badge-error badge-sm">'+
                v.user+
                '</span> '
            );
        });
    }

    $("#confirm-modal").on("click", ".btn-confirm", function () {
        const btn = $(this);
        btn.html(
            '<span class="icon-[svg-spinners--90-ring-with-bg] size-4"></span>'
        );
        $.post("/hotspot/remove", { type: 'active', data: selected }, function(res) {
            if (res.success) {
                showAlert("success", res.message, "tabler--circle-check");
                activeTable.dataTable.ajax.reload();
            } else {
                showAlert("error", res.message, "tabler--alert");
            }
            btn.html("Confirm");
            HSOverlay.close("#confirm-modal");
            $("#btn-remove").attr("disabled", true);
        });
    });
});
