$("#connect").on("submit", function (e) {
    e.preventDefault();
    const data = $(this).serializeArray();
    const btn = $(this).find(".btn-submit");
    btn.attr("disabled", true).html(
        '<span class="icon-[svg-spinners--90-ring-with-bg] size-5"></span>'
    );

    $.post("/", data, function (res) {
        if (res.success) {
            showAlert("success", res.message, "tabler--circle-check");
            location.href = "/dashboard";
        } else {
            showAlert("error", res.message, "tabler--alert-circle");
            btn.attr("disabled", false).html("Connect");
        }
    });
});

$("#content").on("click", ".btn-connect", function () {
    const bt = $(this);
    const id = $(this).attr("data-id");
    bt.attr("disabled", true).html(
        '<span class="icon-[svg-spinners--90-ring-with-bg] size-5"></span>'
    );

    $.post("/router/connect", { id: id }, function (res) {
        if (res.success) {
            location.href = "/dashboard";
            showAlert("seuccess", res.message, "tabler--circle-check");
        } else {
            bt.attr("disabled", false).html(
                '<span class="icon-[tabler--plug-connected] size-5"></span>'
            );
        }
    });
});

$("#content").on("click", ".btn-delete", function () {
    const bt = $(this);
    const id = $(this).attr("data-id");
    bt.html(
        '<span class="icon-[svg-spinners--90-ring-with-bg] size-4"></span>'
    );

    $.post("/router/delete", { id: id }, function (res) {
        if (res.success) {
            $.get("/", function (data) {
                $("#tabs-saved").html($(data).find("#tabs-saved").html());
                showAlert("success", res.message, "tabler--circle-check");
            });
        }
    });
});
