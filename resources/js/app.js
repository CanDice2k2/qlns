import Vue from 'vue'
import VueMeta from 'vue-meta'
import PortalVue from 'portal-vue'
import { InertiaProgress } from '@inertiajs/progress'
import { createInertiaApp } from '@inertiajs/inertia-vue'
import axios from 'axios'

Vue.config.productionTip = false
Vue.mixin({ methods: { route: window.route } })
Vue.use(PortalVue)
Vue.use(VueMeta)

// Cấu hình CSRF token cho axios
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Đảm bảo Axios sử dụng cookies cho CSRF
axios.defaults.withCredentials = true;

// Hàm để lấy token CSRF hiện tại
function refreshCsrfToken() {
  const token = document.head.querySelector('meta[name="csrf-token"]');
  if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
  }
}

// Hàm lấy giá trị cookie theo tên
function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}

// Thiết lập token từ cookie trước mỗi request
axios.interceptors.request.use(config => {
  const token = getCookie('XSRF-TOKEN');
  if (token) {
    config.headers['X-XSRF-TOKEN'] = decodeURIComponent(token);
  }
  return config;
});

// Thiết lập ban đầu
document.addEventListener('DOMContentLoaded', refreshCsrfToken);

// Thiết lập sau khi chuyển trang (Inertia)
document.addEventListener('inertia:success', refreshCsrfToken);

// Thêm vào resources/js/app.js
axios.interceptors.response.use(
  response => response,
  error => {
    if (error.response && error.response.status === 419) {
      // Token hết hạn, lấy token mới
      refreshCsrfToken();

      // Thử lại request
      return axios(error.config);
    }
    return Promise.reject(error);
  }
);

InertiaProgress.init()

createInertiaApp({
  resolve: name => require(`./Pages/${name}`),
  setup({ el, app, props }) {
    new Vue({
      metaInfo: {
        titleTemplate: title => (title ? `${title} - MaiVanDuc` : 'MaiVanDuc'),
      },
      render: h => h(app, props),
    }).$mount(el)
  },
})
