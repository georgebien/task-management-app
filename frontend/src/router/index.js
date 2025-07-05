import { createRouter, createWebHistory } from 'vue-router'
import SidebarLayout from '@/views/SidebarLayout.vue';
import Login from '@/views/auth/Login.vue'
import TaskList from '@/views/task/TaskList.vue'

const routes = [
  {
    path: '/',
    name: 'Login',
    component: Login,
    meta: { title: 'Login' },
  },
  {
    path: '/tasks',
    component: SidebarLayout,
    component: TaskList,
    meta: { title: 'Tasks' },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router