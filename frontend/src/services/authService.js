import api from '@/library/axios';

export async function login(email, password) {
  try {
    await api.get('/sanctum/csrf-cookie');
    const response = await api.post('/api/login', {
      email: email,
      password: password,
    });
    
    return response.data;
  } catch (err) {
    return false;
  }
}