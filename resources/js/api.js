import axios from 'axios';
import Swal from 'sweetalert2';

// Base URL untuk API
const API_BASE_URL = '/api';

// Axios instance dengan konfigurasi default
const api = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

// Interceptor untuk handle error global
api.interceptors.response.use(
    response => response,
    error => {
        const errorMessage = error.response?.data?.message || error.message || 'Terjadi kesalahan';
        
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: errorMessage,
            confirmButtonColor: '#3b82f6'
        });
        
        return Promise.reject(error);
    }
);

// ============= CRITERIA API =============
export const criteriaAPI = {
    getAll: () => api.get('/criteria'),
    getById: (id) => api.get(`/criteria/${id}`),
    create: (data) => api.post('/criteria', data),
    update: (id, data) => api.put(`/criteria/${id}`, data),
    delete: (id) => api.delete(`/criteria/${id}`)
};

// ============= ALTERNATIVE API =============
export const alternativeAPI = {
    getAll: () => api.get('/alternatives'),
    getById: (id) => api.get(`/alternatives/${id}`),
    create: (data) => api.post('/alternatives', data),
    update: (id, data) => api.put(`/alternatives/${id}`, data),
    delete: (id) => api.delete(`/alternatives/${id}`)
};

// ============= DECISION MAKER API =============
export const decisionMakerAPI = {
    getAll: () => api.get('/decision-makers'),
    getById: (id) => api.get(`/decision-makers/${id}`),
    create: (data) => api.post('/decision-makers', data),
    update: (id, data) => api.put(`/decision-makers/${id}`, data),
    delete: (id) => api.delete(`/decision-makers/${id}`)
};

// ============= PAIRWISE COMPARISON API =============
export const pairwiseAPI = {
    getMatrix: () => api.get('/pairwise-comparisons'),
    saveMatrix: (data) => api.post('/pairwise-comparisons/matrix', { matrix: data })
};

// ============= ANP INTERDEPENDENCY API =============
export const anpAPI = {
    getMatrix: () => api.get('/anp-interdependencies'),
    saveMatrix: (data) => api.post('/anp-interdependencies/matrix', { matrix: data })
};

// ============= ALTERNATIVE RATING API =============
export const ratingAPI = {
    getAll: () => api.get('/alternative-ratings'),
    getRatingsByDM: (dmId) => api.get(`/alternative-ratings?decision_maker_id=${dmId}`),
    storeBulk: (data) => api.post('/alternative-ratings/bulk', data),
    saveRatings: (dmId, ratings) => api.post('/alternative-ratings/matrix', { 
        decision_maker_id: dmId,
        ratings: ratings 
    })
};

// ============= CALCULATION API =============
export const calculationAPI = {
    // Individual calculations
    calculateAHP: () => api.post('/calculate/ahp'),
    calculateANP: () => api.post('/calculate/anp'),
    calculateWP: (dmId = null) => {
        const url = dmId ? `/calculate/wp?dm_id=${dmId}` : '/calculate/wp';
        return api.post(url);
    },
    calculateBorda: () => api.post('/calculate/borda'),
    
    // Calculate all at once
    calculateAll: () => api.post('/calculate/all'),
    
    // Get results
    getResults: () => api.get('/results'),
    getFinalRanking: () => api.get('/results/final-ranking')
};

// ============= AUTH API =============
export const authAPI = {
    getCurrentUser: () => api.get('/user/current')
};

// ============= HELPER FUNCTIONS =============
export const showSuccess = (message) => {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        confirmButtonColor: '#10b981',
        timer: 2000
    });
};

export const showError = (message) => {
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: message,
        confirmButtonColor: '#ef4444'
    });
};

export const showLoading = () => {
    Swal.fire({
        title: 'Loading...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
};

export const closeLoading = () => {
    Swal.close();
};

export const confirmDelete = async (message = 'Data yang dihapus tidak dapat dikembalikan!') => {
    const result = await Swal.fire({
        title: 'Apakah Anda yakin?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    });
    
    return result.isConfirmed;
};

export default api;
