@extends('layouts.app')

@section('title', 'Alternative Ratings - GDSS')
@section('page-title', 'Alternative Ratings')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Alternative Ratings by Decision Makers</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Rate each alternative against all criteria</p>
        </div>
        <button id="btnSaveRatings" class="btn btn-success">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
            </svg>
            Save All Ratings
        </button>
    </div>

    <!-- Decision Maker Selector -->
    <div class="card">
        <div class="card-body">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Select Decision Maker
            </label>
            <select id="dmSelector" class="form-select">
                <option value="">Loading decision makers...</option>
            </select>
        </div>
    </div>

    <!-- Ratings Grid -->
    <div id="ratingsContainer">
        <div class="text-center py-8">
            <div class="spinner mx-auto"></div>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Loading...</p>
        </div>
    </div>

    <!-- Rating Scale Reference -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold">Rating Scale</h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="flex items-center gap-2 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <span class="badge badge-danger">1</span>
                    <span class="text-sm">Very Poor</span>
                </div>
                <div class="flex items-center gap-2 p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                    <span class="badge badge-warning">2</span>
                    <span class="text-sm">Poor</span>
                </div>
                <div class="flex items-center gap-2 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <span class="badge badge-gray">3</span>
                    <span class="text-sm">Fair</span>
                </div>
                <div class="flex items-center gap-2 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <span class="badge badge-info">4</span>
                    <span class="text-sm">Good</span>
                </div>
                <div class="flex items-center gap-2 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <span class="badge badge-success">5</span>
                    <span class="text-sm">Excellent</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
@vite('resources/js/pages/ratings.js')
@endsection
