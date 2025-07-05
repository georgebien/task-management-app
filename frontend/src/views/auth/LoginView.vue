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
import api from '@/library/axios';

export default {
  data() {
    return {
      email: '',
      password: '',
    };
  },
  methods: {
    async login() {
      try {
        await api.get('/sanctum/csrf-cookie');
        await api.post('/api/login', {
          email: this.email,
          password: this.password,
        });
        alert('Login successful');
      } catch (err) {
        alert('Login failed');
        console.error(err);
      }
    },
  },
};
</script>