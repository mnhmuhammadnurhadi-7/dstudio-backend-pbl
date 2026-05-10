import axios from './api';

export const servicesApi = {
  getServices: () => axios.get('/api/services').then(res => res.data),
};
