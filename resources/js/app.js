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

// Thiết lập token từ meta tag
document.addEventListener('DOMContentLoaded', () => {
    const token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    }
});

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
