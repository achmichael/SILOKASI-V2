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
            <div class="p-5 rounded-xl border-l-4 ${
                data.consistency_ratio < 0.1 
                    ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-500' 
                    : 'bg-rose-50 dark:bg-rose-900/20 border-rose-500'
            }">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 ${
                        data.consistency_ratio < 0.1 
                            ? 'text-emerald-600 dark:text-emerald-400' 
                            : 'text-rose-600 dark:text-rose-400'
                    }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-bold text-gray-900 dark:text-white">Consistency Check</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Consistency Ratio: <span class="font-mono font-semibold">${data.consistency_ratio?.toFixed(4) || 'N/A'}</span>
                            <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-semibold ${
                                data.consistency_ratio < 0.1 
                                    ? 'bg-emerald-100 dark:bg-emerald-800 text-emerald-700 dark:text-emerald-200' 
                                    : 'bg-rose-100 dark:bg-rose-800 text-rose-700 dark:text-rose-200'
                            }">
                                ${data.consistency_ratio < 0.1 ? '✓ Consistent' : '✗ Inconsistent'}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Criteria Weights
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Criteria</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Weight</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Percentage</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            ${data.criteria?.map(c => `
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                            ${c.code}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">${c.name}</td>
                                    <td class="px-6 py-4 text-sm font-mono font-semibold text-gray-700 dark:text-gray-300">${c.weight.toFixed(4)}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 max-w-xs">
                                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2.5 rounded-full transition-all duration-500" style="width: ${(c.weight * 100).toFixed(0)}%"></div>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 min-w-[3rem]">${(c.weight * 100).toFixed(1)}%</span>
                                        </div>
                                    </td>
                                </tr>
                            `).join('') || '<tr><td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">No data available</td></tr>'}
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
            <div class="p-5 rounded-xl border-l-4 bg-blue-50 dark:bg-blue-900/20 border-blue-500">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-bold text-gray-900 dark:text-white">ANP Calculation Method</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            ANP weights calculated by multiplying AHP weights with interdependency matrix
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                        </svg>
                        ANP Weights Comparison
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Criteria</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">AHP Weight</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">ANP Weight</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            ${data.criteria?.map(c => `
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300">
                                            ${c.code}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">${c.name}</td>
                                    <td class="px-6 py-4 text-sm font-mono text-gray-600 dark:text-gray-400">${c.ahp_weight?.toFixed(4) || '-'}</td>
                                    <td class="px-6 py-4 text-sm font-mono font-bold text-purple-600 dark:text-purple-400">${c.anp_weight?.toFixed(4) || '-'}</td>
                                </tr>
                            `).join('') || '<tr><td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">No data available</td></tr>'}
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
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Weighted Product Results
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rank</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alternative</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Vector S</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Preference Score</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            ${data.alternatives_by_rank?.map((alt, index) => `
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors ${
                                    index === 0 ? 'bg-yellow-50/50 dark:bg-yellow-900/10' : ''
                                }">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            ${index < 3 ? `
                                                <svg class="w-5 h-5 ${
                                                    index === 0 ? 'text-yellow-500' :
                                                    index === 1 ? 'text-gray-400' :
                                                    'text-orange-400'
                                                }" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                </svg>
                                            ` : ''}
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold ${
                                                index === 0 ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300' :
                                                index === 1 ? 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300' :
                                                index === 2 ? 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300' :
                                                'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300'
                                            }">
                                                #${alt.rank}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                            ${alt.code}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">${alt.name}</td>
                                    <td class="px-6 py-4 text-sm font-mono text-gray-600 dark:text-gray-400">${alt.vector_s?.toFixed(4) || '-'}</td>
                                    <td class="px-6 py-4 text-sm font-mono font-bold text-green-600 dark:text-green-400">${alt.preference_score?.toFixed(4) || alt.vector_v?.toFixed(4) || '-'}</td>
                                </tr>
                            `).join('') || '<tr><td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">No data available</td></tr>'}
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
            <div class="p-5 rounded-xl border-l-4 bg-indigo-50 dark:bg-indigo-900/20 border-indigo-500">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <div>
                        <p class="font-bold text-gray-900 dark:text-white">BORDA Aggregation Method</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Final ranking aggregated from all decision makers using weighted BORDA count
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                ${data.alternatives?.map((alt, index) => `
                    <div class="bg-white dark:bg-gray-800 rounded-xl border-2 ${
                        index === 0 ? 'border-yellow-400 dark:border-yellow-500 shadow-lg shadow-yellow-500/20' :
                        index === 1 ? 'border-gray-400 dark:border-gray-500 shadow-md' :
                        index === 2 ? 'border-orange-400 dark:border-orange-500 shadow-md' :
                        'border-gray-200 dark:border-gray-700'
                    } overflow-hidden transition-all duration-300 hover:shadow-xl">
                        <div class="p-6 ${
                            index === 0 ? 'bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20' :
                            'bg-gray-50 dark:bg-gray-800/50'
                        }">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center justify-center w-16 h-16 rounded-full ${
                                        index === 0 ? 'bg-yellow-400 text-yellow-900' :
                                        index === 1 ? 'bg-gray-400 text-gray-900' :
                                        index === 2 ? 'bg-orange-400 text-orange-900' :
                                        'bg-blue-500 text-white'
                                    } font-bold text-2xl shadow-lg">
                                        ${index < 3 ? ['🥇', '🥈', '🥉'][index] : `#${alt.rank}`}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300">
                                                ${alt.code}
                                            </span>
                                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Rank #${alt.rank}</span>
                                        </div>
                                        <h4 class="font-bold text-xl text-gray-900 dark:text-white">${alt.name}</h4>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                        ${alt.borda_score?.toFixed(2)}
                                    </p>
                                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-1">BORDA Score</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                            <h5 class="font-bold text-sm text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Decision Maker Contributions
                            </h5>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Decision Maker</th>
                                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Weight</th>
                                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Vector V</th>
                                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">WP Rank</th>
                                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Borda Points</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        ${alt.decision_makers_detail?.map(dm => `
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">${dm.dm_name}</td>
                                                <td class="px-4 py-3 text-sm">
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                            <div class="bg-indigo-500 h-2 rounded-full" style="width: ${(dm.dm_weight * 100).toFixed(0)}%"></div>
                                                        </div>
                                                        <span class="text-xs font-semibold text-gray-600 dark:text-gray-400">${(dm.dm_weight * 100).toFixed(0)}%</span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-sm font-mono font-semibold text-gray-700 dark:text-gray-300">${dm.vector_v?.toFixed(4) || '-'}</td>
                                                <td class="px-4 py-3 text-sm font-bold text-purple-600 dark:text-purple-400">#${dm.ranking}</td>
                                                <td class="px-4 py-3">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300">
                                                        ${dm.borda_points} pts
                                                    </span>
                                                </td>
                                            </tr>
                                        `).join('') || '<tr><td colspan="5" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">No details available</td></tr>'}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                `).join('') || '<div class="text-center py-8 text-gray-500 dark:text-gray-400">No data available</div>'}
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
