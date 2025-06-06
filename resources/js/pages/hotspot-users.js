window.addEventListener("load", function () {
    const userTable = new HSDataTable("#users-table", {
        pageLength: 10,
        pagingOptions: {
            pageBtnClasses: "btn btn-text btn-square btn-sm",
        },
        ajax: "/get/hotspot/users",
        columns: [
            { data: "server", defaultContent: "All" },
            { data: "name" },
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
            { data: "comment", defaultContent: "" },
        ],
    });
});
