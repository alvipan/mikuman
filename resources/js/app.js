import "./bootstrap";
import "flyonui/flyonui";
import "../../node_modules/flyonui/dist/helper-apexcharts.js";

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

window.showAlert = function (t, m, icon) {
    const e =
        '<div class="alert alert-type py-2 flex items-start gap-2">icon <span>message<span></div>';
    const i = '<span class="icon-[' + icon + '] size-6"></span>';
    const html = e.replace("type", t).replace("icon", i).replace("message", m);
    $("#alert").html($(html).hide().fadeIn(1000).delay(3000).fadeOut(1000));
};

window.formatBytes = function (bytes, decimals = 0) {
    if (bytes <= 0) return "0 Bytes";

    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ["Bytes", "KiB", "MiB", "GiB"];

    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
};
