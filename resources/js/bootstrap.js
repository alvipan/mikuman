import lodash from "lodash";
import axios from "axios";
import jQuery from "jquery";
import ApexCharts from "apexcharts";

window._ = lodash;
window.$ = window.jQuery = jQuery;
window.ApexCharts = ApexCharts;
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
