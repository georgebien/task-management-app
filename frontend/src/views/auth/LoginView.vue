<template>
  <div
    class="card shadow-lg p-4"
    style="width: 100%; max-width: 400px;"
  >
    <div class="card-body">
      <h2 class="card-title text-center mb-4">
        Login
      </h2>
      <form @submit.prevent="login">
        <div class="mb-3">
          <input
            v-model="email"
            placeholder="Email"
            type="email"
            class="form-control"
          >
        </div>
        <div class="mb-3">
          <input
            v-model="password"
            placeholder="Password"
            type="password"
            class="form-control"
          >
        </div>
        <div class="d-grid gap-2">
          <button
            type="submit"
            class="btn btn-primary"
          >
            Login
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { useToast } from 'vue-toast-notification';
import { login } from '../../services/authService';
import { useUserStore } from '@/stores/userStore';
import router from '../../router';

const $toast = useToast();

export default {
  data() {
    return {
      email: null,
      password: null,
    };
  },
  methods: {
    async login() {
      const userStore = useUserStore();
      const response = await login(this.email, this.password);

      if (!response) {
        $toast.error('Login failed. Please check your credentials.');
        return;
      }

      userStore.setToken(response.data.token);
      userStore.setUser(response.data.user);

      router.push('/tasks');
    }
  },
};
</script>