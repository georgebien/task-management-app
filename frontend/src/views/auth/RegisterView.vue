<template>
  <div
    class="card shadow-lg p-4"
    style="width: 100%; max-width: 400px;"
  >
    <div class="card-body">
      <h2 class="card-title text-center mb-4">
        Register
      </h2>
      <form @submit.prevent="handleRegister">
        <div class="mb-3">
          <input
            v-model="firstName"
            placeholder="First name"
            type="text"
            class="form-control"
          >
        </div>
        <div class="mb-3">
          <input
            v-model="lastName"
            placeholder="Last name"
            type="text"
            class="form-control"
          >
        </div>
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
        <div class="mb-3">
          <input
            v-model="confirmPassword"
            placeholder="Re-type Password"
            type="password"
            class="form-control"
          >
        </div>
        <div class="d-grid gap-2">
          <button
            type="submit"
            class="btn btn-primary d-flex align-items-center justify-content-center gap-2"
            :disabled="isLoading"
          >
            <span
              v-if="isLoading"
              class="spinner-border spinner-border-sm"
            />
            <span>{{ isLoading ? 'Processing...' : 'Register' }}</span>
          </button>
        </div>
      </form>

      <p class="text-center mt-5">
        Already have an account?
        <router-link to="/">
          Login here
        </router-link>
      </p>
    </div>
  </div>
</template>

<script>
import { useToast } from 'vue-toast-notification';
import router from '../../router';
import { register } from '../../services/authService';

const $toast = useToast();

export default {
  data() {
    return {
      firstName: null,
      lastName: null,
      email: null,
      password: null,
      confirmPassword: null,
      isLoading: false
    };
  },
  methods: {
    async handleRegister() {
      if (this.password !== this.confirmPassword) {
        $toast.error('Passwords do not match', { position: 'top' });;
        return;
      }

      this.isLoading = true;

      const payload = {
        first_name: this.firstName,
        last_name: this.lastName,
        email: this.email,
        password: this.password,
        password_confirmation: this.confirmPassword,
      }

      const { status, data} = await register(payload);

      if (status !== 200) {
        $toast.error(data.message, { position: 'top' });
        return;
      }

      $toast.success(data.message, { position: 'top' });

      this.isLoading = false;
      this.clearFields();

      setTimeout(function() {
        router.push('/');
      },2000);
    },

    clearFields() {
      this.firstName = null;
      this.lastName = null;
      this.email = null;
      this.password = null;
      this.confirmPassword = null;
    }
  }
};
</script>