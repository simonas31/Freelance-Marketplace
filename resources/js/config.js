import axios from "axios";
import Echo from "laravel-echo";
import Pusher from 'pusher-js';

window.Pusher = Pusher;
 
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

window.axios = axios;
axios.defaults.withCredentials = true;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.headers.common["Content-Type"] = "application/json";
window.axios.defaults.baseURL = "http://127.0.0.1:8000/";