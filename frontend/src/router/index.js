import { createRouter, createWebHistory } from 'vue-router'
import { useUserStore } from '@/stores/userStore';
import SidebarLayout from '@/views/SidebarLayout.vue';
import Login from '@/views/auth/LoginView.vue'
import Register from '@/views/auth/RegisterView.vue'
import TaskList from '@/views/task/TaskListView.vue'

const routes = [
  {
    path: '/',
    name: 'Login',
    component: Login,
    meta: { 
      title: 'Login',
      guest: true
    },
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
    meta: { 
      title: 'Register',
      guest: true
    },
  },
  {
    path: '/tasks',
    component: SidebarLayout,
    children: [
      {
        path: '',
        name: 'Tasks',
        component: TaskList,
        meta: { 
          title: 'Tasks',
          requiresAuth: true
        },
      }
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const userStore = useUserStore();

  if (to.meta.requiresAuth && !userStore.isAuthenticated) {
    next({ name: 'Login' });
    return;
  } 
  
  if (to.meta.guest && userStore.isAuthenticated) {
    next({ name: 'Tasks' });
    return;
  } 

  next();
});

export default router