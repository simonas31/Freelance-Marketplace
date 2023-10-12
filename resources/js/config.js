import axios from "axios";

window.axios = axios;
axios.defaults.withCredentials = true;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.headers.common["Content-Type"] = "application/json";
window.axios.defaults.baseURL = "http://127.0.0.1:8000/";