import { criteriaAPI, anpAPI, showSuccess, showError, showLoading, closeLoading } from '../api.js';

let criteria = [];
let interdependencyMatrix = {};

async function loadCriteria() {
    try {
        const response = await criteriaAPI.getAll();
        criteria = response.data.data;
        
        if (criteria.length === 0) {
            document.getElementById('matrixContainer').innerHTML = `
                <div class="text-center py-8">
                    <div class="empty-state">
                        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                        </svg>
                        <p class="empty-state-text">No criteria found</p>
                        <p class="empty-state-subtext">Please add criteria first before creating interdependency matrix</p>
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
        const response = await anpAPI.getMatrix();
        const data = response.data.data;
        
        if (data && data.matrix) {
            interdependencyMatrix = data.matrix;
        }
    } catch (error) {
        // No existing matrix, initialize empty
        console.log('No existing matrix found, initializing new one');
        initializeMatrix();
    }
}

function initializeMatrix() {
    interdependencyMatrix = {};
    for (let i = 0; i < criteria.length; i++) {
        for (let j = 0; j < criteria.length; j++) {
            const key = `${criteria[i].id}_${criteria[j].id}`;
            interdependencyMatrix[key] = i === j ? 0 : 0;
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
                            From → To
                        </th>
                        ${criteria.map(c => `
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                ${c.code}
                            </th>
                        `).join('')}
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Sum
                        </th>
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
        
        let rowSum = 0;
        for (let j = 0; j < n; j++) {
            const key = `${criteria[i].id}_${criteria[j].id}`;
            const value = interdependencyMatrix[key] || 0;
            rowSum += parseFloat(value);
            
            if (i === j) {
                html += `
                    <td class="px-4 py-3 text-center bg-gray-200 dark:bg-gray-700">
                        <span class="font-bold text-gray-500 dark:text-gray-400">0</span>
                    </td>
                `;
            } else {
                html += `
                    <td class="px-4 py-3 text-center">
                        <input 
                            type="number" 
                            step="0.1" 
                            min="0" 
                            max="1" 
                            class="form-input-sm w-20 text-center" 
                            value="${value}"
                            data-row="${criteria[i].id}" 
                            data-col="${criteria[j].id}"
                            onchange="window.updateMatrixValue(${criteria[i].id}, ${criteria[j].id}, this.value)"
                        />
                    </td>
                `;
            }
        }
        
        html += `
            <td class="px-4 py-3 text-center bg-blue-50 dark:bg-blue-900/20">
                <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">${rowSum.toFixed(2)}</span>
            </td>
        `;
        html += `</tr>`;
    }
    
    // Column sums row
    html += `<tr class="bg-blue-50 dark:bg-blue-900/20 font-semibold">`;
    html += `<td class="px-4 py-3 text-left text-sm text-blue-900 dark:text-blue-300">Column Sum</td>`;
    
    for (let j = 0; j < n; j++) {
        let colSum = 0;
        for (let i = 0; i < n; i++) {
            const key = `${criteria[i].id}_${criteria[j].id}`;
            colSum += parseFloat(interdependencyMatrix[key] || 0);
        }
        html += `
            <td class="px-4 py-3 text-center text-sm text-blue-600 dark:text-blue-400">
                ${colSum.toFixed(2)}
            </td>
        `;
    }
    html += `<td class="px-4 py-3"></td>`;
    html += `</tr>`;
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    
    document.getElementById('matrixContainer').innerHTML = html;
}

function updateMatrixValue(rowId, colId, value) {
    const key = `${rowId}_${colId}`;
    interdependencyMatrix[key] = parseFloat(value) || 0;
    
    // Re-render to update sums
    renderMatrix();
}

async function saveMatrix() {
    try {
        showLoading('Saving ANP interdependency matrix...');
        
        await anpAPI.saveMatrix(interdependencyMatrix);
        
        closeLoading();
        showSuccess('Matrix saved successfully!');
    } catch (error) {
        closeLoading();
        showError(error.response?.data?.message || 'Failed to save matrix');
        console.error(error);
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
