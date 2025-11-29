import { authAPI, calculationAPI, showSuccess, showError, showLoading, closeLoading } from '../api.js';

let currentUser = null;

async function loadData() {
    try {
        showLoading('Loading your results...');
        
        // Get current user
        const userRes = await authAPI.getCurrentUser();
        currentUser = userRes.data.data.user;
        
        updateUserCard();
        
        // Check if user is decision maker
        if (currentUser.role !== 'decision_maker') {
            showError('This page is only for decision makers');
            closeLoading();
            return;
        }
        
        // Load WP rankings for this DM
        await loadMyWPRankings();
        
        // Load BORDA results if available
        await loadBordaResults();
        
        closeLoading();
    } catch (error) {
        closeLoading();
        showError('Failed to load data');
        console.error(error);
    }
}

function updateUserCard() {
    if (!currentUser) return;
    
    const avatarEl = document.getElementById('userAvatar');
    const nameEl = document.getElementById('userName');
    const emailEl = document.getElementById('userEmail');
    
    if (avatarEl && currentUser.name) {
        const initials = currentUser.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
        avatarEl.textContent = initials;
    }
    
    if (nameEl) nameEl.textContent = currentUser.name;
    if (emailEl) emailEl.textContent = currentUser.email;
}

async function loadMyWPRankings() {
    const container = document.getElementById('wpRankingsContent');
    
    try {
        // Calculate WP for this decision maker
        const response = await calculationAPI.calculateWPForDM(currentUser.id);
        const data = response.data.data;
        
        // Check if user has provided ratings
        if (data && !data.has_ratings) {
            container.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full p-8 text-center">
                    <div class="w-16 h-16 bg-yellow-50 dark:bg-yellow-900/20 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">No Ratings Yet</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 max-w-xs mx-auto mb-6">
                        Please submit your ratings to see your personal rankings.
                    </p>
                    <a href="/ratings" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 text-white text-sm font-medium hover:bg-primary-700 transition-colors">
                        <span>Start Rating</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            `;
            return;
        }
        
        if (!data || !data.alternatives_by_rank) {
            container.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full p-8 text-center">
                    <p class="text-slate-500 dark:text-slate-400 font-medium">No rankings available yet.</p>
                </div>
            `;
            return;
        }
        
        // Display rankings
        const html = `
            <div class="space-y-3">
                ${data.alternatives_by_rank.map((alt, index) => `
                    <div class="flex items-center gap-4 p-3 rounded-xl bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                        <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg font-bold text-sm ${
                            index === 0 ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : 
                            index === 1 ? 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300' : 
                            index === 2 ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' : 
                            'bg-gray-50 text-gray-500 dark:bg-slate-800 dark:text-slate-500'
                        }">
                            ${index + 1}
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h4 class="font-semibold text-slate-900 dark:text-white truncate">${alt.name}</h4>
                                <span class="text-xs font-mono font-medium text-slate-500 dark:text-slate-400">${(alt.preference_score || alt.vector_v || 0).toFixed(4)}</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-1.5 overflow-hidden">
                                <div class="h-full rounded-full bg-primary-500"
                                     style="width: ${((alt.preference_score || alt.vector_v || 0) * 100).toFixed(0)}%"></div>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
        
        container.innerHTML = html;
    } catch (error) {
        console.error('Error loading WP rankings:', error);
        container.innerHTML = `
            <div class="flex flex-col items-center justify-center h-full p-8 text-center">
                <p class="text-red-500 font-medium">Failed to load rankings.</p>
                <button onclick="loadMyWPRankings()" class="mt-2 text-sm text-primary-600 hover:underline">Try Again</button>
            </div>
        `;
    }
}

async function loadBordaResults() {
    const container = document.getElementById('bordaResultsContent');
    
    try {
        // Try to get BORDA results
        const response = await calculationAPI.getBordaResults();
        const data = response.data.data;
        
        if (!data || !data.alternatives) {
            showNoBordaResults(container);
            return;
        }
        
        const maxScore = Math.max(...data.alternatives.map(a => a.borda_score || 0));

        // Display BORDA final rankings
        const html = `
            <div class="space-y-3">
                ${data.alternatives.map((alt, index) => `
                    <div class="flex items-center gap-4 p-3 rounded-xl bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                        <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg font-bold text-sm ${
                            index === 0 ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' : 
                            index === 1 ? 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300' : 
                            index === 2 ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' : 
                            'bg-gray-50 text-gray-500 dark:bg-slate-800 dark:text-slate-500'
                        }">
                            ${index + 1}
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h4 class="font-semibold text-slate-900 dark:text-white truncate">${alt.name}</h4>
                                <span class="text-xs font-mono font-medium text-slate-500 dark:text-slate-400">${(alt.borda_score || 0).toFixed(2)}</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-1.5 overflow-hidden">
                                <div class="h-full rounded-full bg-purple-500"
                                     style="width: ${maxScore > 0 ? ((alt.borda_score / maxScore) * 100).toFixed(0) : 0}%"></div>
                            </div>
                        </div>
                    </div>
                `).join('')}
                
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-slate-700 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <span>Participants: <strong class="text-slate-700 dark:text-slate-300">${data.decision_makers?.length || 0}</strong></span>
                    <span class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400 font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Consensus Reached
                    </span>
                </div>
            </div>
        `;
        
        container.innerHTML = html;
    } catch (error) {
        console.error('Error loading BORDA results:', error);
        showNoBordaResults(container);
    }
}

function showNoBordaResults(container) {
    container.innerHTML = `
        <div class="flex flex-col items-center justify-center h-full p-8 text-center">
            <div class="w-12 h-12 bg-gray-100 dark:bg-slate-700 rounded-xl flex items-center justify-center mb-3 text-gray-400 dark:text-slate-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Pending Calculation</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs mx-auto">
                Group consensus results are not yet available.
            </p>
        </div>
    `;
}

// Event listeners
document.addEventListener('DOMContentLoaded', () => {
    loadData();
    
    document.getElementById('btnRefresh')?.addEventListener('click', () => {
        loadData();
    });
});
