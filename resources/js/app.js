import ApexCharts from 'apexcharts';

window.ApexCharts = ApexCharts;

window.formatBps = function (value) {

    if (value >= 1_000_000_000) {
        return (value / 1_000_000_000).toFixed(2) + ' Gbps'
    }

    if (value >= 1_000_000) {
        return (value / 1_000_000).toFixed(2) + ' Mbps'
    }

    if (value >= 1_000) {
        return (value / 1_000).toFixed(2) + ' Kbps'
    }

    return value + ' bps'
}