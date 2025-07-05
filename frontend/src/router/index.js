import { createRouter, createWebHistory } from 'vue-router'
import { useUserStore } from '@/stores/userStore';
import SidebarLayout from '@/views/SidebarLayout.vue';
import Login from '@/views/auth/LoginView.vue'
import TaskList from '@/views/task/TaskListView.vue'

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
    meta: { 
      title: 'Tasks',
      requiresAuth: true
    },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const userStore = useUserStore();

  if (to.meta.requiresAuth && !userStore.isAuthenticated) {
    next({ name: 'login' });
  } else {
    next();
  }
});

export default router