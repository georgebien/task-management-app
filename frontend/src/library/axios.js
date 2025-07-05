import axios from 'axios'
import { useUserStore } from '@/stores/user'
import Cookies from 'js-cookie'

const api = axios.create({
  baseURL: 'http://localhost:8000/',
  withCredentials: true,
  xsrfCookieName: 'XSRF-TOKEN',
  xsrfHeaderName: 'X-XSRF-TOKEN',
})

api.interceptors.request.use((config) => {
  const userStore = useUserStore()
  
  if (userStore.token) {
    config.headers.Authorization = `Bearer ${userStore.token}`
  }

  const token = Cookies.get('XSRF-TOKEN')
  if (token) {
    config.headers['X-XSRF-TOKEN'] = decodeURIComponent(token)
  }

  return config
})

export default api
