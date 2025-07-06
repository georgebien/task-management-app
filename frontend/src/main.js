import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import api from './library/axios';
import './style.css';
import App from './App.vue';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';
import 'vue-good-table-next/dist/vue-good-table-next.css';
import '@vueform/multiselect/themes/default.css';

const app = createApp(App);
const pinia = createPinia();

app.config.globalProperties.$api = api;

router.beforeEach((to, from, next) => {
  const defaultTitle = 'Task Manager';
  document.title = to.meta.title || defaultTitle;
  next();
})

app.use(pinia);
app.use(router);
app.use(ToastPlugin, {
  position: 'top',
  duration: 5000,
  dismissible: true,
});

app.mount('#app');
