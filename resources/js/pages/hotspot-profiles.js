window.addEventListener("load", function () {
    const profileTable = new HSDataTable("#profile-table", {
        pageLength: 15,
        pagingOptions: {
            pageBtnClasses: "btn btn-text btn-square btn-sm",
        },
        ajax: "/get/hotspot/profiles",
        columns: [
            { data: "name" },
            { data: "rate-limit" },
            { data: "validity", default: "" },
            { data: "price" },
            { data: "shared-users" },
            { data: "lock-users" },
            { data: "lock-server" },
        ],
    });
});
