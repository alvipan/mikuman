window.addEventListener("load", function () {
    const userTable = new HSDataTable("#users-table", {
        pageLength: 10,
        pagingOptions: {
            pageBtnClasses: "btn btn-text btn-square btn-sm",
        },
        ajax: "/get/hotspot/users",
        columns: [
            { data: "server", defaultContent: "All" },
            {
                data: "name",
                render: function (data) {
                    return '<span class="font-mono">' + data + "</span>";
                },
            },
            { data: "profile", defaultContent: "" },
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
                data: "comment",
                defaultContent: "",
            },
            {
                data: "id",
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

    function submit(form, url, data) {
        const btn = form.find(".btn-submit");
        const text = btn.html();
        btn.attr("disabled", true).html(
            '<span class="icon-[svg-spinners--90-ring-with-bg] size-5"></span>'
        );

        $.post(url, data, function (res) {
            if (res.success) {
                showAlert("success", res.message, "tabler--circle-check");
                userTable.dataTable.ajax.reload();
                HSOverlay.close(
                    text == "Generate"
                        ? "#hotspot-user-form-modal"
                        : "#hotspot-user-edit-form-modal"
                );
                form[0].reset();
            } else {
                showAlert("error", res.message, "tabler--alert-circle");
            }
            btn.attr("disabled", false).html(text);
        });
    }

    $("#content").on("submit", "#hotspot-user-form", function (e) {
        e.preventDefault();
        const form = $(this);
        const url = "/hotspot/users/generate";
        const data = form.serializeArray();

        submit(form, url, data);
    });

    $("#content").on("submit", "#hotspot-user-edit-form", function (e) {
        e.preventDefault();
        const form = $(this);
        const url = "/hotspot/users/edit";
        const data = form.serializeArray();

        submit(form, url, data);
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

    $("#content").on("click", ".btn-edit", function () {
        HSOverlay.open("#hotspot-user-edit-form-modal");
        const row = $(this).closest("tr");
        const data = userTable.dataTable.row(row).data();
        const form = $("#hotspot-user-edit-form");

        form[0].reset();
        form.find('input[name="id"]').val(data.id);
        form.find('select[name="server"]').val(data.server);
        form.find('input[name="name"]').val(data.name);
        form.find('input[name="password"]').val(data.password);
        form.find('select[name="profile"]').val(data.profile);
        form.find('input[name="comment"]').val(data.comment);
    });

    $("#content").on("click", ".btn-remove", function () {
        HSOverlay.open("#confirm-modal");
        const row = $(this).closest("tr");
        const data = userTable.dataTable.row(row).data();
        const modal = $("#confirm-modal");
        const title = modal.find(".modal-title");
        const body = modal.find(".modal-body");
        title.html("Confirmation");
        body.html(
            "Are you sure you want to delete the [" +
                data.name +
                "] hotspot user?"
        );

        modal.on("click", ".btn-confirm", function () {
            const btn = $(this);
            btn.html(
                '<span class="icon-[svg-spinners--90-ring-with-bg] size-4"></span>'
            );
            $.post("/hotspot/users/remove", { id: data.id }, function (res) {
                if (res.success) {
                    showAlert("success", res.message, "tabler--circle-check");
                    userTable.dataTable.ajax.reload();
                } else {
                    showAlert("error", res.message, "tabler--alert");
                }
                btn.html("Confirm");
                HSOverlay.close("#confirm-modal");
            });
        });
    });

    $("#content").on("change", 'select[name="color"]', function () {
        $(".color").css("color", "var(--color-" + $(this).val() + ")");
    });
});
