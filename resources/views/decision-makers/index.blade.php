@extends('layouts.app')

@section('title', 'Decision Makers - GDSS')
@section('page-title', 'Decision Maker Management')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Decision Maker Management</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Manage decision makers and their weights</p>
        </div>
        <button id="btnAdd" class="btn btn-primary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Decision Maker
        </button>
    </div>

    <!-- Info Alert -->
    <div class="alert alert-info">
        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <strong>Note:</strong> Decision Maker weights are used in BORDA calculation to aggregate individual preferences.
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position/Role</th>
                            <th>Weight</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="dmTableBody">
                        <tr>
                            <td colspan="5" class="text-center py-8">
                                <div class="spinner mx-auto"></div>
                                <p class="text-gray-500 dark:text-gray-400 mt-2">Loading data...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="dmModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full slide-in">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Add Decision Maker</h3>
        </div>
        <form id="dmForm" class="p-6 space-y-4">
            <input type="hidden" id="dmId">
            
            <div class="form-group">
                <label for="name" class="form-label">Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" class="form-input" placeholder="e.g., John Doe" required>
            </div>

            <div class="form-group">
                <label for="position" class="form-label">Position/Role</label>
                <input type="text" id="position" class="form-input" placeholder="e.g., Senior Manager">
            </div>

            <div class="form-group">
                <label for="weight" class="form-label">Weight <span class="text-red-500">*</span></label>
                <input type="number" id="weight" class="form-input" step="0.01" min="0" max="1" placeholder="0.0 - 1.0" required>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Value between 0 and 1 (e.g., 0.3 for 30% influence)</p>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" id="btnCancel" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <span id="btnSubmitText">Save Decision Maker</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('page-scripts')
@vite('resources/js/pages/decision-makers.js')
@endsection
