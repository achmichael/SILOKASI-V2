@extends('layouts.app')

@section('title', 'Dashboard - GDSS')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="card slide-in">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                        Welcome to GDSS! 👋
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Group Decision Support System menggunakan metode ANP-WP-BORDA untuk pemilihan lokasi perumahan
                    </p>
                </div>
                <div class="hidden md:block">
                    <svg class="w-24 h-24 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Criteria Count -->
        <div class="stat-card stat-card-blue slide-in" style="animation-delay: 0.1s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Criteria</p>
                    <h3 id="criteriaCount" class="text-3xl font-bold mt-2">0</h3>
                </div>
                <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
        </div>

        <!-- Alternatives Count -->
        <div class="stat-card stat-card-green slide-in" style="animation-delay: 0.2s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Alternatives</p>
                    <h3 id="alternativesCount" class="text-3xl font-bold mt-2">0</h3>
                </div>
                <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </div>

        <!-- Decision Makers Count -->
        <div class="stat-card stat-card-purple slide-in" style="animation-delay: 0.3s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Decision Makers</p>
                    <h3 id="dmCount" class="text-3xl font-bold mt-2">0</h3>
                </div>
                <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>

        <!-- Calculation Status -->
        <div class="stat-card stat-card-orange slide-in" style="animation-delay: 0.4s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Calculation Status</p>
                    <h3 id="calculationStatus" class="text-lg font-bold mt-2">Not Started</h3>
                </div>
                <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="card slide-in" style="animation-delay: 0.5s">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Workflow Progress</h2>
        </div>
        <div class="card-body">
            <div class="step-indicator">
                <!-- Step 1: Data Master -->
                <div class="step">
                    <div class="step-circle" id="step1">
                        <span>1</span>
                    </div>
                    <div class="hidden md:block ml-3">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Data Master</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Setup criteria & alternatives</p>
                    </div>
                </div>
                <div class="step-line" id="line1"></div>

                <!-- Step 2: AHP Matrix -->
                <div class="step">
                    <div class="step-circle pending" id="step2">
                        <span>2</span>
                    </div>
                    <div class="hidden md:block ml-3">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">AHP Matrix</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Pairwise comparison</p>
                    </div>
                </div>
                <div class="step-line" id="line2"></div>

                <!-- Step 3: ANP Matrix -->
                <div class="step">
                    <div class="step-circle pending" id="step3">
                        <span>3</span>
                    </div>
                    <div class="hidden md:block ml-3">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">ANP Matrix</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Interdependency</p>
                    </div>
                </div>
                <div class="step-line" id="line3"></div>

                <!-- Step 4: Ratings -->
                <div class="step">
                    <div class="step-circle pending" id="step4">
                        <span>4</span>
                    </div>
                    <div class="hidden md:block ml-3">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Ratings</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">DM evaluations</p>
                    </div>
                </div>
                <div class="step-line" id="line4"></div>

                <!-- Step 5: Results -->
                <div class="step">
                    <div class="step-circle pending" id="step5">
                        <span>5</span>
                    </div>
                    <div class="hidden md:block ml-3">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Results</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Final ranking</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Results -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Quick Actions -->
        <div class="card slide-in" style="animation-delay: 0.6s">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</h2>
            </div>
            <div class="card-body">
                <div class="space-y-3">
                    <a href="{{ route('criteria.index') }}" class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-all group">
                        <div class="bg-blue-500 p-3 rounded-lg text-white mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">Manage Criteria</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Setup evaluation criteria</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('alternatives.index') }}" class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-all group">
                        <div class="bg-green-500 p-3 rounded-lg text-white mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400">Manage Alternatives</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Add housing locations</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 dark:group-hover:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('pairwise.index') }}" class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-all group">
                        <div class="bg-purple-500 p-3 rounded-lg text-white mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400">Start Calculation</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Begin AHP matrix input</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <button id="btnCalculateAll" class="w-full flex items-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/30 transition-all group">
                        <div class="bg-orange-500 p-3 rounded-lg text-white mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="flex-1 text-left">
                            <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-orange-600 dark:group-hover:text-orange-400">Calculate All</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Run full calculation</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-600 dark:group-hover:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Final Ranking Preview -->
        <div class="card slide-in" style="animation-delay: 0.7s">
            <div class="card-header flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Final Ranking</h2>
                <a href="{{ route('results.index') }}" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                    View Details →
                </a>
            </div>
            <div class="card-body">
                <div id="rankingContainer">
                    <div class="empty-state">
                        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="empty-state-title">No Results Yet</h3>
                        <p class="empty-state-text">Complete the calculation process to see rankings</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
@vite('resources/js/pages/dashboard.js')
@endsection
