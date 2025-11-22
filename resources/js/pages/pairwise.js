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
        const data = response.data.data;
        
        if (data && data.matrix) {
            pairwiseMatrix = data.matrix;
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
        
        const response = await pairwiseAPI.saveMatrix(pairwiseMatrix);
        
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
    const card = document.getElementById('consistencyCard');
    const crCard = document.getElementById('crCard');
    const alert = document.getElementById('consistencyAlert');
    
    card.classList.remove('hidden');
    
    document.getElementById('ciValue').textContent = data.consistency_index?.toFixed(4) || '-';
    document.getElementById('riValue').textContent = data.random_index?.toFixed(4) || '-';
    document.getElementById('crValue').textContent = data.consistency_ratio?.toFixed(4) || '-';
    
    const cr = data.consistency_ratio || 0;
    const isConsistent = cr < 0.1;
    
    // Update CR card color
    crCard.className = `stat-card ${isConsistent ? 'stat-card-green' : 'stat-card-red'}`;
    
    // Update status
    const statusEl = document.getElementById('crStatus');
    statusEl.textContent = isConsistent ? '✓ Consistent' : '✗ Inconsistent';
    
    // Show alert
    alert.classList.remove('hidden', 'alert-success', 'alert-warning');
    alert.classList.add(isConsistent ? 'alert-success' : 'alert-warning');
    alert.querySelector('p').innerHTML = isConsistent 
        ? '<strong>Good!</strong> Your pairwise comparison matrix is consistent (CR < 0.10). You can proceed to the next step.'
        : '<strong>Warning!</strong> Your pairwise comparison matrix is inconsistent (CR ≥ 0.10). Please review your comparisons to ensure logical consistency.';
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    loadCriteria();
    
    // Save button
    document.getElementById('btnSaveMatrix')?.addEventListener('click', saveMatrix);
});

// Expose function to window for inline handlers
window.updateMatrixValue = updateMatrixValue;
