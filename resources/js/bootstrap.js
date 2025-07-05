import lodash from "lodash";
import axios from "axios";
import jQuery from "jquery";
import DataTable from "datatables.net";

window._ = lodash;
window.$ = window.jQuery = jQuery;
window.DataTable = DataTable;
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
