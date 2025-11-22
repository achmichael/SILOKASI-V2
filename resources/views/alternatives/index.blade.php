@extends('layouts.app')

@section('title', 'Manage Alternatives - GDSS')
@section('page-title', 'Alternative Management')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Alternative Management</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Manage housing location alternatives</p>
        </div>
        <button id="btnAdd" class="btn btn-primary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Alternative
        </button>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>WP Score</th>
                            <th>Final Rank</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="alternativesTableBody">
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

<div id="alternativeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full slide-in">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Add Alternative</h3>
        </div>
        <form id="alternativeForm" class="p-6 space-y-4">
            <input type="hidden" id="alternativeId">
            
            <div class="form-group">
                <label for="code" class="form-label">Code <span class="text-red-500">*</span></label>
                <input type="text" id="code" class="form-input" placeholder="e.g., A1" required>
            </div>

            <div class="form-group">
                <label for="name" class="form-label">Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" class="form-input" placeholder="e.g., Gentan" required>
            </div>

            <div class="form-group">
                <label for="location" class="form-label">Location</label>
                <input type="text" id="location" class="form-input" placeholder="Full address">
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" class="form-textarea" rows="3"></textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" id="btnCancel" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <span id="btnSubmitText">Save Alternative</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('page-scripts')
@vite('resources/js/pages/alternatives.js')
@endsection
