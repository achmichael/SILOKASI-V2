import { decisionMakerAPI, showSuccess, showError, showLoading, closeLoading } from '../api.js';

async function loadDecisionMakers() {
    try {
        const response = await decisionMakerAPI.getAll();
        const dms = response.data.data;
        
        // Update stats
        document.getElementById('statTotal').textContent = dms.length;
        
        const tbody = document.getElementById('dmTableBody');
        
        if (dms.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center py-8">
                        <div class="empty-state">
                            <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <p class="empty-state-text">No decision makers yet</p>
                                <p class="empty-state-subtext">Users with decision maker role will appear here</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }
            
            tbody.innerHTML = dms.map((dm, index) => `
                <tr>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-gray-900 dark:text-white">${dm.name}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">${dm.email}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-blue-600/20">
                            <i data-lucide="user-check" class="w-3 h-3"></i> ${dm.role === 'decision_maker' ? 'Decision Maker' : dm.role}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20">
                            <i data-lucide="check-circle-2" class="w-3 h-3"></i> Active
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            ${new Date(dm.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}
                        </div>
                    </td>
                </tr>
            `).join('');
            
            // Re-initialize Lucide icons for the status badges
            lucide.createIcons();
        } catch (error) {
            showError('Failed to load decision makers');
            console.error(error);
        }
    }

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    loadDecisionMakers();
});

