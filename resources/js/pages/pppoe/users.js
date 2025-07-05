window.addEventListener("load", function () {
    let selected = [];
    const table = new HSDataTable("#user-table", {
        pageLength: 10,
        pagingOptions: {
            pageBtnClasses: "btn btn-text btn-square btn-sm",
        },
        rowSelectingOptions: {
            selectAllSelector: "#table-checkbox-all",
        },
        ajax: {
            url: "/get/pppoe/users",
            dataSrc: "",
        },
        columns: [
            {
                data: "",
                render: function () {
                    return '<input type="checkbox" class="selector checkbox checkbox-xs" data-datatable-row-selecting-individual="" />';
                },
            },
            { data: "name" },
            { data: "service" },
            { data: "profile" },
            { data: "local-address" },
            { data: "remote-address" },
            {
                data: "",
                render: function (data) {
                    return (
                        '<button class="btn btn-circle btn-text btn-edit btn-xs">' +
                        '<span class="icon-[tabler--edit] size-4"></span>' +
                        "</button>" +
                        '<button class="btn btn-circle btn-text btn-remove btn-error btn-xs">' +
                        '<span class="icon-[tabler--trash] size-4"></span>' +
                        "</button>"
                    );
                },
            },
        ],
    });

    $("#content").on("submit", "#pppoe-user-form", function (e) {
        e.preventDefault();
        const form = $(this);
        const data = form.serializeArray();
        const btn = form.find(".btn-submit");
        const url = "/pppoe/users/submit";
        btn.attr("disabled", true).html(
            '<span class="icon-[svg-spinners--90-ring-with-bg] size-5"></span>'
        );

        $.post(url, data, function (res) {
            if (res.success) {
                showAlert("success", res.message, "tabler--circle-check");
                table.dataTable.ajax.reload();
                HSOverlay.close("#pppoe-user-form-modal");
                form[0].reset();
            } else {
                showAlert("error", res.message, "line-md--alert-circle");
            }
            btn.attr("disabled", false).html("Save");
        });
    });

    $("#content").on("click", ".btn-add", function () {
        HSOverlay.open("#pppoe-user-form-modal");
        const form = $("#pppoe-user-form");
        form[0].reset();
    });

    $("#content").on("click", ".btn-edit", function () {
        HSOverlay.open("#pppoe-user-form-modal");
        const row = $(this).closest("tr");
        const form = $("#pppoe-user-form");
        const data = table.dataTable.row(row).data();

        form[0].reset();
        form.find('input[name="id"]').val(data.id);
        form.find('input[name="name"]').val(data.name);
        form.find('input[name="password"]').val(data.password);
        form.find('select[name="profile"]').val(data.profile);
        form.find('input[name="local-address"]').val(data["local-address"]);
        form.find('input[name="remote-address"]').val(data["remote-address"]);
    });

    $("#content").on("click", "#btn-remove", function () {
        const data = table.dataTable.rows(".selected").data();
        removeUser(data);
    });

    $("#content").on("click", ".btn-remove", function () {
        const data = [];
        const row = $(this).closest("tr");
        data.push(table.dataTable.row(row).data());
        removeUser(data);
    });

    table.dataTable.on("change", 'thead input[type="checkbox"]', function () {
        $("tbody tr .selector").trigger("change");
    });

    table.dataTable.on("change", "tbody .selector", function () {
        const row = $(this).closest("tr");
        if ($(this).is(":checked")) {
            row.addClass("selected");
        } else {
            row.removeClass("selected");
        }
        $("#btn-remove").attr(
            "disabled",
            table.dataTable.rows(".selected").data().length <= 0
        );
    });

    function removeUser(data) {
        HSOverlay.open("#confirm-modal");
        const modal = $("#confirm-modal");
        const title = modal.find(".title");
        const body = modal.find(".modal-body");

        title.html("Confirmation");
        body.html(
            "<p>The PPPoE user(s) below will be deleted.</p>" +
                "<items></items>"
        );

        selected = [];
        $.each(data, function (i, v) {
            selected.push(v.id);
            body.find("items").append(
                '<span class="badge badge-soft badge-error badge-sm">' +
                    v.name +
                    "</span> "
            );
        });
    }

    $("#confirm-modal").on("click", ".btn-confirm", function () {
        const btn = $(this);
        btn.html(
            '<span class="icon-[svg-spinners--90-ring-with-bg] size-4"></span>'
        );
        $.post(
            "/pppoe/remove",
            { type: "user", data: selected },
            function (res) {
                if (res.success) {
                    showAlert("success", res.message, "tabler--circle-check");
                    table.dataTable.ajax.reload();
                } else {
                    showAlert("error", res.message, "tabler--alert");
                }
                btn.html("Confirm");
                HSOverlay.close("#confirm-modal");
                $("#btn-remove").attr("disabled", true);
            }
        );
    });
});
