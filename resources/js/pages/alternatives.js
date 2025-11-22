import { alternativeAPI, showSuccess, showError, showLoading, closeLoading, confirmDelete } from '../api.js';

let editingId = null;

async function loadAlternatives() {
    try {
        const response = await alternativeAPI.getAll();
        const alternatives = response.data.data;
        
        const tbody = document.getElementById('alternativesTableBody');
        
        if (alternatives.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-8">
                        <div class="empty-state">
                            <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <p class="empty-state-text">No alternatives yet</p>
                            <p class="empty-state-subtext">Click "Add Alternative" to create your first alternative option</p>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }
        
        tbody.innerHTML = alternatives.map((item, index) => `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">${index + 1}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-3">
                        ${item.rank ? `<span class="badge badge-primary">Rank ${item.rank}</span>` : ''}
                        <div class="text-sm font-medium text-gray-900 dark:text-white">${item.name}</div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">${item.description || '-'}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-600 dark:text-gray-400">${item.location || '-'}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="flex items-center gap-2">
                        <button onclick="window.editAlternative(${item.id})" class="btn-icon btn-icon-warning">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button onclick="window.deleteAlternative(${item.id})" class="btn-icon btn-icon-danger">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    } catch (error) {
        showError('Failed to load alternatives');
        console.error(error);
    }
}

function showModal() {
    document.getElementById('alternativeModal').classList.remove('hidden');
    document.getElementById('alternativeForm').reset();
    document.getElementById('modalTitle').textContent = 'Add Alternative';
    editingId = null;
}

function closeModal() {
    document.getElementById('alternativeModal').classList.add('hidden');
    document.getElementById('alternativeForm').reset();
    editingId = null;
}

async function editAlternative(id) {
    try {
        const response = await alternativeAPI.getById(id);
        const alternative = response.data.data;
        
        document.getElementById('name').value = alternative.name;
        document.getElementById('description').value = alternative.description || '';
        document.getElementById('location').value = alternative.location || '';
        
        document.getElementById('modalTitle').textContent = 'Edit Alternative';
        editingId = id;
        
        document.getElementById('alternativeModal').classList.remove('hidden');
    } catch (error) {
        showError('Failed to load alternative details');
        console.error(error);
    }
}

async function deleteAlternative(id) {
    const confirmed = await confirmDelete('Are you sure you want to delete this alternative?');
    if (!confirmed) return;

    try {
        showLoading('Deleting alternative...');
        await alternativeAPI.delete(id);
        closeLoading();
        showSuccess('Alternative deleted successfully');
        loadAlternatives();
    } catch (error) {
        closeLoading();
        showError('Failed to delete alternative');
        console.error(error);
    }
}

async function submitForm(e) {
    e.preventDefault();
    
    const formData = {
        name: document.getElementById('name').value,
        description: document.getElementById('description').value,
        location: document.getElementById('location').value
    };

    try {
        showLoading(editingId ? 'Updating alternative...' : 'Creating alternative...');
        
        if (editingId) {
            await alternativeAPI.update(editingId, formData);
            showSuccess('Alternative updated successfully');
        } else {
            await alternativeAPI.create(formData);
            showSuccess('Alternative created successfully');
        }
        
        closeLoading();
        closeModal();
        loadAlternatives();
    } catch (error) {
        closeLoading();
        showError(editingId ? 'Failed to update alternative' : 'Failed to create alternative');
        console.error(error);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    loadAlternatives();
    
    // Event listeners
    document.getElementById('btnAdd')?.addEventListener('click', showModal);
    document.getElementById('btnCancel')?.addEventListener('click', closeModal);
    document.getElementById('alternativeForm')?.addEventListener('submit', submitForm);
    
    // Close modal on overlay click
    document.getElementById('alternativeModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'alternativeModal') {
            closeModal();
        }
    });
});

// Expose functions to window for onclick handlers
window.editAlternative = editAlternative;
window.deleteAlternative = deleteAlternative;
