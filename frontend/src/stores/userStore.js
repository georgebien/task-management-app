import { defineStore } from 'pinia';

export const useUserStore = defineStore('user', {
  state: () => ({
    token: localStorage.getItem('token') || null,
    user: JSON.parse(localStorage.getItem('user')) || null,
  }),

  getters: {
    isAuthenticated(state) {
      return !!state.token;
    },
  },

  actions: {
    setToken(newToken) {
      this.token = newToken;
      localStorage.setItem('token', newToken);
    },
    setUser(newUser) {
      this.user = newUser;
      localStorage.setItem('user', JSON.stringify(newUser));
    },
    clearUser() {
      this.token = null;
      this.user = null;
      localStorage.removeItem('token');
      localStorage.removeItem('user');
    },
  },
});