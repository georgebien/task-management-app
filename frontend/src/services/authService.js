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

export async function logout() {
  try {
    const response = await api.post('/api/logout', );
    
    return response.data;
  } catch (err) {
    return false;
  }
}

export async function register(payload) {
  try {
    await api.get('/sanctum/csrf-cookie');
    const response = await api.post('/api/register', payload);
    
    return response;
  } catch (error) {
    return error.response;
  }
}
