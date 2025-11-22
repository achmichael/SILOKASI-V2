import { criteriaAPI, alternativeAPI, decisionMakerAPI, ratingAPI, showSuccess, showError, showLoading, closeLoading } from '../api.js';

let decisionMakers = [];
let criteria = [];
let alternatives = [];
let ratings = {};
let currentDMId = null;

async function loadData() {
    try {
        const [dmRes, criteriaRes, altRes] = await Promise.all([
            decisionMakerAPI.getAll(),
            criteriaAPI.getAll(),
            alternativeAPI.getAll()
        ]);

        decisionMakers = dmRes.data.data;
        criteria = criteriaRes.data.data;
        alternatives = altRes.data.data;

        if (decisionMakers.length === 0 || criteria.length === 0 || alternatives.length === 0) {
            showEmptyState();
            return;
        }

        populateDMSelector();
        
        if (decisionMakers.length > 0) {
            currentDMId = decisionMakers[0].id;
            document.getElementById('dmSelector').value = currentDMId;
            await loadRatingsForDM(currentDMId);
        }
    } catch (error) {
        showError('Failed to load data');
        console.error(error);
    }
}

function showEmptyState() {
    const dmSelector = document.getElementById('dmSelector');
    const container = document.getElementById('ratingsContainer');
    
    dmSelector.innerHTML = '<option value="">No decision makers found</option>';
    
    let messages = [];
    if (decisionMakers.length === 0) messages.push('decision makers');
    if (criteria.length === 0) messages.push('criteria');
    if (alternatives.length === 0) messages.push('alternatives');
    
    container.innerHTML = `
        <div class="card">
            <div class="card-body">
                <div class="text-center py-8">
                    <div class="empty-state">
                        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="empty-state-text">Missing required data</p>
                        <p class="empty-state-subtext">Please add ${messages.join(', ')} first</p>
                        <div class="mt-4 flex gap-2 justify-center">
                            ${decisionMakers.length === 0 ? '<a href="/decision-makers" class="btn btn-primary">Add Decision Makers</a>' : ''}
                            ${criteria.length === 0 ? '<a href="/criteria" class="btn btn-primary">Add Criteria</a>' : ''}
                            ${alternatives.length === 0 ? '<a href="/alternatives" class="btn btn-primary">Add Alternatives</a>' : ''}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function populateDMSelector() {
    const selector = document.getElementById('dmSelector');
    selector.innerHTML = decisionMakers.map(dm => `
        <option value="${dm.id}">${dm.name} (Weight: ${(dm.weight * 100).toFixed(0)}%)</option>
    `).join('');
}

async function loadRatingsForDM(dmId) {
    try {
        const response = await ratingAPI.getRatingsByDM(dmId);
        const data = response.data.data;
        
        if (data && data.ratings) {
            ratings = data.ratings;
        } else {
            initializeRatings();
        }
        
        renderRatingsGrid();
    } catch (error) {
        // No existing ratings, initialize empty
        console.log('No existing ratings found, initializing new ones');
        initializeRatings();
        renderRatingsGrid();
    }
}

function initializeRatings() {
    ratings = {};
    for (let alt of alternatives) {
        for (let crit of criteria) {
            const key = `${alt.id}_${crit.id}`;
            ratings[key] = 3; // Default to fair rating
        }
    }
}

function renderRatingsGrid() {
    const container = document.getElementById('ratingsContainer');
    
    let html = `
        <div class="grid grid-cols-1 gap-6">
            ${alternatives.map(alt => `
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">${alt.name}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">${alt.code}</p>
                            </div>
                            <span class="badge badge-primary">Alternative</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            ${criteria.map(crit => {
                                const key = `${alt.id}_${crit.id}`;
                                const value = ratings[key] || 3;
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
                                                class="flex-1"
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
}

function updateRating(altId, critId, value) {
    const key = `${altId}_${critId}`;
    ratings[key] = parseInt(value);
    
    // Update badge display
    const badge = document.querySelector(`input[data-alt="${altId}"][data-crit="${critId}"]`).nextElementSibling;
    badge.textContent = value;
    badge.className = `rating-badge rating-${value} w-8 text-center font-bold text-sm py-1 px-2 rounded`;
}

async function saveRatings() {
    if (!currentDMId) {
        showError('Please select a decision maker');
        return;
    }

    try {
        showLoading('Saving ratings...');
        
        await ratingAPI.saveRatings(currentDMId, ratings);
        
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
    loadData();
    
    // DM Selector change
    document.getElementById('dmSelector')?.addEventListener('change', (e) => {
        currentDMId = parseInt(e.target.value);
        loadRatingsForDM(currentDMId);
    });
    
    // Save button
    document.getElementById('btnSaveRatings')?.addEventListener('click', saveRatings);
});

// Expose function to window for inline handlers
window.updateRating = updateRating;
