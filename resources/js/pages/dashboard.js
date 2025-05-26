window.addEventListener("load", function () {
    const selectInterface = HSSelect.getInstance("#select-interface");
    // Default Apexchart init
    const chartEl = document.querySelector("#traffic-chart");
    const chartConfig = {
        chart: {
            height: 210,
            type: "area",
            toolbar: {
                show: false,
            },
            zoom: {
                enabled: false,
            },
            animations: {
                enabled: false,
            },
        },
        series: [
            {
                name: "Tx",
                data: [0, 0, 0, 0, 0, 0],
            },
            {
                name: "Rx",
                data: [0, 0, 0, 0, 0, 0],
            },
        ],
        legend: {
            show: true,
            position: "top",
            horizontalAlign: "right",
            labels: {
                useSeriesColors: true,
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: "smooth",
            width: 2,
        },
        grid: {
            strokeDashArray: 2,
            borderColor:
                "color-mix(in oklab, var(--color-base-content) 40%, transparent)",
        },
        colors: ["var(--color-accent)", "var(--color-error)"],
        fill: {
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                gradientToColors: ["var(--color-base-100)"],
                opacityTo: 0.3,
                stops: [0, 90, 100],
            },
        },
        xaxis: {
            type: "category",
            tickPlacement: "on",
            categories: [
                "00:00:00",
                "00:00:00",
                "00:00:00",
                "00:00:00",
                "00:00:00",
                "00:00:00",
            ],
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
            crosshairs: {
                stroke: {
                    dashArray: 0,
                },
                dropShadow: {
                    show: false,
                },
            },
            tooltip: {
                enabled: false,
            },
            labels: {
                style: {
                    colors: "var(--color-base-content)",
                    fontSize: "12px",
                    fontWeight: 400,
                },
                formatter: (title) => {
                    let t = title;
                    return t;
                },
            },
        },
        yaxis: {
            labels: {
                align: "left",
                minWidth: 0,
                maxWidth: 140,
                style: {
                    colors: "var(--color-base-content)",
                    fontSize: "12px",
                    fontWeight: 400,
                },
                formatter: (value) => formatBytes(value),
            },
        },
        tooltip: {
            enabled: false,
        },
    };
    const trafficChart = new ApexCharts(chartEl, chartConfig);
    trafficChart.render();

    function getData() {
        $.when(
            $.get("/system/resources", function (res) {
                $("#board-name").html(res[0]["board-name"]);
                $("#architecture-name").html(
                    res[0]["architecture-name"].toUpperCase()
                );
                $("#version").html(res[0]["version"]);
                $("#build-time").html(res[0]["build-time"]);

                $("#cpu-count").html(res[0]["cpu-count"]);
                $("#cpu-frequency").html(res[0]["cpu-frequency"]);
                $("#cpu-progress").css("width", res[0]["cpu-load"] + "%");

                $("#total-memory").html(formatBytes(res[0]["total-memory"]));
                $("#memory-progress").css(
                    "width",
                    (100 / res[0]["total-memory"]) *
                        (res[0]["total-memory"] - res[0]["free-memory"])
                );

                $("#total-hdd-space").html(
                    formatBytes(res[0]["total-hdd-space"])
                );
                $("#hdd-progress").css(
                    "width",
                    (100 / res[0]["total-hdd-space"]) *
                        (res[0]["total-hdd-space"] - res[0]["free-hdd-space"])
                );
            }),
            $.get("/system/health", function (res) {
                $("#voltage").html(res[0]["value"] + "V");
                $("#temperature").html(res[1]["value"] + "&deg;C");
            }),
            $.get("/hotspot/users?-default=", function (res) {
                $("#hotspot-users").html(res.length);
            }),
            $.get("/hotspot/active", function (res) {
                $("#hotspot-active").html(res.length);
            }),
            $.get("/ppp/users", function (res) {
                $("#ppp-users").html(res.length);
            }),
            $.get("/ppp/active", function (res) {
                $("#ppp-active").html(res.length);
            }),
            $.get("/get/income?summary=", function (res) {
                $("#income-today").html("Rp " + res.today);
                $("#income-yesterday").html("Rp " + res.yesterday);
                $("#income-this-month").html("Rp " + res["this-month"]);
                $("#income-last-month").html("Rp " + res["last-month"]);
            }),
            $.get(
                "/get/interface/traffic?interface=HOTSPOT&once=",
                function (res) {
                    chartConfig.series[0].data.shift();
                    chartConfig.series[0].data.push(
                        res[0]["tx-bits-per-second"]
                    );
                    chartConfig.series[1].data.shift();
                    chartConfig.series[1].data.push(
                        res[0]["rx-bits-per-second"]
                    );

                    let d = new Date();
                    let t =
                        d.getHours() +
                        ":" +
                        d.getMinutes() +
                        ":" +
                        d.getSeconds();

                    chartConfig.xaxis.categories.shift();
                    chartConfig.xaxis.categories.push(t);

                    trafficChart.updateSeries(chartConfig.series);
                    trafficChart.updateOptions({
                        xaxis: {
                            categories: chartConfig.xaxis.categories,
                        },
                    });
                }
            ),
            $.get("/get/logs?buffer=disk", function (res) {
                $("#logs").html("");
                const tr =
                    "<tr><td>time</td><td class='ps-0'>message</td></tr>";
                $.each(res.reverse(), function (i) {
                    $("#logs").append(
                        tr
                            .replace("time", res[i]["time"])
                            .replace(
                                "message",
                                res[i]["message"].replace("->:", "")
                            )
                    );
                });
            })
        ).then(
            function () {
                setTimeout(function () {
                    getData();
                }, 2500);
            },
            function () {
                setTimeout(function () {
                    getData();
                }, 2500);
            }
        );
    }
    getData();
});
