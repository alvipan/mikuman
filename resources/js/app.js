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
