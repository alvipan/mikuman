import lodash from "lodash";
import axios from "axios";

window._ = lodash;
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
