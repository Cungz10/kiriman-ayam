import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || '/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
})

// ── Master Kiriman ──────────────────────────────────────────────────
export const MasterKirimanApi = {
  /** GET /api/master-kiriman */
  list() {
    return api.get('/master-kiriman')
  },

  /** POST /api/master-kiriman */
  create(nama_kiriman) {
    return api.post('/master-kiriman', { nama_kiriman })
  },

  /** PUT /api/master-kiriman/:id */
  update(id, nama_kiriman) {
    return api.put(`/master-kiriman/${id}`, { nama_kiriman })
  },

  /** DELETE /api/master-kiriman/:id */
  remove(id) {
    return api.delete(`/master-kiriman/${id}`)
  },
}

// ── Riwayat Input ───────────────────────────────────────────────────
export const RiwayatInputApi = {
  /**
   * GET /api/riwayat-input
   * @param {Object} params - { nama_kiriman, nomer_po, tanggal_dari, tanggal_sampai, page }
   */
  list(params = {}) {
    return api.get('/riwayat-input', { params })
  },

  /** GET /api/riwayat-input/:id */
  show(id) {
    return api.get(`/riwayat-input/${id}`)
  },

  /**
   * POST /api/riwayat-input
   * @param {Object} payload - { nama_kiriman, nomer_po, nilai: number[] }
   */
  create(payload) {
    return api.post('/riwayat-input', payload)
  },

  /** DELETE /api/riwayat-input/:id */
  remove(id) {
    return api.delete(`/riwayat-input/${id}`)
  },
}

export default api
