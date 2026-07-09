import api from './api';

const CaseService = {
  getAll: () => api.get('/cases'),

  getById: (id) => api.get(`/cases/${id}`),

  create: (data) => api.post('/cases', data),

  update: (id, data) => api.put(`/cases/${id}`, data),

  addCourtDate: (id, data) => api.put(`/cases/${id}/add-date`, data),

  getByClient: (clientId) => api.get(`/cases/client/${clientId}`),
};

export default CaseService;
