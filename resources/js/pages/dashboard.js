import { criteriaAPI, alternativeAPI, decisionMakerAPI, calculationAPI, showLoading, closeLoading, showSuccess, showError } from '../api.js';

// Load dashboard data
async function loadDashboardData() {
    try {
        // Load counts
        const [criteriaRes, alternativesRes, dmRes] = await Promise.all([
            criteriaAPI.getAll(),
            alternativeAPI.getAll(),
            decisionMakerAPI.getAll()
        ]);

        document.getElementById('criteriaCount').textContent = criteriaRes.data.data.length;
        document.getElementById('alternativesCount').textContent = alternativesRes.data.data.length;
        document.getElementById('dmCount').textContent = dmRes.data.data.length;

        // Update step indicators
        updateStepIndicators(criteriaRes.data.data.length, alternativesRes.data.data.length);

        // Load final ranking if available
        try {
            const rankingRes = await calculationAPI.getFinalRanking();
            displayRanking(rankingRes.data.data);
            document.getElementById('calculationStatus').textContent = 'Completed';
        } catch (error) {
            // No results yet
            console.log('No calculation results yet');
        }
    } catch (error) {
        console.error('Error loading dashboard:', error);
    }
}

function updateStepIndicators(criteriaCount, alternativesCount) {
    const step1 = document.getElementById('step1');
    const line1 = document.getElementById('line1');

    if (criteriaCount > 0 && alternativesCount > 0) {
        step1.classList.remove('pending');
        step1.classList.add('completed');
        line1.classList.add('completed');
    }
}

function displayRanking(rankings) {
    const container = document.getElementById('rankingContainer');
    
    if (!rankings || rankings.length === 0) return;

    const html = `
        <div class="space-y-3">
            ${rankings.slice(0, 5).map((item, index) => `
                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full ${
                        index === 0 ? 'bg-yellow-400 text-yellow-900' :
                        index === 1 ? 'bg-gray-300 text-gray-900' :
                        index === 2 ? 'bg-orange-400 text-orange-900' :
                        'bg-blue-100 text-blue-900 dark:bg-blue-900 dark:text-blue-300'
                    } font-bold">
                        ${index + 1}
                    </div>
                    <div class="ml-4 flex-1">
                        <h4 class="font-semibold text-gray-900 dark:text-white">${item.name}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">${item.code}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-blue-600 dark:text-blue-400">${item.borda_score?.toFixed(2) || item.score?.toFixed(2)}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Score</p>
                    </div>
                </div>
            `).join('')}
        </div>
    `;

    container.innerHTML = html;
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    loadDashboardData();
    
    // Calculate All button
    document.getElementById('btnCalculateAll')?.addEventListener('click', async () => {
        try {
            showLoading();
            const response = await calculationAPI.calculateAll();
            closeLoading();
            
            showSuccess('Calculation completed successfully!');
            
            // Reload dashboard
            loadDashboardData();
        } catch (error) {
            closeLoading();
            showError(error.response?.data?.message || 'Calculation failed');
        }
    });
});
