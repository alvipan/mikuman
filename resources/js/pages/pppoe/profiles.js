window.addEventListener("load", function () {
    let selected = [];
    const profileTable = new HSDataTable("#profile-table", {
        pageLength: 10,
        pagingOptions: {
            pageBtnClasses: "btn btn-text btn-square btn-sm",
        },
        rowSelectingOptions: {
            selectAllSelector: "#table-checkbox-all",
        },
        ajax: {
            url: "/get/pppoe/profiles",
            dataSrc: "",
        },
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

    $("#content").on("submit", "#pppoe-profile-form", function (e) {
        e.preventDefault();
        const form = $(this);
        const data = form.serializeArray();
        const btn = form.find(".btn-submit");
        const url = "/pppoe/profiles/submit";
        btn.attr("disabled", true).html(
            '<span class="icon-[svg-spinners--90-ring-with-bg] size-5"></span>'
        );

        $.post(url, data, function (res) {
            if (res.success) {
                showAlert("success", res.message, "tabler--circle-check");
                profileTable.dataTable.ajax.reload();
                HSOverlay.close("#pppoe-profile-form-modal");
                form[0].reset();
            } else {
                showAlert("error", res.message, "line-md--alert-circle");
            }
            btn.attr("disabled", false).html("Save");
        });
    });

    $("#content").on("click", ".btn-add", function () {
        HSOverlay.open("#pppoe-profile-form-modal");
        HSTabs.open(document.querySelector("#tab-general"));
        const form = $("#pppoe-profile-form");
        form[0].reset();
        form.find('select[name="expmode"]').trigger("change");
    });

    $("#content").on("click", ".btn-edit", function () {
        HSOverlay.open("#pppoe-profile-form-modal");
        const row = $(this).closest("tr");
        const form = $("#pppoe-profile-form");
        const data = profileTable.dataTable.row(row).data();

        form[0].reset();
        form.find('input[name="id"]').val(data.id);
        form.find('input[name="name"]').val(data.name);
        form.find('input[name="rate-limit"]').val(data["rate-limit"]);
        form.find('select[name="parent-queue"]').val(data["parent-queue"]);
        form.find('select[name="only-one"]').val(data["only-one"]);
        form.find('input[name="dns-server"]').val(data["dns-server"]);
    });

    $("#content").on("click", "#btn-remove", function () {
        const data = profileTable.dataTable.rows(".selected").data();
        removeProfile(data);
    });

    $("#content").on("click", ".btn-remove", function () {
        const data = [];
        const row = $(this).closest("tr");
        data.push(profileTable.dataTable.row(row).data());
        removeProfile(data);
    });

    profileTable.dataTable.on(
        "change",
        'thead input[type="checkbox"]',
        function () {
            $('tbody tr input[type="checkbox"]').trigger("change");
        }
    );

    profileTable.dataTable.on(
        "change",
        'tbody input[type="checkbox"]',
        function () {
            const row = $(this).closest("tr");
            if ($(this).is(":checked")) {
                row.addClass("selected");
            } else {
                row.removeClass("selected");
            }
            $("#btn-remove").attr(
                "disabled",
                profileTable.dataTable.rows(".selected").data().length <= 0
            );
        }
    );

    function removeProfile(data) {
        HSOverlay.open("#confirm-modal");
        const modal = $("#confirm-modal");
        const title = modal.find(".title");
        const body = modal.find(".modal-body");

        title.html("Confirmation");
        body.html(
            "<p>The PPPoE profile below will be deleted.</p>" +
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
            { type: "profile", data: selected },
            function (res) {
                if (res.success) {
                    showAlert("success", res.message, "tabler--circle-check");
                    profileTable.dataTable.ajax.reload();
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
