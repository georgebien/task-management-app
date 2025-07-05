import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null,
    token: null,
  }),
  actions: {
    setUser(data) {
      this.user = data
    },
    setToken(token) {
      this.token = token
    },
    logout() {
      this.user = null
      this.token = null
    },
  },
})
