import { criteriaAPI, showSuccess, showError, showLoading, closeLoading, confirmDelete } from '../api.js';

let editingId = null;

async function loadCriteria() {
    console.log('Loading criteria...');
    try {
        const response = await criteriaAPI.getAll();
        console.log('API Response:', response);
        console.log('Response data:', response.data);
        
        const criteria = response.data.data;
        console.log('Criteria array:', criteria);
        
        const tbody = document.getElementById('criteriaTableBody');
        const template = document.getElementById('rowTemplate');
        
        if (!tbody) {
            console.error('Table body not found!');
            return;
        }
        
        if (!template) {
            console.error('Row template not found!');
            return;
        }
        
        // Update statistics
        updateStatistics(criteria);
        
        if (criteria.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                <i data-lucide="inbox" class="w-8 h-8 text-slate-400"></i>
                            </div>
                            <p class="text-slate-900 dark:text-white font-semibold mb-1">No criteria yet</p>
                            <p class="text-sm text-slate-500">Click "Add New Criteria" to create your first evaluation criterion</p>
                        </div>
                    </td>
                </tr>
            `;
            lucide.createIcons();
            return;
        }
        
        tbody.innerHTML = '';
        
        criteria.forEach((item) => {
            console.log('Processing item:', item);
            const clone = template.content.cloneNode(true);
            const row = clone.querySelector('tr');
            
            // Set code
            row.querySelector('.code-cell').textContent = item.code || 'N/A';
            
            // Set name and description
            row.querySelector('.name-cell').textContent = item.name;
            row.querySelector('.desc-cell').textContent = item.description || 'No description';
            
            // Set type badge
            const typeBadge = row.querySelector('.type-badge');
            const typeIcon = row.querySelector('.type-icon');
            const typeText = row.querySelector('.type-text');
            
            if (item.type === 'benefit') {
                typeBadge.classList.add('bg-emerald-50', 'text-emerald-700', 'ring-emerald-600/20');
                typeIcon.setAttribute('data-lucide', 'trending-up');
                typeText.textContent = 'Benefit';
            } else {
                typeBadge.classList.add('bg-rose-50', 'text-rose-700', 'ring-rose-600/20');
                typeIcon.setAttribute('data-lucide', 'trending-down');
                typeText.textContent = 'Cost';
            }
            
            // Set weights
            row.querySelector('.weight-ahp').textContent = item.weight_ahp ? item.weight_ahp.toFixed(4) : '-';
            row.querySelector('.weight-anp').textContent = item.weight_anp ? item.weight_anp.toFixed(4) : '-';
            
            // Set action buttons
            row.querySelector('.btn-edit').addEventListener('click', () => editCriteria(item.id));
            row.querySelector('.btn-delete').addEventListener('click', () => deleteCriteria(item.id));
            
            tbody.appendChild(clone);
        });
        
        console.log('Rows added, reinitializing icons...');
        // Re-initialize Lucide icons
        lucide.createIcons();
        
    } catch (error) {
        console.error('Error loading criteria:', error);
        console.error('Error details:', error.response);
        
        const tbody = document.getElementById('criteriaTableBody');
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-rose-50 rounded-full flex items-center justify-center mb-4">
                            <i data-lucide="alert-circle" class="w-8 h-8 text-rose-500"></i>
                        </div>
                        <p class="text-slate-900 dark:text-white font-semibold mb-1">Failed to load criteria</p>
                        <p class="text-sm text-slate-500">Please try refreshing the page</p>
                    </div>
                </td>
            </tr>
        `;
        lucide.createIcons();
        showError('Failed to load criteria');
    }
}

function updateStatistics(criteria) {
    const total = criteria.length;
    const benefit = criteria.filter(c => c.type === 'benefit').length;
    const cost = criteria.filter(c => c.type === 'cost').length;
    
    document.getElementById('statTotal').textContent = total;
    document.getElementById('statBenefit').textContent = benefit;
    document.getElementById('statCost').textContent = cost;
}

function showModal() {
    const modal = document.getElementById('criteriaModal');
    const backdrop = document.getElementById('modalBackdrop');
    const panel = document.getElementById('modalPanel');
    
    modal.classList.remove('hidden');
    
    setTimeout(() => {
        backdrop.classList.remove('opacity-0');
        panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-4', 'sm:scale-95');
        panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('criteriaModal');
    const backdrop = document.getElementById('modalBackdrop');
    const panel = document.getElementById('modalPanel');
    
    backdrop.classList.add('opacity-0');
    panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-4', 'sm:scale-95');
    panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.getElementById('criteriaForm').reset();
        document.getElementById('modalTitle').textContent = 'Add New Criteria';
        document.getElementById('btnSubmitText').textContent = 'Save Changes';
        editingId = null;
    }, 300);
}

async function editCriteria(id) {
    try {
        const response = await criteriaAPI.getById(id);
        console.log('response', response);
        const criteria = response.data.data;
    
        document.getElementById('criteriaId').value = criteria.id;
        document.getElementById('code').value = criteria.code || '';
        document.getElementById('name').value = criteria.name;
        document.getElementById('description').value = criteria.description || '';
        document.getElementById('type').value = criteria.type;
        
        document.getElementById('modalTitle').textContent = 'Edit Criteria';
        document.getElementById('btnSubmitText').textContent = 'Update Changes';
        editingId = id;
        
        showModal();
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
        code: document.getElementById('code').value,
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
    const btnAdd = document.getElementById('btnAdd');
    const btnCancel = document.getElementById('btnCancel');
    const btnCloseModal = document.getElementById('btnCloseModal');
    const criteriaForm = document.getElementById('criteriaForm');
    const criteriaModal = document.getElementById('criteriaModal');
    
    if (btnAdd) btnAdd.addEventListener('click', showModal);
    if (btnCancel) btnCancel.addEventListener('click', closeModal);
    if (btnCloseModal) btnCloseModal.addEventListener('click', closeModal);
    if (criteriaForm) criteriaForm.addEventListener('submit', submitForm);
    
    // Close modal on overlay click
    if (criteriaModal) {
        criteriaModal.addEventListener('click', (e) => {
            if (e.target.id === 'criteriaModal' || e.target.id === 'modalBackdrop') {
                closeModal();
            }
        });
    }
});

// Expose functions to window for onclick handlers
window.editCriteria = editCriteria;
window.deleteCriteria = deleteCriteria;
