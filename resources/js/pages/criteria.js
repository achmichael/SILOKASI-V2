import { criteriaAPI, showSuccess, showError, showLoading, closeLoading, confirmDelete } from '../api.js';

let editingId = null;

async function loadCriteria() {
    try {
        const response = await criteriaAPI.getAll();
        const criteria = response.data.data;
        
        const tbody = document.getElementById('criteriaTableBody');
        
        if (criteria.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-8">
                        <div class="empty-state">
                            <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="empty-state-text">No criteria yet</p>
                            <p class="empty-state-subtext">Click "Add Criteria" to create your first evaluation criterion</p>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }
        
        tbody.innerHTML = criteria.map((item, index) => `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">${index + 1}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">${item.name}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">${item.description || '-'}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="badge ${item.type === 'benefit' ? 'badge-success' : 'badge-danger'}">
                        ${item.type}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="flex items-center gap-2">
                        <button onclick="window.editCriteria(${item.id})" class="btn-icon btn-icon-warning">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button onclick="window.deleteCriteria(${item.id})" class="btn-icon btn-icon-danger">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    } catch (error) {
        showError('Failed to load criteria');
        console.error(error);
    }
}

function showModal() {
    document.getElementById('criteriaModal').classList.remove('hidden');
    document.getElementById('criteriaForm').reset();
    document.getElementById('modalTitle').textContent = 'Add Criteria';
    editingId = null;
}

function closeModal() {
    document.getElementById('criteriaModal').classList.add('hidden');
    document.getElementById('criteriaForm').reset();
    editingId = null;
}

async function editCriteria(id) {
    try {
        const response = await criteriaAPI.getById(id);
        const criteria = response.data.data;
        
        document.getElementById('name').value = criteria.name;
        document.getElementById('description').value = criteria.description || '';
        document.getElementById('type').value = criteria.type;
        
        document.getElementById('modalTitle').textContent = 'Edit Criteria';
        editingId = id;
        
        document.getElementById('criteriaModal').classList.remove('hidden');
    } catch (error) {
        showError('Failed to load criteria details');
        console.error(error);
    }
}

async function deleteCriteria(id) {
    const confirmed = await confirmDelete('Are you sure you want to delete this criteria?');
    if (!confirmed) return;

    try {
        showLoading('Deleting criteria...');
        await criteriaAPI.delete(id);
        closeLoading();
        showSuccess('Criteria deleted successfully');
        loadCriteria();
    } catch (error) {
        closeLoading();
        showError('Failed to delete criteria');
        console.error(error);
    }
}

async function submitForm(e) {
    e.preventDefault();
    
    const formData = {
        name: document.getElementById('name').value,
        description: document.getElementById('description').value,
        type: document.getElementById('type').value
    };

    try {
        showLoading(editingId ? 'Updating criteria...' : 'Creating criteria...');
        
        if (editingId) {
            await criteriaAPI.update(editingId, formData);
            showSuccess('Criteria updated successfully');
        } else {
            await criteriaAPI.create(formData);
            showSuccess('Criteria created successfully');
        }
        
        closeLoading();
        closeModal();
        loadCriteria();
    } catch (error) {
        closeLoading();
        showError(editingId ? 'Failed to update criteria' : 'Failed to create criteria');
        console.error(error);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    loadCriteria();
    
    // Event listeners
    document.getElementById('btnAdd')?.addEventListener('click', showModal);
    document.getElementById('btnCancel')?.addEventListener('click', closeModal);
    document.getElementById('criteriaForm')?.addEventListener('submit', submitForm);
    
    // Close modal on overlay click
    document.getElementById('criteriaModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'criteriaModal') {
            closeModal();
        }
    });
});

// Expose functions to window for onclick handlers
window.editCriteria = editCriteria;
window.deleteCriteria = deleteCriteria;
