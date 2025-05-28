window.addEventListener("load", function () {
    const logTable = new HSDataTable("#logs-table", {
        pageLength: 10,
        pagingOptions: {
            pageBtnClasses: "btn btn-text btn-square btn-sm",
        },
        ajax: "/get/logs",
        columns: [
            { data: "time" },
            {
                data: "message",
                render: function (data) {
                    return data.includes("pppoe") ? "PPPoE" : "Hotspot";
                },
            },
            {
                data: "message",
                render: function (data) {
                    if (data.includes("pppoe")) {
                        let x = data.replace("<", "").replace(">", "");
                        return x.split(": ")[0].split("-")[1];
                    }
                    let x = data.split(": ");
                    return x[0] != "->"
                        ? x[0].split(" ")[0]
                        : x[1].split(" ")[0];
                },
            },
            {
                data: "message",
                render: function (data) {
                    if (data.includes("pppoe")) {
                        return "";
                    }
                    let x = data.split(": ");
                    return x[0] != "->"
                        ? x[0].split(" ")[1].replace("(", "").replace(")", "")
                        : x[1].split(" ")[1].replace("(", "").replace(")", "");
                },
            },
            {
                data: "message",
                render: function (data) {
                    if (data.includes("pppoe")) {
                        let x = data
                            .replace("<", "")
                            .replace(">", "")
                            .split(": ");
                        return x[1][0].toUpperCase() + x[1].slice(1);
                    }
                    let x = data.split("): ");
                    return x[1][0].toUpperCase() + x[1].slice(1);
                },
            },
        ],
    });

    $(".btn-reload").on("click", function () {
        logTable.dataTable.ajax.reload();
    });
});
