import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Ambil token CSRF dari meta tag dan set header default
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

// Sertakan cookie (XSRF-TOKEN) saat request ke domain yang sama
window.axios.defaults.withCredentials = true;
