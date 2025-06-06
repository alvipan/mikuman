window.addEventListener("load", function () {
    const activeTable = new HSDataTable("#active-table", {
        pageLength: 10,
        pagingOptions: {
            pageBtnClasses: "btn btn-text btn-square btn-sm",
        },
        ajax: "/get/hotspot/active",
        columns: [
            { data: "server", defaultContent: "All" },
            { data: "user" },
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
            { data: "login-by" },
            { data: "comment", defaultContent: "" },
        ],
    });

    $(".btn-reload").on("click", function () {
        activeTable.dataTable.ajax.reload();
    });
});
