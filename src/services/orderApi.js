import axios from './api';

export const orderApi = {
  getStep1: () => axios.get('/api/order/step-1').then(res => res.data),
  postStep1: (data) => axios.post('/api/order/step-1', data).then(res => res.data),
  postStep2: (data) => axios.post('/api/order/step-2', data).then(res => res.data),
  getStep3: (serviceId) => axios.get(`/api/order/step-3?service_id=${serviceId}`).then(res => res.data),
  postStep3: (data) => axios.post('/api/order/step-3', data).then(res => res.data),
  getOrder: (ticketId) => axios.get(`/api/order/${ticketId}`).then(res => res.data),
  checkStatus: (data) => {
    const payload = {
      ticket_id: data.ticket_id ?? data.kode_tiket ?? data,
    };
    return axios.post('/api/order/status', payload).then(res => res.data);
  },
  rateOrder: (data) => axios.post('/api/order/rate', data).then(res => res.data),
};
