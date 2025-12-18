import "./bootstrap";
import "flyonui/flyonui";
import jQuery from "jquery";
window.$ = window.jQuery = jQuery;

window.table;

window.showAlert = function (t, m, i) {
    const alert = $("#alert").find(".alert-" + t);
    alert.find(".icon").html('<span class="icon-[' + i + '] size-6"></span>');
    alert.find(".message").html(m);
    alert.fadeIn(1000).delay(3000).fadeOut(1000);
};

window.formatBytes = function (bytes, decimals = 0) {
    if (bytes <= 0) return "0 Bytes";

    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ["Bytes", "KiB", "MiB", "GiB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
};

window.formatTimes = function (x) {
    let w = x.substring(0, x.indexOf("w")),
        d = x.substring(w > 0 ? x.indexOf("w") + 1 : 0, x.indexOf("d")),
        h = x.substring(d > 0 ? x.indexOf("d") + 1 : 0, x.indexOf("h")),
        m = x.substring(h > 0 ? x.indexOf("h") + 1 : 0, x.indexOf("m")),
        s = x.substring(m > 0 ? x.indexOf("m") + 1 : 0, x.indexOf("s"));

    d = w * 7 + parseInt(d);
    h = h > 0 ? (h < 10 ? "0" + h : h) : "00";
    m = m > 0 ? (m < 10 ? "0" + m : m) : "00";
    s = s > 0 ? (s < 10 ? "0" + s : s) : "00";

    return (d > 0 ? d + "d " : "") + h + ":" + m + ":" + s;
};

document.addEventListener('livewire:init', () => 
{
    let selected = [];

    Livewire.on('alert', (e) => {
        showAlert(e[0]['type'], e[0]['message']);
        console.log('err', e[0]['message'])
    });

    Livewire.on('update-data', (evn) => {
        table.dataTable.clear().rows.add(evn.data).draw();
        $("#btn-remove").attr("disabled", true);
        console.log('info', evn.data)
    });

    $.get("/get/expire-monitor", function (res) {
        if (res.success) {
            if (res.data.mikuman <= 0) {
                HSOverlay.open("#expire-monitor-form-modal");
            }
        }
    });

    let pathList = [
        '/hotspot/profiles',
        '/hotspot/users',
        '/hotspot/active',
        '/pppoe/profiles',
        '/pppoe/secrets',
        '/pppoe/active'
    ];

    if (pathList.includes(location.pathname)) 
    {
        $('#datatable').on('change','thead input[type="checkbox"]', function() {
            $('tbody input[type="checkbox"]').trigger("change");
        });

        $('#datatable').on('change','tbody input[type="checkbox"]', function() {
            const row = $(this).closest("tr");
            if ($(this).is(":checked")) {
                row.addClass("selected");
            } else {
                row.removeClass("selected");
            }
            $("#btn-remove").attr(
                "disabled",
                table.dataTable.rows(".selected").data().length <= 0
            );
        });

        $("#content").on("click", "#btn-remove", function () {
            selected = table.dataTable.rows(".selected").data().toArray();
            remove();
        });

        $("#content").on("click", ".btn-remove", function () {
            const row = $(this).closest("tr");
            selected = [];
            selected.push(table.dataTable.row(row).data());
            remove();
        });

        $("#confirm-modal").on("click", ".btn-confirm", function () {
            Livewire.dispatch('remove', { selected: selected });
        });

        function remove() {
            HSOverlay.open("#confirm-modal");
            const modal = $("#confirm-modal");
            const title = modal.find(".title");
            const body = modal.find(".modal-body");

            title.html("Confirmation");
            body.html(
                '<p class="mb-2">The items below will be removed.</p>' +
                '<items></items>'
            );

            $.each(selected, function (i, v) {
                body.find("items").append(
                    '<span class="badge badge-soft badge-error badge-sm me-1">' +
                    (v.name ? v.name : v.user) +
                    '</span>'
                );
            });
        }
    }
});

document.addEventListener('livewire:navigated', () => {

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $("#expire-monitor-form").on("submit", function (e) {
        e.preventDefault();
        const form = $(this);
        const data = form.serializeArray();
        const btn = form.find(".btn-submit");
        const btnText = btn.html();

        btn.attr("disabled", true).html(
            '<span class="icon-[svg-spinners--90-ring-with-bg] size-5"></span>'
        );

        $.post("/post/expire-monitor", data, function (res) {
            if (res.success) {
                HSOverlay.close("#expire-monitor-form-modal");
                showAlert("success", res.message);
                btn.attr("disabled", false).html(btnText);
            }
        });
    });

    $("#setting-theme").on("change", function () {
        $("html").attr("data-theme", $(this).val());
    });

    $("#setting-currency").on("change", function () {
        $(".currency").html($(this).val());
    });

    $("#form-setting").on("submit", function (e) {
        e.preventDefault();
        const form = $(this);
        const data = form.serializeArray();
        const btn = form.find(".btn-submit");
        const url = "/router/edit";

        btn.attr("disabled", true).html(
            '<span class="icon-[svg-spinners--90-ring-with-bg] size-5"></span>'
        );

        $.post(url, { data: data }, function (res) {
            if (res.success) {
                showAlert("success", "Changes have been saved.");
            } else {
                showAlert("error", res.message);
            }

            btn.attr("disabled", false).html("Save changes");
        });
    });

    HSStaticMethods.autoInit();
});
