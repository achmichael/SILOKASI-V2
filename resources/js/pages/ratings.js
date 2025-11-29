import { ratingAPI, authAPI, showSuccess, showError, showLoading, closeLoading } from '../api.js';

// Variable Global
let criteria = [];
let alternatives = [];
let ratings = {};
let currentDMId = null;
let currentUser = null;

async function loadData() {
    try {
        // 1. Cukup panggil API User dan Ratings saja
        const [userRes, ratingRes] = await Promise.all([
            authAPI.getCurrentUser(),
            // Asumsi: Anda memanggil rating berdasarkan user yang sedang login atau endpoint umum
            // Jika user belum login, authAPI biasanya handle error duluan.
            // Kita perlu ID user untuk fetch rating, jadi logic-nya sedikit diubah:
            authAPI.getCurrentUser().then(res => {
                const user = res.data.data.user;
                if (user && user.role === 'decision_maker') {
                    // Chain request: dapat user dulu, baru ambil rating
                    return ratingAPI.getRatingsByDM(user.id).then(r => ({ user, ratings: r }));
                }
                return { user, ratings: null };
            })
        ]);

        // Note: Implementasi Promise di atas sedikit kompleks untuk handle dependency. 
        // Cara lebih sederhana (sequential) seringkali lebih aman untuk logic auth -> data:
        
        /* REVISI LOGIC FETCH:
           Kita ambil User dulu, baru ambil Ratings. 
           Karena kita butuh ID user untuk ambil rating (kecuali API rating otomatis detect user dari token).
        */
       
        // Step 1: Get User
        const userResponse = await authAPI.getCurrentUser();
        currentUser = userResponse.data.data.user;
        updateUserCard();

        if (!currentUser || currentUser.role !== 'decision_maker') {
            showNoDecisionMakerState();
            return;
        }

        currentDMId = currentUser.id;

        // Step 2: Get Ratings (yang berisi data kriteria & alternatif)
        const ratingResponse = await ratingAPI.getRatingsByDM(currentDMId);
        const ratingsData = ratingResponse.data.data;

        // Step 3: Proses Data (Ekstraksi Kriteria & Alternatif dari JSON Rating)
        if (!ratingsData || ratingsData.length === 0) {
            // Edge case: Jika belum ada data rating sama sekali, kita tidak bisa 
            // mengekstrak kriteria/alternatif. Di kasus ini, UI akan kosong.
            showEmptyState(); 
            return;
        }

        processResponseData(ratingsData);
        
        // Step 4: Render
        renderRatingsGrid();

    } catch (error) {
        const isUnauthorized = error.response?.status === 401;
        showError(isUnauthorized ? 'Please login to continue' : 'Failed to load data');
        console.error(error);
        if (isUnauthorized) {
            showUnauthorizedState();
        }
    }
}

/**
 * Fungsi Utama: Mengekstrak Criteria dan Alternatives unik dari response Ratings
 */
function processResponseData(data) {
    const tempCriteria = new Map();
    const tempAlternatives = new Map();
    ratings = {}; // Reset ratings

    data.forEach(item => {
        // 1. Ambil Alternative Unik
        if (item.alternative && !tempAlternatives.has(item.alternative.id)) {
            tempAlternatives.set(item.alternative.id, item.alternative);
        }

        // 2. Ambil Criteria Unik
        if (item.criteria && !tempCriteria.has(item.criteria.id)) {
            tempCriteria.set(item.criteria.id, item.criteria);
        }

        // 3. Mapping Rating
        // Format Key: "alternativeId_criteriaId"
        const key = `${item.alternative_id}_${item.criteria_id}`;
        ratings[key] = item.rating;
    });

    // 4. Konversi Map ke Array dan Sort berdasarkan ID agar urutan konsisten
    criteria = Array.from(tempCriteria.values()).sort((a, b) => a.id - b.id);
    alternatives = Array.from(tempAlternatives.values()).sort((a, b) => a.id - b.id);
}

function showEmptyState() {
    const container = document.getElementById('ratingsContainer');
    container.innerHTML = `
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
            <div class="p-6 text-center py-8">
                <p class="text-gray-500">No rating data found to extract Criteria and Alternatives.</p>
                <p class="text-sm text-gray-400">Please ensure the backend has initialized data.</p>
            </div>
        </div>
    `;
    toggleSaveButton(false);
}

function showNoDecisionMakerState() {
    const container = document.getElementById('ratingsContainer');
    container.innerHTML = `
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
            <div class="p-6">
                <div class="text-center py-10">
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Decision Maker Role Required</h3>
                    <p class="text-gray-600 mt-2">Your account does not have decision maker role.</p>
                </div>
            </div>
        </div>
    `;
    toggleSaveButton(false);
}

function showUnauthorizedState() {
    const container = document.getElementById('ratingsContainer');
    container.innerHTML = `
        <div class="card">
            <div class="card-body text-center py-10">
                <h3 class="text-lg font-semibold">Authentication Required</h3>
                <a href="/login" class="btn btn-primary mt-4">Go to Login</a>
            </div>
        </div>
    `;
    toggleSaveButton(false);
}

function updateUserCard() {
    const nameEl = document.getElementById('dmName');
    if (!nameEl) return;

    document.getElementById('loadingIndicator')?.style.setProperty('display', 'none');

    nameEl.textContent = currentUser?.name || '-';
    document.getElementById('dmEmail').textContent = currentUser?.email || '-';
    
    const avatarEl = document.getElementById('dmAvatar');
    if (avatarEl && currentUser?.name) {
        const initials = currentUser.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
        avatarEl.textContent = initials;
        avatarEl.classList.remove('bg-slate-100', 'text-slate-400');
        avatarEl.classList.add('bg-gradient-to-br', 'from-primary-100', 'to-indigo-100', 'text-primary-700');
    }
    
    const roleEl = document.getElementById('dmRole');
    if (roleEl) {
        roleEl.textContent = currentUser?.role === 'decision_maker' ? 'Decision Maker' : 'Admin';
    }

    const statusBadge = document.getElementById('dmStatusBadge');
    if (statusBadge) {
        const isDecisionMaker = currentUser?.role === 'decision_maker';
        statusBadge.textContent = isDecisionMaker ? 'Active' : 'Not DM';
        statusBadge.className = isDecisionMaker ? 'badge badge-success' : 'badge badge-warning';
    }

    const dmDetails = document.getElementById('dmDetails');
    if (dmDetails) {
        dmDetails.classList.remove('opacity-50');
        dmDetails.classList.add('opacity-100');
    }
}

function toggleSaveButton(isEnabled) {
    const button = document.getElementById('btnSaveRatings');
    if (!button) return;

    button.disabled = !isEnabled;
    button.classList.toggle('opacity-50', !isEnabled);
    button.classList.toggle('cursor-not-allowed', !isEnabled);
}

function renderRatingsGrid() {
    const container = document.getElementById('ratingsContainer');
    
    // Safety check
    if (alternatives.length === 0 || criteria.length === 0) {
        showEmptyState();
        return;
    }

    let html = `
        <div class="grid grid-cols-1 gap-6">
            ${alternatives.map(alt => `
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">${alt.name}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">${alt.code}</p>
                            </div>
                            <span class="badge badge-primary">Alternative</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            ${criteria.map(crit => {
                                const key = `${alt.id}_${crit.id}`;
                                const value = ratings[key] || 1; // Default 1 jika undefined
                                return `
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            ${crit.code} - ${crit.name}
                                            <span class="badge badge-${crit.type === 'benefit' ? 'success' : 'danger'} ml-2 text-xs">
                                                ${crit.type}
                                            </span>
                                        </label>
                                        <div class="flex items-center gap-2">
                                            <input 
                                                type="range" 
                                                min="1" 
                                                max="5" 
                                                step="1" 
                                                value="${value}"
                                                class="flex-1 w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700"
                                                data-alt="${alt.id}" 
                                                data-crit="${crit.id}"
                                                oninput="window.updateRating(${alt.id}, ${crit.id}, this.value)"
                                            />
                                            <span class="rating-badge rating-${value} w-8 text-center font-bold text-sm py-1 px-2 rounded">
                                                ${value}
                                            </span>
                                        </div>
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
    
    container.innerHTML = html;
    toggleSaveButton(true);
}

function updateRating(altId, critId, value) {
    const key = `${altId}_${critId}`;
    ratings[key] = parseInt(value);
    
    // Update badge display secara spesifik
    const inputEl = document.querySelector(`input[data-alt="${altId}"][data-crit="${critId}"]`);
    if(inputEl) {
        const badge = inputEl.nextElementSibling;
        badge.textContent = value;
        badge.className = `rating-badge rating-${value} w-8 text-center font-bold text-sm py-1 px-2 rounded`;
    }
}

async function saveRatings() {
    if (!currentDMId) {
        showError('Decision maker profile not found');
        return;
    }

    try {
        showLoading('Saving ratings...');
        
        // Convert ratings object to matrix format (2D array)
        // Penting: Urutan harus sesuai dengan urutan 'alternatives' dan 'criteria' yang sudah di-sort di processResponseData
        const matrix = [];
        alternatives.forEach(alt => {
            const row = [];
            criteria.forEach(crit => {
                const key = `${alt.id}_${crit.id}`;
                row.push(ratings[key] || 1); 
            });
            matrix.push(row);
        });
        
        await ratingAPI.saveRatings(currentDMId, matrix);
        
        closeLoading();
        showSuccess('Ratings saved successfully!');
    } catch (error) {
        closeLoading();
        showError(error.response?.data?.message || 'Failed to save ratings');
        console.error(error);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    toggleSaveButton(false);
    loadData();
    document.getElementById('btnSaveRatings')?.addEventListener('click', saveRatings);
});

// Expose function to window for inline handlers
window.updateRating = updateRating;