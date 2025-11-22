import { calculationAPI, showLoading, closeLoading, showSuccess, showError } from '../api.js';
import Chart from 'chart.js/auto';

let currentTab = 'final';
let resultsData = null;

// Tab switching
function setupTabs() {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const tab = btn.dataset.tab;
            switchTab(tab);
        });
    });
}

function switchTab(tab) {
    // Update buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector(`[data-tab="${tab}"]`)?.classList.add('active');

    // Update content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    document.getElementById(`tab-${tab}`)?.classList.remove('hidden');

    currentTab = tab;
    
    // Load data if needed
    if (tab !== 'final') {
        loadTabData(tab);
    }
}

async function loadFinalRanking() {
    try {
        const response = await calculationAPI.getFinalRanking();
        const rankings = response.data.data;
        
        if (!rankings || rankings.length === 0) {
            return;
        }

        const html = `
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Chart -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Score Comparison</h3>
                    <canvas id="chartFinalRanking" class="w-full" height="300"></canvas>
                </div>

                <!-- Table -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ranking Details</h3>
                    <div class="space-y-3">
                        ${rankings.map((item, index) => `
                            <div class="flex items-center p-4 rounded-lg ${
                                index === 0 ? 'bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 border-2 border-yellow-400' :
                                index === 1 ? 'bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 border-2 border-gray-400' :
                                index === 2 ? 'bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 border-2 border-orange-400' :
                                'bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600'
                            }">
                                <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full ${
                                    index === 0 ? 'bg-yellow-400 text-yellow-900' :
                                    index === 1 ? 'bg-gray-400 text-gray-900' :
                                    index === 2 ? 'bg-orange-400 text-orange-900' :
                                    'bg-blue-500 text-white'
                                } font-bold text-lg">
                                    ${index + 1}
                                </div>
                                <div class="ml-4 flex-1">
                                    <h4 class="font-bold text-gray-900 dark:text-white text-lg">${item.name}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">${item.code}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">${item.borda_score?.toFixed(2) || item.score?.toFixed(2)}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">BORDA Score</p>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>

            <!-- Winner Card -->
            <div class="mt-6 p-6 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-2 border-green-500 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600 dark:text-green-400 mb-1">🏆 RECOMMENDED LOCATION</p>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">${rankings[0].name}</h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Code: ${rankings[0].code} • Score: ${(rankings[0].borda_score || rankings[0].score).toFixed(2)}</p>
                    </div>
                    <div class="text-6xl">🎯</div>
                </div>
            </div>
        `;

        document.getElementById('finalRankingContent').innerHTML = html;

        // Create chart
        const ctx = document.getElementById('chartFinalRanking');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: rankings.map(r => r.code),
                    datasets: [{
                        label: 'BORDA Score',
                        data: rankings.map(r => r.borda_score || r.score),
                        backgroundColor: rankings.map((_, i) => 
                            i === 0 ? 'rgba(250, 204, 21, 0.8)' :
                            i === 1 ? 'rgba(156, 163, 175, 0.8)' :
                            i === 2 ? 'rgba(251, 146, 60, 0.8)' :
                            'rgba(59, 130, 246, 0.8)'
                        ),
                        borderColor: rankings.map((_, i) => 
                            i === 0 ? 'rgb(250, 204, 21)' :
                            i === 1 ? 'rgb(156, 163, 175)' :
                            i === 2 ? 'rgb(251, 146, 60)' :
                            'rgb(59, 130, 246)'
                        ),
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: (items) => rankings[items[0].dataIndex].name,
                                label: (item) => `Score: ${item.parsed.y.toFixed(2)}`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    } catch (error) {
        console.error('Error loading final ranking:', error);
    }
}

async function loadTabData(tab) {
    const contentId = `${tab}Content`;
    const container = document.getElementById(contentId);
    
    try {
        container.innerHTML = '<div class="text-center py-8"><div class="spinner mx-auto"></div><p class="text-gray-500 dark:text-gray-400 mt-2">Loading...</p></div>';

        let response;
        switch(tab) {
            case 'ahp':
                response = await calculationAPI.calculateAHP();
                displayAHPResults(response.data.data, container);
                break;
            case 'anp':
                response = await calculationAPI.calculateANP();
                displayANPResults(response.data.data, container);
                break;
            case 'wp':
                response = await calculationAPI.calculateWP();
                displayWPResults(response.data.data, container);
                break;
            case 'borda':
                response = await calculationAPI.calculateBorda();
                displayBORDAResults(response.data.data, container);
                break;
        }
    } catch (error) {
        container.innerHTML = '<div class="alert alert-error">Failed to load data</div>';
    }
}

function displayAHPResults(data, container) {
    const html = `
        <div class="space-y-6">
            <div class="alert alert-success">
                <strong>Consistency Ratio:</strong> ${data.consistency_ratio?.toFixed(4) || 'N/A'} 
                ${data.consistency_ratio < 0.1 ? '✓ Consistent' : '✗ Inconsistent'}
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-3">Criteria Weights</h3>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Criteria</th>
                                <th>Weight</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.criteria?.map(c => `
                                <tr>
                                    <td><span class="badge badge-primary">${c.code}</span></td>
                                    <td>${c.name}</td>
                                    <td>${c.weight.toFixed(4)}</td>
                                    <td>
                                        <div class="flex items-center">
                                            <div class="w-32 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-2">
                                                <div class="progress-bar" style="width: ${(c.weight * 100).toFixed(0)}%"></div>
                                            </div>
                                            <span class="text-sm">${(c.weight * 100).toFixed(1)}%</span>
                                        </div>
                                    </td>
                                </tr>
                            `).join('') || '<tr><td colspan="4">No data</td></tr>'}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
    container.innerHTML = html;
}

function displayANPResults(data, container) {
    const html = `
        <div class="space-y-6">
            <div class="alert alert-info">
                ANP weights calculated by multiplying AHP weights with interdependency matrix
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-3">ANP Weights</h3>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Criteria</th>
                                <th>AHP Weight</th>
                                <th>ANP Weight</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.criteria?.map(c => `
                                <tr>
                                    <td><span class="badge badge-primary">${c.code}</span></td>
                                    <td>${c.name}</td>
                                    <td>${c.ahp_weight?.toFixed(4) || '-'}</td>
                                    <td class="font-semibold">${c.anp_weight?.toFixed(4) || '-'}</td>
                                </tr>
                            `).join('') || '<tr><td colspan="4">No data</td></tr>'}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
    container.innerHTML = html;
}

function displayWPResults(data, container) {
    const html = `
        <div class="space-y-6">
            <div>
                <h3 class="text-lg font-semibold mb-3">Weighted Product Results</h3>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Code</th>
                                <th>Alternative</th>
                                <th>Vector S</th>
                                <th>Vector V (Preference Score)</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.alternatives_by_rank?.map((alt, index) => `
                                <tr>
                                    <td>
                                        <span class="badge ${
                                            index === 0 ? 'badge-success' :
                                            index < 3 ? 'badge-info' :
                                            'badge-gray'
                                        }">${alt.rank}</span>
                                    </td>
                                    <td><span class="badge badge-primary">${alt.code}</span></td>
                                    <td class="font-medium">${alt.name}</td>
                                    <td>${alt.vector_s?.toFixed(4) || '-'}</td>
                                    <td class="font-semibold text-blue-600 dark:text-blue-400">${alt.preference_score?.toFixed(4) || alt.vector_v?.toFixed(4) || '-'}</td>
                                </tr>
                            `).join('') || '<tr><td colspan="5">No data</td></tr>'}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
    container.innerHTML = html;
}

function displayBORDAResults(data, container) {
    const html = `
        <div class="space-y-6">
            <div>
                <h3 class="text-lg font-semibold mb-3">BORDA Aggregation Results</h3>
                ${data.alternatives?.map(alt => `
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-bold text-lg">${alt.code} - ${alt.name}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Final Rank: ${alt.rank}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">${alt.borda_score?.toFixed(2)}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">BORDA Score</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="font-semibold mb-2">Decision Maker Contributions:</h5>
                            <div class="table-container">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>DM</th>
                                            <th>Weight</th>
                                            <th>Vector V</th>
                                            <th>WP Rank</th>
                                            <th>Borda Points</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${alt.decision_makers_detail?.map(dm => `
                                            <tr>
                                                <td>${dm.dm_name}</td>
                                                <td>${(dm.dm_weight * 100).toFixed(0)}%</td>
                                                <td class="font-semibold">${dm.vector_v?.toFixed(4) || '-'}</td>
                                                <td>${dm.ranking}</td>
                                                <td><span class="badge badge-success">${dm.borda_points}</span></td>
                                            </tr>
                                        `).join('') || '<tr><td colspan="5">No details</td></tr>'}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                `).join('') || '<p>No data available</p>'}
            </div>
        </div>
    `;
    container.innerHTML = html;
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    setupTabs();
    loadFinalRanking();
    
    // Calculate All button
    document.getElementById('btnCalculateAll')?.addEventListener('click', async () => {
        try {
            showLoading();
            await calculationAPI.calculateAll();
            closeLoading();
            
            showSuccess('Calculation completed successfully!');
            loadFinalRanking();
        } catch (error) {
            closeLoading();
            showError(error.response?.data?.message || 'Calculation failed');
        }
    });
});
