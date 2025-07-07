import api from '@/library/axios';

export async function getList(filters) {
	try {
		const response = await api.get('/api/tasks', {
			params:filters
		});

		return response.data.data;
	} catch (error) {
		return false;
	}
}

export async function create(data) {
	try {
		const response = await api.post('/api/tasks', data);

		return response;
	} catch (error) {
		return error.response;
	}
}

export async function update(data, id) {
	try {
		const response = await api.post(`/api/tasks/${id}`, data);

		return response;
	} catch (error) {
		return error.response;
	}
}

export async function remove(payload) {
	try {
		const response = await api.delete('/api/tasks', {
			data: payload
		});

		return response;
	} catch (error) {
		return error.response;
	}
}