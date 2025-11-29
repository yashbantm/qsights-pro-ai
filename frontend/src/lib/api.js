import axios from 'axios'
import { useAuthStore } from '@/store/authStore'

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1'

const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
})

// Request interceptor
api.interceptors.request.use(
  (config) => {
    const token = useAuthStore.getState().token
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => Promise.reject(error)
)

// Response interceptor
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      useAuthStore.getState().logout()
      window.location.href = '/login'
    }
    return Promise.reject(error)
  }
)

export default api

// API Functions
export const authAPI = {
  login: (credentials) => api.post('/auth/login', credentials),
  logout: () => api.post('/auth/logout'),
  me: () => api.get('/auth/me'),
  participantLogin: (data) => api.post('/auth/participant-login', data),
}

export const organizationsAPI = {
  getAll: (params) => api.get('/organizations', { params }),
  getOne: (id) => api.get(`/organizations/${id}`),
  create: (data) => api.post('/organizations', data),
  update: (id, data) => api.put(`/organizations/${id}`, data),
  delete: (id) => api.delete(`/organizations/${id}`),
  uploadLogo: (file) => {
    const formData = new FormData()
    formData.append('logo', file)
    return api.post('/organizations/upload-logo', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
  },
}

export const programsAPI = {
  getAll: (params) => api.get('/programs', { params }),
  getOne: (id) => api.get(`/programs/${id}`),
  create: (data) => api.post('/programs', data),
  update: (id, data) => api.put(`/programs/${id}`, data),
  delete: (id) => api.delete(`/programs/${id}`),
}

export const activitiesAPI = {
  getAll: (params) => api.get('/activities', { params }),
  getOne: (id) => api.get(`/activities/${id}`),
  create: (data) => api.post('/activities', data),
  update: (id, data) => api.put(`/activities/${id}`, data),
  delete: (id) => api.delete(`/activities/${id}`),
  approve: (token, data) => api.post(`/activities/approve/${token}`, data),
  decline: (token, data) => api.post(`/activities/decline/${token}`, data),
}

export const questionnairesAPI = {
  getAll: (params) => api.get('/questionnaires', { params }),
  getOne: (id) => api.get(`/questionnaires/${id}`),
  create: (data) => api.post('/questionnaires', data),
  update: (id, data) => api.put(`/questionnaires/${id}`, data),
  delete: (id) => api.delete(`/questionnaires/${id}`),
}

export const participantsAPI = {
  getAll: (params) => api.get('/participants', { params }),
  getOne: (id) => api.get(`/participants/${id}`),
  create: (data) => api.post('/participants', data),
  update: (id, data) => api.put(`/participants/${id}`, data),
  delete: (id) => api.delete(`/participants/${id}`),
  bulkUpload: (file) => {
    const formData = new FormData()
    formData.append('file', file)
    return api.post('/participants/bulk-upload', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
  },
}

export const responsesAPI = {
  getForActivity: (activityId) => api.get(`/activities/${activityId}/responses`),
  submit: (data) => api.post('/responses', data),
  saveProgress: (id, data) => api.put(`/responses/${id}/progress`, data),
}

export const analyticsAPI = {
  getDashboard: () => api.get('/analytics/dashboard'),
  getActivityAnalytics: (activityId) => api.get(`/analytics/activities/${activityId}`),
  exportResponses: (activityId, format) => 
    api.get(`/analytics/activities/${activityId}/export/${format}`, { responseType: 'blob' }),
}
