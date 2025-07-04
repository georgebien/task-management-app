import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import './style.css'
import App from './App.vue'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'

const app = createApp(App)

router.beforeEach((to, from, next) => {
  const defaultTitle = 'Task Manager'
  document.title = to.meta.title || defaultTitle
  next()
})

app.use(createPinia())
app.use(router)
app.mount('#app')
