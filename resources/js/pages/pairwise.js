import { criteriaAPI, pairwiseAPI, showSuccess, showError, showLoading, closeLoading } from '../api.js';

let criteria = [];
let pairwiseMatrix = {};

async function loadCriteria() {
    try {
        const response = await criteriaAPI.getAll();
        criteria = response.data.data;
        
        if (criteria.length === 0) {
            document.getElementById('matrixContainer').innerHTML = `
                <div class="text-center py-8">
                    <div class="empty-state">
                        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="empty-state-text">No criteria found</p>
                        <p class="empty-state-subtext">Please add criteria first before creating pairwise comparisons</p>
                        <a href="/criteria" class="btn btn-primary mt-4">Go to Criteria</a>
                    </div>
                </div>
            `;
            return;
        }        
        // Load existing matrix if available
        await loadExistingMatrix();
        
        // Render matrix
        renderMatrix();
    } catch (error) {
        showError('Failed to load criteria');
        console.error(error);
    }
}

async function loadExistingMatrix() {
    try {
        const response = await pairwiseAPI.getMatrix();
        const existingData = response.data.data; 
        if (Array.isArray(existingData) && existingData.length > 0) {
            
            // Loop data dari server dan masukkan ke pairwiseMatrix
            existingData.forEach(item => {
                // Pastikan value dikonversi ke float karena dari JSON string "1.00"
                const val = parseFloat(item.value);
                
                // Buat key sesuai format yang dipakai di initializeMatrix & renderMatrix
                // Format: "idBaris_idKolom"
                const key = `${item.criteria_i}_${item.criteria_j}`;
                
                // Masukkan ke state local
                pairwiseMatrix[key] = val;
            });

            displayConsistencyResults(response.data.calculation_result);
        }
    } catch (error) {
        // No existing matrix, initialize empty
        console.log('No existing matrix found, initializing new one');
        initializeMatrix();
    }
}

function initializeMatrix() {
    pairwiseMatrix = {};
    for (let i = 0; i < criteria.length; i++) {
        for (let j = 0; j < criteria.length; j++) {
            const key = `${criteria[i].id}_${criteria[j].id}`;
            if (i === j) {
                pairwiseMatrix[key] = 1;
            } else if (!pairwiseMatrix[key]) {
                pairwiseMatrix[key] = 1;
            }
        }
    }
}

function renderMatrix() {
    const n = criteria.length;
    
    let html = `
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Criteria
                        </th>
                        ${criteria.map(c => `
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                ${c.code}
                            </th>
                        `).join('')}
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
    `;
    
    for (let i = 0; i < n; i++) {
        html += `<tr class="${i % 2 === 0 ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800'}">`;
        html += `
            <td class="px-4 py-3 whitespace-nowrap">
                <div class="flex items-center">
                    <span class="badge badge-primary mr-2">${criteria[i].code}</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">${criteria[i].name}</span>
                </div>
            </td>
        `;
        
        for (let j = 0; j < n; j++) {
            const key = `${criteria[i].id}_${criteria[j].id}`;
            const reverseKey = `${criteria[j].id}_${criteria[i].id}`;
            
            if (i === j) {
                html += `
                    <td class="px-4 py-3 text-center bg-blue-50 dark:bg-blue-900/20">
                        <span class="text-sm font-bold text-blue-600 dark:text-blue-400">1</span>
                    </td>
                `;
            } else if (i < j) {
                const value = pairwiseMatrix[key] || 1;
                html += `
                    <td class="px-4 py-3 text-center">
                        <select 
                            class="form-select-sm w-20 text-center" 
                            data-row="${criteria[i].id}" 
                            data-col="${criteria[j].id}"
                            onchange="window.updateMatrixValue(${criteria[i].id}, ${criteria[j].id}, this.value)"
                        >
                            ${[9, 8, 7, 6, 5, 4, 3, 2, 1].map(v => `
                                <option value="${v}" ${value === v ? 'selected' : ''}>${v}</option>
                            `).join('')}
                        </select>
                    </td>
                `;
            } else {
                const reciprocalValue = pairwiseMatrix[reverseKey] || 1;
                const displayValue = reciprocalValue === 1 ? '1' : `1/${reciprocalValue}`;
                html += `
                    <td class="px-4 py-3 text-center bg-gray-100 dark:bg-gray-700">
                        <span class="text-sm text-gray-600 dark:text-gray-400">${displayValue}</span>
                    </td>
                `;
            }
        }
        
        html += `</tr>`;
    }
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    
    document.getElementById('matrixContainer').innerHTML = html;
}

function updateMatrixValue(rowId, colId, value) {
    const key = `${rowId}_${colId}`;
    pairwiseMatrix[key] = parseFloat(value);
    
    // Update reciprocal value in display
    renderMatrix();
}

async function saveMatrix() {
    try {
        showLoading('Saving pairwise comparison matrix...');
        
        // Convert object to 2D array
        const n = criteria.length;
        const matrixArray = [];
        
        for (let i = 0; i < n; i++) {
            const row = [];
            for (let j = 0; j < n; j++) {
                if (i === j) {
                    row.push(1);
                } else if (i < j) {
                    // Upper triangle: get from pairwiseMatrix or default to 1
                    const val = pairwiseMatrix[`${criteria[i].id}_${criteria[j].id}`] || 1;
                    row.push(val);
                } else {
                    // Lower triangle: reciprocal of the upper triangle value
                    const val = pairwiseMatrix[`${criteria[j].id}_${criteria[i].id}`] || 1;
                    row.push(1 / val);
                }
            }
            matrixArray.push(row);
        }

        const response = await pairwiseAPI.saveMatrix(matrixArray);
        
        closeLoading();
        showSuccess('Matrix saved successfully!');
        
        // Check consistency
        if (response.data.data.consistency_ratio !== undefined) {
            displayConsistencyResults(response.data.data);
        }
    } catch (error) {
        closeLoading();
        showError(error.response?.data?.message || 'Failed to save matrix');
        console.error(error);
    }
}

function displayConsistencyResults(data) {
    const placeholder = document.getElementById('consistencyPlaceholder');
    const resultContainer = document.getElementById('consistencyResult');    
    // Toggle visibility
    if (placeholder) placeholder.classList.add('hidden');
    if (resultContainer) resultContainer.classList.remove('hidden');
    
    // Update values
    const ciEl = document.getElementById('ciValue');
    const riEl = document.getElementById('riValue');
    const crEl = document.getElementById('crValue');

    if (ciEl) ciEl.textContent = data.consistency_index?.toFixed(4) || '-';
    if (riEl) riEl.textContent = data.random_index?.toFixed(4) || '-';
    if (crEl) crEl.textContent = data.consistency_ratio?.toFixed(4) || '-';
    
    const cr = data.consistency_ratio || 0;
    const isConsistent = cr < 0.1;
    
    // Update Badge
    const crBadge = document.getElementById('crBadge');
    if (crBadge) {
        crBadge.textContent = isConsistent ? 'Valid' : 'Invalid';
        crBadge.className = `px-2.5 py-0.5 rounded-full text-xs font-bold ${isConsistent ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'}`;
    }

    // Update Message
    const crMessage = document.getElementById('crMessage');
    if (crMessage) {
        crMessage.textContent = isConsistent 
            ? 'The matrix is consistent (CR < 0.1).' 
            : 'The matrix is inconsistent (CR ≥ 0.1). Please revise.';
        crMessage.className = `text-sm mt-2 ${isConsistent ? 'text-emerald-600' : 'text-rose-600'}`;
    }

    // Update Progress Bar
    const crProgressBar = document.getElementById('crProgressBar');
    if (crProgressBar) {
        // Map CR to percentage (0.1 is threshold, say 50%)
        // Let's say 0.2 is 100%
        let percentage = (cr / 0.2) * 100;
        if (percentage > 100) percentage = 100;
        
        crProgressBar.style.width = `${percentage}%`;
        crProgressBar.className = `h-full transition-all duration-1000 ${isConsistent ? 'bg-emerald-500' : 'bg-rose-500'}`;
    }
    
    // Update Alert Box
    const alertBox = document.getElementById('alertBox');
    if (alertBox) {
        if (isConsistent) {
            alertBox.classList.add('hidden');
        } else {
            alertBox.classList.remove('hidden');
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    loadCriteria();
    
    // Save button
    document.getElementById('btnSaveMatrix')?.addEventListener('click', saveMatrix);
});

// Expose function to window for inline handlers
window.updateMatrixValue = updateMatrixValue;
