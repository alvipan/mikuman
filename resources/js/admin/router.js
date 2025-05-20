$(".btn-connect").on("click", function () {
    const btn = $(this);
    btn.html(
        '<span class="icon-[svg-spinners--90-ring-with-bg] size-4"></span>'
    );
    const routerId = $(this).attr("data-id");
    $.get("/routers/connect", { id: routerId }, function (res) {
        if (res.success) {
            location.href = "/routers/dashboard";
        } else {
            btn.html(
                '<span class="icon-[tabler--plug-connected] size-4"></span>'
            );
            alert(res.message);
        }
    });
});

$("#content").on("click", ".btn-edit", function () {
    const routerId = $(this).attr("data-id");
    location.href = "/routers/edit/" + routerId;
});

$("#content").on("click", ".btn-delete", function () {
    const btn = $(this);
    const routerId = btn.attr("data-id");
    btn.html(
        '<span class="icon-[svg-spinners--90-ring-with-bg] size-4"></span>'
    );

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.post("/routers/delete", { id: routerId }, function (res) {
        if (res.success) {
            $.get("/routers", function (data) {
                $("#content").html($(data).find("#content").html());
            });
        }
    });
});
