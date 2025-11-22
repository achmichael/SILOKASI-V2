@extends('layouts.app')

@section('title', 'ANP Interdependency - GDSS')
@section('page-title', 'ANP Interdependency Matrix')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">ANP Interdependency Matrix</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Define criteria interdependencies for ANP calculation</p>
        </div>
        <button id="btnSaveMatrix" class="btn btn-success">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
            </svg>
            Save Matrix
        </button>
    </div>

    <!-- Info Alert -->
    <div class="alert alert-info">
        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <strong>Note:</strong> Values represent the degree of influence each criterion (row) has on another criterion (column). Use values between 0 and 1, where higher values indicate stronger influence.
    </div>

    <!-- ANP Interdependency Matrix -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold">Interdependency Matrix</h2>
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

    <!-- Matrix Guidelines -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold">Guidelines</h2>
        </div>
        <div class="card-body">
            <ul class="list-disc list-inside space-y-2 text-gray-700 dark:text-gray-300">
                <li>Diagonal values (self-influence) are always 0</li>
                <li>Use 0 if criterion has no influence on another</li>
                <li>Use values 0.1-0.3 for weak influence</li>
                <li>Use values 0.4-0.6 for moderate influence</li>
                <li>Use values 0.7-1.0 for strong influence</li>
                <li>Each column should sum to approximately 1.0 for normalized results</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
@vite('resources/js/pages/anp.js')
@endsection
