@extends('layouts.app')

@section('title', 'Pairwise Comparison - GDSS')
@section('page-title', 'AHP Pairwise Comparison')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">AHP Pairwise Comparison Matrix</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Compare criteria pairs using Saaty's 1-9 scale</p>
        </div>
        <button id="btnSaveMatrix" class="btn btn-success">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
            </svg>
            Save Matrix
        </button>
    </div>

    <!-- Saaty Scale Reference -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold">Saaty's Scale Reference</h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <span class="badge badge-primary text-lg">1</span>
                    <span class="text-sm">Equal importance</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <span class="badge badge-primary text-lg">3</span>
                    <span class="text-sm">Moderate importance</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <span class="badge badge-primary text-lg">5</span>
                    <span class="text-sm">Strong importance</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <span class="badge badge-primary text-lg">7</span>
                    <span class="text-sm">Very strong importance</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <span class="badge badge-primary text-lg">9</span>
                    <span class="text-sm">Extreme importance</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <span class="badge badge-gray text-lg">2,4,6,8</span>
                    <span class="text-sm">Intermediate values</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pairwise Comparison Matrix -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold">Comparison Matrix</h2>
        </div>
        <div class="card-body">
            <div id="matrixContainer">
                <div class="text-center py-8">
                    <div class="spinner mx-auto"></div>
                    <p class="text-gray-500 dark:text-gray-400 mt-2">Loading criteria...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Consistency Check -->
    <div id="consistencyCard" class="card hidden">
        <div class="card-header">
            <h2 class="text-lg font-semibold">Consistency Check</h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="stat-card stat-card-blue">
                    <p class="text-blue-100 text-sm font-medium">Consistency Index (CI)</p>
                    <h3 id="ciValue" class="text-2xl font-bold mt-2">-</h3>
                </div>
                <div class="stat-card stat-card-purple">
                    <p class="text-purple-100 text-sm font-medium">Random Index (RI)</p>
                    <h3 id="riValue" class="text-2xl font-bold mt-2">-</h3>
                </div>
                <div class="stat-card" id="crCard">
                    <p class="text-sm font-medium opacity-90">Consistency Ratio (CR)</p>
                    <h3 id="crValue" class="text-2xl font-bold mt-2">-</h3>
                    <p id="crStatus" class="text-xs mt-1 opacity-75"></p>
                </div>
            </div>
            <div class="mt-4">
                <div id="consistencyAlert" class="alert hidden">
                    <p></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
@vite('resources/js/pages/pairwise.js')
@endsection
