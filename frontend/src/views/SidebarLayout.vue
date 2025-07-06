<template>
  <div class="d-flex">
    <aside
      class="bg-light border-end p-3 d-flex flex-column"
      style="width: 250px;"
    >
      <div>
        <h4 class="mb-4">
          Task Manager
        </h4>
        <nav>
          <ul class="nav flex-column">
            <li class="nav-item">
              <router-link
                class="nav-link"
                to="/tasks"
              >
                Tasks
              </router-link>
            </li>
          </ul>
        </nav>
      </div>

      <div class="mt-auto">
        <button
          class="btn btn-secondary w-100"
          @click="logout"
        >
          Logout
        </button>
      </div>
    </aside>

    <main class="w-100 p-4">
      <router-view />
    </main>
  </div>
</template>

<script>
import { useToast } from 'vue-toast-notification';
import { logout } from '@/services/authService';
import { useUserStore } from '@/stores/userStore';
import router from '../router';

const $toast = useToast();

export default {
  data() {
    return {
      email: null,
      password: null,
    };
  },
  methods: {
    async logout() {
      const userStore = useUserStore();
      const response = await logout();

      if (!response) {
        $toast.error('An unexpected error occurred');
        return;
      }

      userStore.clearUser();

      router.push('/');
    }
  },
};
</script>
