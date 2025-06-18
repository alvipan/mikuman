import flatpickr from "flatpickr";

window.addEventListener("load", function () {
    const reportTable = new HSDataTable("#report-table", {
        pageLength: 10,
        pagingOptions: {
            pageBtnClasses: "btn btn-text btn-square btn-sm",
        },
        ajax: "/get/report",
        columns: [
            { data: "date" },
            { data: "time" },
            { data: "user" },
            { data: "profile" },
            { data: "ip-address" },
            { data: "mac-address" },
            { data: "comment" },
            {
                data: "price",
                render: function (data) {
                    let currency = $("#setting-currency").val();
                    let price = new Intl.NumberFormat().format(data);
                    return (
                        '<span class="currency">' +
                        currency +
                        "</span> " +
                        price
                    );
                },
            },
        ],
    });

    let summary = {
        voucher: 0,
        income: 0,
    };

    $("#page-entities").on("change", function () {
        summary.voucher = 0;
        summary.income = 0;
        reportTable.dataTable.draw();
    });

    let start = new Date(new Date().getFullYear(), new Date().getMonth(), 1);
    let end = new Date();

    reportTable.dataTable.search.fixed("date", function (row, data, index) {
        var min = start;
        var max = end;
        var date = new Date(data["date"]);

        if (
            (min === null && max === null) ||
            (min === null && date <= max) ||
            (min <= date && max === null) ||
            (min <= date && date <= max)
        ) {
            summary.voucher++;
            summary.income += parseInt(data["price"].replaceAll(",", ""));
            $("#voucher").html(new Intl.NumberFormat().format(summary.voucher));
            $("#income").html(new Intl.NumberFormat().format(summary.income));
            return true;
        }

        return false;
    });

    flatpickr("#date-range", {
        mode: "range",
        dateFormat: "M/d/Y",
        defaultDate:
            new Date(new Date().getFullYear(), new Date().getMonth(), 1) +
            " to " +
            new Date(),
        onChange: function (dates) {
            start = dates[0];
            end = dates.length > 1 ? dates[1] : dates[0];
            summary.voucher = 0;
            summary.income = 0;
            reportTable.dataTable.draw();
        },
    });
});
