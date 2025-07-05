window.addEventListener("load", function () {
    let selected = [];
    let filter = {
        server: [],
        profile: [],
        comment: [],
    };
    const userTable = new HSDataTable("#users-table", {
        pageLength: 10,
        pagingOptions: {
            pageBtnClasses: "btn btn-text btn-square btn-sm",
        },
        rowSelectingOptions: {
            selectAllSelector: "#table-checkbox-all",
        },
        ajax: "/get/hotspot/users",
        columns: [
            {
                data: "",
                render: function () {
                    return '<input type="checkbox" class="checkbox checkbox-xs" data-datatable-row-selecting-individual="" />';
                },
            },
            {
                data: "server",
                defaultContent: "All",
                render: function (data) {
                    if (!filter.server.includes(data)) {
                        filter.server.push(data);
                        $(".select-server").append(
                            '<option value="' +
                                (data ? data : "All") +
                                '">' +
                                (data ? data : "All") +
                                "</option>"
                        );
                    }
                    return data;
                },
            },
            {
                data: "name",
                render: function (data) {
                    return '<span class="font-mono">' + data + "</span>";
                },
            },
            {
                data: "profile",
                defaultContent: "",
                render: function (data) {
                    let profile = data ? data : "none";
                    if (!filter.profile.includes(profile)) {
                        filter.profile.push(profile);
                        $(".select-profile").append(
                            '<option value="' +
                                profile +
                                '">' +
                                profile +
                                "</option>"
                        );
                    }
                    return profile;
                },
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
                data: "comment",
                defaultContent: "",
                render: function (data) {
                    if (
                        !filter.comment.includes(data) &&
                        String(data).includes("vc-")
                    ) {
                        filter.comment.push(data);
                        $(".select-comment").append(
                            '<option value="' +
                                (data ? data : "") +
                                '">' +
                                (data ? data : "") +
                                "</option>"
                        );
                    }
                    return data;
                },
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
                updateFilter();
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

    userTable.dataTable.search.fixed("server", function (row, data) {
        let server =
            $(".select-server").val() === "All"
                ? undefined
                : $(".select-server").val();

        return server === data.server || server === "";
    });

    userTable.dataTable.search.fixed("profile", function (row, data) {
        let profile = $(".select-profile").val();

        return (
            profile === "" ||
            data.profile === profile ||
            data.profile === undefined
        );
    });

    userTable.dataTable.search.fixed("comment", function (row, data) {
        let comment = $(".select-comment").val();

        return comment === data.comment || comment === "";
    });

    $(".select-server").on("change", function () {
        userTable.dataTable.draw();
    });

    $(".select-profile").on("change", function () {
        userTable.dataTable.draw();
    });

    $(".select-comment").on("change", function () {
        userTable.dataTable.draw();
    });

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

    $("#content").on("change", 'select[name="color"]', function () {
        $(".color").css("color", "var(--color-" + $(this).val() + ")");
    });

    $("#content").on("click", "#btn-remove", function () {
        const data = userTable.dataTable.rows(".selected").data();
        removeUser(data);
    });

    $("#content").on("click", ".btn-remove", function () {
        const data = [];
        const row = $(this).closest("tr");
        data.push(userTable.dataTable.row(row).data());
        removeUser(data);
    });

    userTable.dataTable.on(
        "change",
        'thead input[type="checkbox"]',
        function () {
            $('tbody tr input[type="checkbox"]').trigger("change");
        }
    );

    userTable.dataTable.on(
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
                userTable.dataTable.rows(".selected").data().length <= 0
            );
        }
    );

    function removeUser(data) {
        HSOverlay.open("#confirm-modal");
        const modal = $("#confirm-modal");
        const title = modal.find(".title");
        const body = modal.find(".modal-body");

        title.html("Confirmation");
        body.html("<p>The user below will be deleted.</p>" + "<items></items>");

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
            "/hotspot/remove",
            { type: "user", data: selected },
            function (res) {
                if (res.success) {
                    showAlert("success", res.message, "tabler--circle-check");
                    updateFilter();
                    userTable.dataTable.ajax.reload();
                } else {
                    showAlert("error", res.message, "tabler--alert");
                }
                btn.html("Confirm");
                HSOverlay.close("#confirm-modal");
                $("#btn-remove").attr("disabled", true);
            }
        );
    });

    function updateFilter() {
        filter = {
            server: [],
            profile: [],
            comment: [],
        };
        $(".select-server").html('<option value="">Server</option>');
        $(".select-profile").html('<option value="">Profile</option>');
        $(".select-comment").html('<option value="">Comment</option>');
    }
});
