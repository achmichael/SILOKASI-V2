@extends('layouts.app')

@section('title', 'Manage Criteria - GDSS')
@section('page-title', 'Criteria Management')

@section('content')
<div class="space-y-6">
    <!-- Header with Add Button -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Criteria Management</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Manage evaluation criteria for housing location selection</p>
        </div>
        <button id="btnAdd" class="btn btn-primary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Criteria
        </button>
    </div>

    <!-- Criteria Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Weight (AHP)</th>
                            <th>Weight (ANP)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="criteriaTableBody">
                        <tr>
                            <td colspan="6" class="text-center py-8">
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

<!-- Add/Edit Modal -->
<div id="criteriaModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full slide-in">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Add Criteria</h3>
        </div>
        <form id="criteriaForm" class="p-6 space-y-4">
            <input type="hidden" id="criteriaId">
            
            <div class="form-group">
                <label for="code" class="form-label">Code <span class="text-red-500">*</span></label>
                <input type="text" id="code" class="form-input" placeholder="e.g., C1" required>
            </div>

            <div class="form-group">
                <label for="name" class="form-label">Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" class="form-input" placeholder="e.g., Lokasi Strategis" required>
            </div>

            <div class="form-group">
                <label for="type" class="form-label">Type <span class="text-red-500">*</span></label>
                <select id="type" class="form-select" required>
                    <option value="">-- Select Type --</option>
                    <option value="benefit">Benefit (Higher is Better)</option>
                    <option value="cost">Cost (Lower is Better)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" class="form-textarea" rows="3" placeholder="Optional description"></textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" id="btnCancel" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <span id="btnSubmitText">Save Criteria</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('page-scripts')
@vite('resources/js/pages/criteria.js')
@endsection
