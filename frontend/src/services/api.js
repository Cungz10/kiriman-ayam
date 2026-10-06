import axios from 'axios'

// Sesuaikan base URL ini ke endpoint Laravel-mu (mis. http://127.0.0.1:8000/api)
const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || '/api',
  headers: {
    Accept: 'application/json',
  },
})

export default api

export const MasterKirimanApi = {
  list: () => api.get('/master-kiriman'),
  create: (nama_kiriman) => api.post('/master-kiriman', { nama_kiriman }),
  update: (id, nama_kiriman) => api.put(`/master-kiriman/${id}`, { nama_kiriman }),
  remove: (id) => api.delete(`/master-kiriman/${id}`),
}

export const RiwayatInputApi = {
  list: (params) => api.get('/riwayat-input', { params }),
  show: (id) => api.get(`/riwayat-input/${id}`),
  create: (payload) => api.post('/riwayat-input', payload),
  remove: (id) => api.delete(`/riwayat-input/${id}`),
}
