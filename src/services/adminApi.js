import axios from './api';

export const adminApi = {
  // Auth
  login: (data) => axios.post('/api/admin/login', data).then(res => res.data),
  logout: () => axios.post('/api/admin/logout').then(res => res.data),
  
  // Orders
  getOrders: (params) => axios.get('/api/admin/orders', { params }).then(res => res.data),
  getCompletedOrders: (params) => axios.get('/api/admin/orders/completed', { params }).then(res => res.data),
  updateStatus: (ticketId, data) => axios.patch(`/api/admin/orders/${ticketId}/status`, data).then(res => res.data),
  updateResult: (ticketId, data) => axios.patch(`/api/admin/orders/${ticketId}/result`, data).then(res => res.data),
  
  // Services
  getAdminServices: () => axios.get('/api/admin/services').then(res => res.data),
  getService: (id) => axios.get(`/api/admin/services/${id}`).then(res => res.data),
  createService: (data) => axios.post('/api/admin/services', data).then(res => res.data),
  updateService: (id, data) => axios.put(`/api/admin/services/${id}`, data).then(res => res.data),
  deleteService: (id) => axios.delete(`/api/admin/services/${id}`).then(res => res.data),
  
  // Admins
  getAdmins: () => axios.get('/api/admin/admins').then(res => res.data),
  createAdmin: (data) => axios.post('/api/admin/admins', data).then(res => res.data),
  deleteAdmin: (id) => axios.delete(`/api/admin/admins/${id}`).then(res => res.data),
  
  // CMS
  getCms: () => axios.get('/api/admin/cms').then(res => res.data),
  updateCms: (data) => axios.post('/api/admin/cms', data).then(res => res.data),
};
