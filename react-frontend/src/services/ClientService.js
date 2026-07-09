import api from './api';

const ClientService = {
  getAll: () => api.get('/clients'),

  getById: (id) => api.get(`/clients/${id}`),

  create: (data) => api.post('/clients', data),

  update: (id, data) => api.put(`/clients/${id}`, data),

  delete: (id) => api.delete(`/clients/${id}`),
};

export default ClientService;
