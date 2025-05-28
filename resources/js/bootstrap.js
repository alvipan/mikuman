import lodash from "lodash";
import axios from "axios";
import jQuery from "jquery";
import ApexCharts from "apexcharts";
import DataTable from "datatables.net";

window._ = lodash;
window.$ = window.jQuery = jQuery;
window.ApexCharts = ApexCharts;
window.DataTable = DataTable;
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
