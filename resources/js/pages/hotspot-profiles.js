window.addEventListener("load", function () {
    const profileTable = new HSDataTable("#profile-table", {
        pageLength: 10,
        pagingOptions: {
            pageBtnClasses: "btn btn-text btn-square btn-sm",
        },
        ajax: "/get/hotspot/profiles",
        columns: [
            { data: "name" },
            { data: "rate-limit", default: "" },
            { data: "validity", default: "" },
            { data: "price" },
            { data: "shared-users" },
            { data: "lock-users" },
            { data: "lock-server" },
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

    $("#content").on("submit", "#form-hotspot-profile", function (e) {
        e.preventDefault();
        const form = $(this);
        const data = form.serializeArray();
        const btn = form.find(".btn-submit");
        const url = "/hotspot/profiles/submit";

        btn.attr("disabled", true).html(
            '<span class="icon-[svg-spinners--90-ring-with-bg] size-5"></span>'
        );

        $.post(url, data, function (res) {
            if (res.success) {
                showAlert("success", res.message, "tabler--circle-check");
                profileTable.dataTable.ajax.reload();
                HSOverlay.close("#form-hotspot-profile-modal");
                form[0].reset();
            } else {
                showAlert("error", res.message, "tabler--alert-circle");
            }
            btn.attr("disabled", false).html("Save");
        });
    });

    $("#content").on("click", ".btn-add", function () {
        HSOverlay.open("#form-hotspot-profile-modal");
        HSTabs.open(document.querySelector("#tab-general"));
        const form = $("#form-hotspot-profile");
        form[0].reset();
        form.find('select[name="expmode"]').trigger("change");
    });

    $("#content").on("click", ".btn-edit", function () {
        HSOverlay.open("#form-hotspot-profile-modal");
        HSTabs.open(document.querySelector("#tab-general"));
        const row = $(this).closest("tr");
        const form = $("#form-hotspot-profile");
        const data = profileTable.dataTable.row(row).data();

        form[0].reset();
        form.find('input[name="id"]').val(data.id);
        form.find('input[name="name"]').val(data.name);
        form.find('select[name="ippool"]').val(data["ip-pool"]);
        form.find('input[name="sharedusers"]').val(data["shared-users"]);
        form.find('input[name="ratelimit"]').val(data["rate-limit"]);
        form.find('select[name="parentqueue"]').val(data["parent-queue"]);
        form.find('select[name="expmode"]')
            .val(data["expire-mode"])
            .trigger("change");
        form.find('input[name="validity"]').val(data.validity);
        form.find('input[name="price"]').val(data.price);
        form.find('input[name="sprice"]').val(data.sprice);
        form.find('select[name="ulock"]').val(data["lock-users"]);
        form.find('select[name="slock"]').val(data["lock-server"]);
    });

    $("#content").on("change", 'select[name="expmode"]', function () {
        const expmode = $(this).val();
        expmode
            ? $('input[name="validity"]').attr("disabled", false)
            : $('input[name="validity"]').attr("disabled", true);
    });

    $("#content").on("click", ".btn-remove", function () {
        const row = $(this).closest("tr");
        const data = profileTable.dataTable.row(row).data();
        const btn = $(this);
        const html = btn.html();

        if (confirm("Are you sure to remove user profile?")) {
            btn.html(
                '<span class="icon-[svg-spinners--90-ring-with-bg] size-4"></span>'
            );
            $.post("/hotspot/profiles/remove", { id: data.id }, function (res) {
                if (res.success) {
                    showAlert("success", res.message, "tabler--circle-check");
                    profileTable.dataTable.ajax.reload();
                } else {
                    showAlert("error", res.message, "tabler--alert");
                    btn.html(html);
                }
            });
        }
    });
});
