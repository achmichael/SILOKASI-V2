import { decisionMakerAPI, showSuccess, showError, showLoading, closeLoading, confirmDelete } from '../api.js';

let editingId = null;

async function loadDecisionMakers() {
    try {
        const response = await decisionMakerAPI.getAll();
        const dms = response.data.data;
        
        const tbody = document.getElementById('dmTableBody');
        
        if (dms.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-8">
                        <div class="empty-state">
                            <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <p class="empty-state-text">No decision makers yet</p>
                                <p class="empty-state-subtext">Click "Add Decision Maker" to create your first decision maker</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }
            
            tbody.innerHTML = dms.map((dm, index) => `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">${index + 1}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">${dm.name}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">${dm.email || '-'}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <div class="flex-1">
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-primary-600 h-2 rounded-full" style="width: ${(dm.weight * 100).toFixed(0)}%"></div>
                                </div>
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">${parseFloat(dm.weight).toFixed(2)}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div class="flex items-center gap-2">
                            <button onclick="window.editDecisionMaker(${dm.id})" class="btn-icon btn-icon-warning">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button onclick="window.deleteDecisionMaker(${dm.id})" class="btn-icon btn-icon-danger">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        } catch (error) {
            showError('Failed to load decision makers');
            console.error(error);
        }
    }

    function showModal() {
        document.getElementById('dmModal').classList.remove('hidden');
        document.getElementById('dmForm').reset();
        document.getElementById('modalTitle').textContent = 'Add Decision Maker';
        editingId = null;
    }

    function closeModal() {
        document.getElementById('dmModal').classList.add('hidden');
        document.getElementById('dmForm').reset();
        editingId = null;
    }

    async function editDecisionMaker(id) {
        try {
            const response = await decisionMakerAPI.getById(id);
            const dm = response.data.data;
            
            document.getElementById('name').value = dm.name;
            document.getElementById('email').value = dm.email || '';
            document.getElementById('weight').value = dm.weight;
            
            document.getElementById('modalTitle').textContent = 'Edit Decision Maker';
            editingId = id;
            
            document.getElementById('dmModal').classList.remove('hidden');
        } catch (error) {
            showError('Failed to load decision maker details');
            console.error(error);
        }
    }

    async function deleteDecisionMaker(id) {
        const confirmed = await confirmDelete('Are you sure you want to delete this decision maker?');
        if (!confirmed) return;

        try {
            showLoading('Deleting decision maker...');
            await decisionMakerAPI.delete(id);
            closeLoading();
            showSuccess('Decision maker deleted successfully');
            loadDecisionMakers();
        } catch (error) {
            closeLoading();
            showError('Failed to delete decision maker');
            console.error(error);
        }
    }

    async function submitForm(e) {
        e.preventDefault();
        
        const formData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            weight: parseFloat(document.getElementById('weight').value)
        };

        try {
            showLoading(editingId ? 'Updating decision maker...' : 'Creating decision maker...');
            
            if (editingId) {
                await decisionMakerAPI.update(editingId, formData);
                showSuccess('Decision maker updated successfully');
            } else {
                await decisionMakerAPI.create(formData);
                showSuccess('Decision maker created successfully');
            }
            
            closeLoading();
            closeModal();
            loadDecisionMakers();
        } catch (error) {
            closeLoading();
            showError(editingId ? 'Failed to update decision maker' : 'Failed to create decision maker');
            console.error(error);
        }
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        loadDecisionMakers();
        
        // Event listeners
        document.getElementById('btnAdd')?.addEventListener('click', showModal);
        document.getElementById('btnCancel')?.addEventListener('click', closeModal);
        document.getElementById('dmForm')?.addEventListener('submit', submitForm);
        
        // Close modal on overlay click
        document.getElementById('dmModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'dmModal') {
                closeModal();
            }
        });
    });

    // Expose functions to window for onclick handlers
    window.editDecisionMaker = editDecisionMaker;
    window.deleteDecisionMaker = deleteDecisionMaker;
