@extends('layouts.app')

@section('title', 'Criteria Management')
@section('page-title', 'Criteria Setup')
@section('page-subtitle', 'Define and configure evaluation parameters')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Evaluation Criteria</h1>
            <p class="text-slate-500 mt-1">Manage the factors used to evaluate housing locations.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="hidden sm:flex gap-2">
                <button class="p-2.5 text-slate-500 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Filter">
                    <i data-lucide="filter" class="w-5 h-5"></i>
                </button>
                <button class="p-2.5 text-slate-500 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Export">
                    <i data-lucide="download" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>
            
            <button id="btnAdd" class="btn-primary flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-lg font-medium shadow-lg shadow-primary-600/20 transition-all hover:shadow-primary-600/40 hover:-translate-y-0.5">
                <i data-lucide="plus" class="w-5 h-5"></i>
                <span>Add New Criteria</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Criteria</p>
                <p class="text-xl font-bold text-slate-800 mt-1" id="statTotal">--</p>
            </div>
            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg"><i data-lucide="layers" class="w-5 h-5"></i></div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Type: Benefit</p>
                <p class="text-xl font-bold text-emerald-600 mt-1" id="statBenefit">--</p>
            </div>
            <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg"><i data-lucide="trending-up" class="w-5 h-5"></i></div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Type: Cost</p>
                <p class="text-xl font-bold text-rose-600 mt-1" id="statCost">--</p>
            </div>
            <div class="p-2 bg-rose-50 text-rose-600 rounded-lg"><i data-lucide="trending-down" class="w-5 h-5"></i></div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-24">Code</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Criteria Name</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-32">Type</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-32 text-right">Weight (AHP)</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-32 text-right">Weight (ANP)</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-24 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="criteriaTableBody" class="divide-y divide-slate-100">
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-10 h-10 border-4 border-primary-100 border-t-primary-600 rounded-full animate-spin mb-3"></div>
                                <p class="text-slate-500 font-medium">Loading criteria data...</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
            <span class="text-xs text-slate-500">Showing all records</span>
        </div>
    </div>
</div>

<div id="criteriaModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="modalPanel">
                
                <div class="bg-white px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900" id="modalTitle">Add New Criteria</h3>
                    <button type="button" id="btnCloseModal" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form id="criteriaForm">
                    <div class="px-6 py-6 space-y-5">
                        <input type="hidden" id="criteriaId">
                        
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label for="code" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Code <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-slate-400 font-mono text-xs">#</span>
                                    </div>
                                    <input type="text" id="code" class="block w-full pl-8 pr-3 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-900 placeholder-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all sm:text-sm" placeholder="C1" required>
                                </div>
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Attribute Type <span class="text-red-500">*</span>
                                </label>
                                <select id="type" class="block w-full py-2.5 pl-3 pr-10 rounded-lg border border-slate-300 bg-white text-slate-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all sm:text-sm" required>
                                    <option value="">Select Type</option>
                                    <option value="benefit">Benefit (↗)</option>
                                    <option value="cost">Cost (↘)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Criteria Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" class="block w-full px-3 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-900 placeholder-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all sm:text-sm" placeholder="e.g., Infrastructure Quality" required>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Description
                            </label>
                            <textarea id="description" rows="3" class="block w-full px-3 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-900 placeholder-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all sm:text-sm resize-none" placeholder="Brief explanation of this criteria..."></textarea>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3">
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 sm:w-auto transition-all">
                            <span id="btnSubmitText">Save Changes</span>
                        </button>
                        <button type="button" id="btnCancel" class="inline-flex w-full justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:w-auto transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<template id="rowTemplate">
    <tr class="hover:bg-slate-50 group transition-colors">
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex items-center justify-center h-8 w-10 rounded-md bg-slate-100 text-slate-700 font-mono font-bold text-sm code-cell"></span>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm font-medium text-slate-900 name-cell"></div>
            <div class="text-xs text-slate-500 truncate max-w-[200px] desc-cell"></div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium type-badge ring-1 ring-inset">
                <i class="w-3 h-3 type-icon"></i>
                <span class="type-text"></span>
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right font-mono text-sm text-slate-600 weight-ahp">
            -
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right font-mono text-sm text-slate-600 weight-anp">
            -
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <button class="btn-edit p-1.5 text-blue-600 hover:bg-blue-50 rounded-md transition-colors" title="Edit">
                    <i data-lucide="pencil" class="w-4 h-4"></i>
                </button>
                <button class="btn-delete p-1.5 text-rose-600 hover:bg-rose-50 rounded-md transition-colors" title="Delete">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>
        </td>
    </tr>
</template>
@endsection

@section('page-scripts')
    @vite('resources/js/pages/criteria.js')
@endsection