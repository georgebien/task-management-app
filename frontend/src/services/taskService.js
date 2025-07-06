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
