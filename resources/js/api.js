import axios from "axios";
import Swal from "sweetalert2";

// Base URL untuk API
const API_BASE_URL = "/api";

// Axios instance dengan konfigurasi default
const api = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
    },
});

// Interceptor untuk handle error global
api.interceptors.response.use(
    (response) => response,
    (error) => {
        const errorMessage =
            error.response?.data?.message ||
            error.message ||
            "Terjadi kesalahan";

        Swal.fire({
            icon: "error",
            title: "Error",
            text: errorMessage,
            confirmButtonColor: "#3b82f6",
        });

        return Promise.reject(error);
    }
);

// ============= CRITERIA API =============
export const criteriaAPI = {
    getAll: () =>
        api.get("/criteria", {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
    getById: (id) =>
        api.get(`/criteria/${id}`, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
    create: (data) =>
        api.post("/criteria", data, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
    update: (id, data) =>
        api.put(`/criteria/${id}`, data, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
    delete: (id) =>
        api.delete(`/criteria/${id}`, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
};

// ============= ALTERNATIVE API =============
export const alternativeAPI = {
    getAll: () =>
        api.get("/alternatives", {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
    getById: (id) =>
        api.get(`/alternatives/${id}`, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
    create: (data) =>
        api.post("/alternatives", data, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
    update: (id, data) =>
        api.put(`/alternatives/${id}`, data, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
    delete: (id) =>
        api.delete(`/alternatives/${id}`, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
};

// ============= DECISION MAKER API =============
export const decisionMakerAPI = {
    getAll: () =>
        api.get("/decision-makers", {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
    getById: (id) =>
        api.get(`/decision-makers/${id}`, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
    create: (data) =>
        api.post("/decision-makers", data, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
    update: (id, data) =>
        api.put(`/decision-makers/${id}`, data, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
    delete: (id) =>
        api.delete(`/decision-makers/${id}`, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
};

// ============= PAIRWISE COMPARISON API =============
export const pairwiseAPI = {
    getMatrix: () =>
        api.get("/pairwise-comparisons", {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
    saveMatrix: (data) =>
        api.post("/pairwise-comparisons/matrix", { matrix: data }, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
};

// ============= ANP INTERDEPENDENCY API =============
export const anpAPI = {
    getMatrix: () =>
        api.get("/anp-interdependencies", {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
    saveMatrix: (data) =>
        api.post("/anp-interdependencies/matrix", { matrix: data }, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
};

// ============= ALTERNATIVE RATING API =============
export const ratingAPI = {
    getAll: () =>
        api.get("/alternative-ratings", {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
    getRatingsByDM: (dmId) =>
        api.get(`/alternative-ratings?decision_maker_id=${dmId}`, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
    storeBulk: (data) =>
        api.post("/alternative-ratings/bulk", data, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
    saveRatings: (dmId, ratings) =>
        api.post("/alternative-ratings/matrix", {
            decision_maker_id: dmId,
            ratings: ratings,
        }, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
};

// ============= CALCULATION API =============
export const calculationAPI = {
    // Individual calculations
    calculateAHP: () =>
        api.post("/calculate/ahp", {}, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
    calculateANP: () =>
        api.post("/calculate/anp", {}, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
    calculateWP: (dmId = null) => {
        const url = dmId ? `/calculate/wp?dm_id=${dmId}` : "/calculate/wp";
        return api.post(url, {}, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        });
    },
    calculateBorda: () =>
        api.post("/calculate/borda", {}, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),

    // Calculate all at once
    calculateAll: () =>
        api.post("/calculate/all", {}, {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),

    // Get results
    getResults: () =>
        api.get("/results", {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
    getFinalRanking: () =>
        api.get("/results/final-ranking", {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
};

// ============= AUTH API =============
export const authAPI = {
    getCurrentUser: () =>
        api.get("/user/current", {
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        }),
};

// ============= HELPER FUNCTIONS =============
export const showSuccess = (message) => {
    Swal.fire({
        icon: "success",
        title: "Berhasil!",
        text: message,
        confirmButtonColor: "#10b981",
        timer: 2000,
    });
};

export const showError = (message) => {
    Swal.fire({
        icon: "error",
        title: "Error!",
        text: message,
        confirmButtonColor: "#ef4444",
    });
};

export const showLoading = () => {
    Swal.fire({
        title: "Loading...",
        text: "Mohon tunggu sebentar",
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
};

export const closeLoading = () => {
    Swal.close();
};

export const confirmDelete = async (
    message = "Data yang dihapus tidak dapat dikembalikan!"
) => {
    const result = await Swal.fire({
        title: "Apakah Anda yakin?",
        text: message,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    });

    return result.isConfirmed;
};

export default api;
