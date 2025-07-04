import { createRouter, createWebHistory } from 'vue-router'
import DefaultLayout from '@/views/DefaultLayout.vue';
import TaskList from '@/views/TaskList.vue'
import DeletedTaskList from '@/views/DeletedTaskList.vue'

const routes = [
  {
    path: '/',
    component: DefaultLayout,
    children: [
      {
        path: 'tasks',
        name: 'Tasks',
        component: TaskList,
      },
       {
        path: 'deleted-tasks',
        name: 'Deleted tasks',
        component: DeletedTaskList,
      },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router