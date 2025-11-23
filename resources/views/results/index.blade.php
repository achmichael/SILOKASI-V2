@extends('layouts.app')

@section('title', 'Calculation Results - GDSS')
@section('page-title', 'Calculation Results')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-tight">Calculation Results</h1>
            <p class="text-base text-gray-600 dark:text-gray-300 mt-2">Comprehensive overview of ANP-WP-BORDA calculations and rankings</p>
        </div>
        <button id="btnCalculateAll" class="btn btn-primary flex items-center gap-2 px-6 py-3 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold hover:from-blue-600 hover:to-blue-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            Calculate All
        </button>
    </div>

    <!-- Tabs Card -->
    <div class="card bg-white dark:bg-gray-900 rounded-2xl shadow-xl overflow-hidden ring-1 ring-gray-200 dark:ring-gray-700">
        <div class="border-b border-gray-200 dark:border-gray-700/50">
            <nav class="flex overflow-x-auto space-x-1 px-4 sm:px-6 py-1" aria-label="Tabs">
                <button class="tab-btn active flex items-center gap-2 px-4 py-4 text-sm font-medium whitespace-nowrap transition-all duration-300 rounded-t-lg" data-tab="final">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    Final Ranking
                </button>
                <button class="tab-btn flex items-center gap-2 px-4 py-4 text-sm font-medium whitespace-nowrap transition-all duration-300 rounded-t-lg" data-tab="ahp">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    AHP Results
                </button>
                <button class="tab-btn flex items-center gap-2 px-4 py-4 text-sm font-medium whitespace-nowrap transition-all duration-300 rounded-t-lg" data-tab="anp">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                    </svg>
                    ANP Results
                </button>
                <button class="tab-btn flex items-center gap-2 px-4 py-4 text-sm font-medium whitespace-nowrap transition-all duration-300 rounded-t-lg" data-tab="wp">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    WP Results
                </button>
                <button class="tab-btn flex items-center gap-2 px-4 py-4 text-sm font-medium whitespace-nowrap transition-all duration-300 rounded-t-lg" data-tab="borda">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    BORDA Results
                </button>
            </nav>
        </div>

        <div class="card-body p-6 sm:p-8">
            <!-- Final Ranking Tab -->
            <div id="tab-final" class="tab-content animate-fade-in">
                <div id="finalRankingContent">
                    <div class="empty-state flex flex-col items-center justify-center py-16 text-center bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                        <svg class="empty-state-icon w-20 h-20 text-blue-400 dark:text-blue-500 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="empty-state-title text-xl font-bold text-gray-900 dark:text-white mb-2">No Results Available Yet</h3>
                        <p class="empty-state-text text-sm text-gray-600 dark:text-gray-400 max-w-md">Initiate the full ANP-WP-BORDA analysis by clicking "Calculate All" to generate and view detailed results.</p>
                    </div>
                </div>
            </div>

            <!-- AHP Tab -->
            <div id="tab-ahp" class="tab-content hidden animate-fade-in">
                <div id="ahpContent" class="text-gray-600 dark:text-gray-300">Loading AHP results...</div>
            </div>

            <!-- ANP Tab -->
            <div id="tab-anp" class="tab-content hidden animate-fade-in">
                <div id="anpContent" class="text-gray-600 dark:text-gray-300">Loading ANP results...</div>
            </div>

            <!-- WP Tab -->
            <div id="tab-wp" class="tab-content hidden animate-fade-in">
                <div id="wpContent" class="text-gray-600 dark:text-gray-300">Loading WP results...</div>
            </div>

            <!-- BORDA Tab -->
            <div id="tab-borda" class="tab-content hidden animate-fade-in">
                <div id="bordaContent" class="text-gray-600 dark:text-gray-300">Loading BORDA results...</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.tab-btn {
    border-bottom: 2px solid transparent;
    color: #6b7280; /* gray-500 */
}

.tab-btn:hover {
    background-color: #f3f4f6; /* gray-100 */
    color: #374151; /* gray-700 */
}

.tab-btn.active {
    background-color: #ffffff; /* white */
    border-bottom-color: #3b82f6; /* blue-500 */
    color: #1d4ed8; /* blue-700 */
    box-shadow: inset 0 -1px 0 0 #3b82f6;
}

.dark .tab-btn {
    color: #9ca3af; /* gray-400 */
}

.dark .tab-btn:hover {
    background-color: #374151; /* gray-700 */
    color: #e5e7eb; /* gray-200 */
}

.dark .tab-btn.active {
    background-color: #1f2937; /* gray-800 */
    border-bottom-color: #60a5fa; /* blue-400 */
    color: #bfdbfe; /* blue-200 */
    box-shadow: inset 0 -1px 0 0 #60a5fa;
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endpush

@section('page-scripts')
@vite('resources/js/pages/results.js')
@endsection